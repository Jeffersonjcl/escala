<?php
require_once(__DIR__ . '/../_guard.php');
require_once(__DIR__ . '/xlsx_reader.php');
require_once("../../../conexao.php");

header('Content-Type: application/json; charset=utf-8');

function normalizar_cabecalho($valor) {
	$valor = trim((string) $valor);
	$valor = mb_strtolower($valor, 'UTF-8');
	$mapa = [
		'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
		'é' => 'e', 'ê' => 'e',
		'í' => 'i',
		'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
		'ú' => 'u',
		'ç' => 'c',
	];
	$valor = strtr($valor, $mapa);
	return preg_replace('/[^a-z0-9]/', '', $valor);
}

function ler_csv($caminhoArquivo) {
	$conteudo = file_get_contents($caminhoArquivo);
	$conteudo = preg_replace("/^\xEF\xBB\xBF/", '', $conteudo); // remove BOM

	$primeiraLinha = strtok($conteudo, "\n");
	$delimitador = substr_count($primeiraLinha, ';') > substr_count($primeiraLinha, ',') ? ';' : ',';

	$linhas = [];
	$fp = fopen('php://temp', 'r+');
	fwrite($fp, $conteudo);
	rewind($fp);
	while (($linha = fgetcsv($fp, 0, $delimitador)) !== false) {
		$linhas[] = $linha;
	}
	fclose($fp);

	return $linhas;
}

if (empty($_FILES['arquivo']['tmp_name'])) {
	echo json_encode(['erro' => 'Nenhum arquivo enviado.']);
	exit();
}

$nomeOriginal = $_FILES['arquivo']['name'];
$extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

try {
	if ($extensao === 'xlsx') {
		$linhas = xlsx_ler($_FILES['arquivo']['tmp_name']);
	} elseif ($extensao === 'csv') {
		$linhas = ler_csv($_FILES['arquivo']['tmp_name']);
	} else {
		echo json_encode(['erro' => 'Formato não suportado. Envie um arquivo .xlsx ou .csv.']);
		exit();
	}
} catch (Exception $e) {
	echo json_encode(['erro' => $e->getMessage()]);
	exit();
}

if (count($linhas) < 2) {
	echo json_encode(['erro' => 'A planilha não tem dados para importar (apenas cabeçalho ou vazia).']);
	exit();
}

$cabecalho = array_map('normalizar_cabecalho', array_shift($linhas));

$colunas = [
	'nome_guerra' => ['nomedeguerra', 'nomeguerra'],
	'nome_completo' => ['nomecompleto'],
	'matricula' => ['matricula'],
	'telefone' => ['telefone'],
	'grupo' => ['grupo'],
	'turno' => ['turno', 'turnopadrao'],
	'funcao' => ['funcao'],
	'posto' => ['postograduacao', 'posto', 'graduacao'],
	'numeral' => ['numeral'],
	'disponivel' => ['disponivel', 'disponivelparaservico'],
];

$indices = [];
foreach ($colunas as $campo => $possiveis) {
	$indices[$campo] = null;
	foreach ($possiveis as $possivel) {
		$pos = array_search($possivel, $cabecalho, true);
		if ($pos !== false) {
			$indices[$campo] = $pos;
			break;
		}
	}
}

if ($indices['nome_guerra'] === null || $indices['funcao'] === null) {
	echo json_encode(['erro' => 'A planilha precisa ter as colunas: Nome de Guerra e Função.']);
	exit();
}

function valor_da_linha($linha, $indices, $campo) {
	$i = $indices[$campo];
	if ($i === null || !isset($linha[$i])) {
		return '';
	}
	return trim((string) $linha[$i]);
}

$funcoes = [];
foreach ($pdo->query("SELECT id, nome FROM funcoes") as $f) {
	$funcoes[mb_strtolower($f['nome'], 'UTF-8')] = $f['id'];
}

$postos = [];
foreach ($pdo->query("SELECT id, nome FROM postos") as $p) {
	$postos[mb_strtolower($p['nome'], 'UTF-8')] = $p['id'];
}

$matriculasExistentes = [];
foreach ($pdo->query("SELECT matricula FROM policiais WHERE matricula IS NOT NULL AND matricula <> ''") as $m) {
	$matriculasExistentes[$m['matricula']] = true;
}

$insereFuncao = $pdo->prepare("INSERT INTO funcoes SET nome = :nome");
$inserePosto = $pdo->prepare("INSERT INTO postos SET nome = :nome");
$inserePolicial = $pdo->prepare("INSERT INTO policiais SET nome_guerra = :nome_guerra, nome_completo = :nome_completo, matricula = :matricula, telefone = :telefone, grupo = :grupo, turno_padrao = :turno_padrao, funcao_id = :funcao_id, posto_id = :posto_id, numeral = :numeral, disponivel = :disponivel, foto = 'sem-foto.jpg'");

$importados = 0;
$erros = [];

foreach ($linhas as $n => $linha) {
	$numeroLinha = $n + 2; // +1 pelo cabeçalho, +1 porque array é 0-based

	$camposVazios = array_filter($linha, fn($v) => trim((string) $v) !== '');
	if (count($camposVazios) === 0) {
		continue; // linha em branco
	}

	$nome_guerra = valor_da_linha($linha, $indices, 'nome_guerra');
	$grupo_txt = valor_da_linha($linha, $indices, 'grupo');
	$turno_txt = valor_da_linha($linha, $indices, 'turno');
	$funcao_nome = valor_da_linha($linha, $indices, 'funcao');
	$posto_nome = valor_da_linha($linha, $indices, 'posto');
	$disponivel_txt = mb_strtolower(valor_da_linha($linha, $indices, 'disponivel'), 'UTF-8');

	if ($nome_guerra === '') {
		$erros[] = "Linha {$numeroLinha}: Nome de Guerra é obrigatório.";
		continue;
	}

	// vazio = policial disponível para qualquer grupo/turno
	$grupo = null;
	if ($grupo_txt !== '') {
		$grupo = ucfirst(mb_strtolower($grupo_txt, 'UTF-8'));
		if (!in_array($grupo, ['Alpha', 'Bravo'], true)) {
			$erros[] = "Linha {$numeroLinha}: Grupo \"{$grupo_txt}\" inválido (use Alpha, Bravo ou deixe em branco).";
			continue;
		}
	}

	$turno = null;
	if ($turno_txt !== '') {
		$turno = mb_strtoupper($turno_txt, 'UTF-8');
		if (!in_array($turno, ['A', 'B'], true)) {
			$erros[] = "Linha {$numeroLinha}: Turno \"{$turno_txt}\" inválido (use A, B ou deixe em branco).";
			continue;
		}
	}

	if ($funcao_nome === '') {
		$erros[] = "Linha {$numeroLinha}: Função é obrigatória.";
		continue;
	}

	$matricula = valor_da_linha($linha, $indices, 'matricula');
	if ($matricula !== '' and isset($matriculasExistentes[$matricula])) {
		$erros[] = "Linha {$numeroLinha}: Matrícula \"{$matricula}\" já está cadastrada.";
		continue;
	}

	$funcao_chave = mb_strtolower($funcao_nome, 'UTF-8');
	if (!isset($funcoes[$funcao_chave])) {
		$insereFuncao->execute([':nome' => $funcao_nome]);
		$funcoes[$funcao_chave] = $pdo->lastInsertId();
	}
	$funcao_id = $funcoes[$funcao_chave];

	$posto_id = null;
	if ($posto_nome !== '') {
		$posto_chave = mb_strtolower($posto_nome, 'UTF-8');
		if (!isset($postos[$posto_chave])) {
			$inserePosto->execute([':nome' => $posto_nome]);
			$postos[$posto_chave] = $pdo->lastInsertId();
		}
		$posto_id = $postos[$posto_chave];
	}

	$disponivel = in_array($disponivel_txt, ['nao', 'não', '0', 'false'], true) ? 0 : 1;

	$inserePolicial->bindValue(':nome_guerra', $nome_guerra);
	$inserePolicial->bindValue(':nome_completo', valor_da_linha($linha, $indices, 'nome_completo'));
	$inserePolicial->bindValue(':matricula', $matricula);
	$inserePolicial->bindValue(':telefone', valor_da_linha($linha, $indices, 'telefone'));
	$inserePolicial->bindValue(':grupo', $grupo, $grupo === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
	$inserePolicial->bindValue(':turno_padrao', $turno, $turno === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
	$inserePolicial->bindValue(':funcao_id', $funcao_id);
	$inserePolicial->bindValue(':posto_id', $posto_id, $posto_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
	$inserePolicial->bindValue(':numeral', valor_da_linha($linha, $indices, 'numeral'));
	$inserePolicial->bindValue(':disponivel', $disponivel);
	$inserePolicial->execute();

	if ($matricula !== '') {
		$matriculasExistentes[$matricula] = true;
	}

	$importados++;
}

echo json_encode(['importados' => $importados, 'erros' => $erros]);
