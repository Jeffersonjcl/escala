<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once("../../conexao.php");
require_once '../../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'];

$query = $pdo->prepare("SELECT ed.*, ue.nome nome_escalante, uc.nome nome_comandante,
		pe.nome_completo nome_completo_escalante_esperado, pe.matricula matricula_escalante_esperado, poe.nome posto_escalante_esperado,
		pc.nome_completo nome_completo_comandante_esperado, pc.matricula matricula_comandante_esperado, poc.nome posto_comandante_esperado
	FROM escalas_diarias ed
	LEFT JOIN usuarios ue ON ue.id = ed.escalante_id
	LEFT JOIN usuarios uc ON uc.id = ed.comandante_id
	LEFT JOIN policiais pe ON pe.id = ed.escalante_policial_id
	LEFT JOIN postos poe ON poe.id = pe.posto_id
	LEFT JOIN policiais pc ON pc.id = ed.comandante_policial_id
	LEFT JOIN postos poc ON poc.id = pc.posto_id
	WHERE ed.id = :id");
$query->bindValue(":id", $id);
$query->execute();
$escala = $query->fetch(PDO::FETCH_ASSOC);

if (!$escala) {
	exit('Escala não encontrada!');
}

$dataF = implode('/', array_reverse(explode('-', $escala['data_escala'])));

$dias_semana = ['SEGUNDA-FEIRA', 'TERÇA-FEIRA', 'QUARTA-FEIRA', 'QUINTA-FEIRA', 'SEXTA-FEIRA', 'SÁBADO', 'DOMINGO'];
$dia_semana = $dias_semana[date('N', strtotime($escala['data_escala'])) - 1];

$labels_grupo = ['Adm' => 'Administrativo', 'Alpha' => 'Alpha', 'Bravo' => 'Bravo', 'Guarda01' => 'Guarda 01', 'Guarda02' => 'Guarda 02', 'Guarda03' => 'Guarda 03', 'Guarda04' => 'Guarda 04'];

//título mostra só o Grupo operacional (Alpha/Bravo); Guarda/Administrativo aparecem em seções próprias
$grupo_partes_titulo = explode(',', $escala['grupo'] ?? '');
$grupo_principal = array_values(array_intersect($grupo_partes_titulo, ['Alpha', 'Bravo']))[0] ?? ($grupo_partes_titulo[0] ?? '');

//abreviações usadas nas colunas Função e Posto (padrão do documento oficial)
$abrev_funcao = [
	'comandante' => 'CMT',
	'comandante de equipe' => 'CMT',
	'comandante da guarda' => 'CMT',
	'sentinela 01' => 'SENT 1',
	'sentinela 02' => 'SENT 2',
	'sub comandante' => 'SUB',
	'subcomandante' => 'SUB',
	'motorista' => 'MOT',
	'piloto' => 'MOT',
	'garupa' => 'GAR',
	'atirador' => 'GAR',
	'1º patrulheiro' => 'PAT',
	'2º patrulheiro' => 'PAT',
	'patrulheiro' => 'PAT',
	'2º homem' => '2ºH',
	'3º homem' => '3ºH',
	'5º homem' => '5ºH',
	'permanente' => 'PERM',
	'sai' => 'SAI',
	'auxiliar do p1' => 'AUX P1',
	'auxiliar do p4' => 'AUX P4',
	'escalante' => 'ESC',
	'reserva de armamento' => 'RES',
	'rancheiro' => 'RCH'
];

$abrev_posto = [
	'soldado pm' => 'SD',
	'cabo pm' => 'CB',
	'3º sargento pm' => '3SGT',
	'2º sargento pm' => '2SGT',
	'1º sargento pm' => '1SGT',
	'subtenente pm' => 'ST',
	'2º tenente qopm' => '2TEN',
	'2º tenente qoapm' => '2TEN',
	'1º tenente qopm' => '1TEN',
	'1º tenente qoapm' => '1TEN',
	'capitão qopm' => 'CAP',
	'capitão qoapm' => 'CAP',
	'major qopm' => 'MAJ',
	'major qoapm' => 'MAJ',
	'tenente coronel qopm' => 'TC',
	'coronel qopm' => 'CEL'
];

//abreviação de posto usada na linha de assinatura (mais espaçada que a da caixa de equipe)
$abrev_posto_assinatura = [
	'soldado pm' => 'SD',
	'cabo pm' => 'CB',
	'3º sargento pm' => '3º SGT',
	'2º sargento pm' => '2º SGT',
	'1º sargento pm' => '1º SGT',
	'subtenente pm' => 'SUBTEN',
	'2º tenente qopm' => '2º TEN',
	'2º tenente qoapm' => '2º TEN',
	'1º tenente qopm' => '1º TEN',
	'1º tenente qoapm' => '1º TEN',
	'capitão qopm' => 'CAP',
	'capitão qoapm' => 'CAP',
	'major qopm' => 'MAJ',
	'major qoapm' => 'MAJ',
	'tenente coronel qopm' => 'TEN CEL',
	'coronel qopm' => 'CEL'
];

//reduz a fonte do nome quando ele for muito longo para caber em uma linha na caixa da equipe
function tamanhoFonteNome($nome) {
	$tam = mb_strlen($nome, 'UTF-8');
	if ($tam > 34) {
		return '5px';
	}
	if ($tam > 28) {
		return '5.6px';
	}
	if ($tam > 22) {
		return '6.3px';
	}
	return '7px';
}

function abreviar($nome, $mapa) {
	$chave = mb_strtolower(trim($nome ?? ''), 'UTF-8');
	if ($chave === '') {
		return '';
	}
	if (isset($mapa[$chave])) {
		return $mapa[$chave];
	}
	return mb_strtoupper(mb_substr($nome, 0, 4, 'UTF-8'), 'UTF-8');
}

$query = $pdo->prepare("SELECT * from escala_equipes where escala_id = :id order by turno asc, id asc");
$query->bindValue(":id", $id);
$query->execute();
$equipes = $query->fetchAll(PDO::FETCH_ASSOC);

$query_membros = $pdo->prepare("SELECT p.nome_guerra, p.matricula, p.telefone, p.drso, p.numeral,
		f.nome funcao_nome, po.nome posto_nome
	FROM escala_membros em
	INNER JOIN policiais p ON p.id = em.policial_id
	INNER JOIN funcoes f ON f.id = em.funcao_na_escala_id
	LEFT JOIN postos po ON po.id = p.posto_id
	WHERE em.equipe_id = :equipe_id ORDER BY CASE LOWER(f.nome)
			WHEN 'comandante' THEN 1
			WHEN 'comandante de equipe' THEN 1
			WHEN 'comandante da guarda' THEN 1
			WHEN 'sentinela 01' THEN 2
			WHEN 'sentinela 02' THEN 3
			WHEN '2º homem' THEN 2
			WHEN '3º homem' THEN 3
			WHEN 'garupa' THEN 4
			WHEN 'atirador' THEN 4
			WHEN '5º homem' THEN 5
			WHEN 'sub comandante' THEN 6
			WHEN 'subcomandante' THEN 6
			WHEN 'motorista' THEN 7
			WHEN 'piloto' THEN 7
			WHEN '1º patrulheiro' THEN 8
			WHEN '2º patrulheiro' THEN 9
			WHEN 'patrulheiro' THEN 9
			WHEN 'permanente' THEN 9
			WHEN 'sai' THEN 10
			WHEN 'auxiliar do p1' THEN 11
			WHEN 'auxiliar do p4' THEN 12
			WHEN 'escalante' THEN 13
			WHEN 'reserva de armamento' THEN 14
			WHEN 'rancheiro' THEN 15
			ELSE 16
		END, p.nome_guerra ASC");

//equipes de Guarda e Administrativo têm escala diferente (24h / dias úteis) e saem
//separadas das equipes normais de Turno A/B, identificadas pelo nome da equipe
function classificarEquipe($equipe) {
	$nome = mb_strtoupper($equipe['nome_equipe'] ?? '', 'UTF-8');
	if (mb_strpos($nome, 'GUARDA') !== false) {
		return 'Guarda';
	}
	if (mb_strpos($nome, 'ADM') !== false) {
		return 'Administrativo';
	}
	return 'Normal';
}

//separa as equipes por turno já carregando os membros de cada uma
$turnos = [];
$equipes_guarda = [];
$equipes_admin = [];
foreach ($equipes as $equipe) {
	$query_membros->bindValue(":equipe_id", $equipe['id']);
	$query_membros->execute();
	$equipe['membros'] = $query_membros->fetchAll(PDO::FETCH_ASSOC);

	$categoria = classificarEquipe($equipe);
	if ($categoria === 'Guarda') {
		$equipes_guarda[] = $equipe;
	} elseif ($categoria === 'Administrativo') {
		$equipes_admin[] = $equipe;
	} else {
		$turnos[$equipe['turno']][] = $equipe;
	}
}

//mesmo padrão de cor usado no select de Escalas: Turno A azul escuro, Turno B vermelho escuro,
//Guarda laranja escuro, Administrativo verde escuro
function corNomeTurno($turno) {
	return $turno === 'B' ? '#8B0000' : '#00008B';
}

function montarCaixaEquipe($equipe, $abrev_funcao, $abrev_posto, $preencher_minimo = true, $cor_fixa = null) {
	$titulo = mb_strtoupper($equipe['nome_equipe'], 'UTF-8');
	$cor_nome = $cor_fixa ?? corNomeTurno($equipe['turno'] ?? '');

	$info = [];
	if (!empty($equipe['viatura'])) {
		$info[] = mb_strtoupper($equipe['viatura'], 'UTF-8');
	}
	if (!empty($equipe['horario_inicio']) && !empty($equipe['horario_fim'])) {
		$info[] = substr($equipe['horario_inicio'], 0, 5) . ' às ' . substr($equipe['horario_fim'], 0, 5);
	}

	$html = '<table class="equipe">';
	$html .= '<tr><td class="titulo" colspan="4">' . htmlspecialchars($titulo) . '</td></tr>';

	if (count($info) > 0) {
		$html .= '<tr><td class="info" colspan="4">' . htmlspecialchars(implode(' - ', $info)) . '</td></tr>';
	}
	if (!empty($equipe['area_atuacao'])) {
		$html .= '<tr><td class="area" colspan="4">' . htmlspecialchars(mb_strtoupper($equipe['area_atuacao'], 'UTF-8')) . '</td></tr>';
	}

	$total_linhas = 0;
	foreach ($equipe['membros'] as $m) {
		$nome = mb_strtoupper($m['nome_guerra'], 'UTF-8');
		if (!empty($m['telefone'])) {
			$nome .= ' ' . $m['telefone'];
		}
		if ($m['drso']) {
			$nome .= ' (DRSO)';
		}

		$html .= '<tr>
			<td class="c-func">' . htmlspecialchars(abreviar($m['funcao_nome'], $abrev_funcao)) . '</td>
			<td class="c-posto">' . htmlspecialchars(abreviar($m['posto_nome'], $abrev_posto)) . '</td>
			<td class="c-mat">' . htmlspecialchars($m['numeral'] ?: '-') . '</td>
			<td class="c-nome" style="font-size:' . tamanhoFonteNome($nome) . '; color:' . $cor_nome . '">' . htmlspecialchars($nome) . '</td>
		</tr>';
		$total_linhas++;
	}

	//mantém todas as caixas com a mesma altura mínima (equipe padrão = 4 policiais);
	//Guarda/Administrativo não seguem esse padrão de efetivo, então não recebem linhas em branco
	if ($preencher_minimo) {
		while ($total_linhas < 4) {
			$html .= '<tr><td class="c-func">&nbsp;</td><td class="c-posto">&nbsp;</td><td class="c-mat">&nbsp;</td><td class="c-nome">&nbsp;</td></tr>';
			$total_linhas++;
		}
	}

	$html .= '</table>';

	return $html;
}

function montarCaixaResumo($titulo, $equipes_turno, $apenas_efetivo = false, $rotulo_efetivo = 'EFETIVO OPERACIONAL') {
	$qtd_equipes = count($equipes_turno);
	$qtd_viaturas = 0;
	$efetivo = 0;
	foreach ($equipes_turno as $e) {
		$efetivo += count($e['membros']);
		if (mb_stripos(trim($e['nome_equipe'] ?? ''), 'VTR', 0, 'UTF-8') === 0) {
			$qtd_viaturas++;
		}
	}

	$linhas = $apenas_efetivo ? [
		$rotulo_efetivo => $efetivo
	] : [
		$rotulo_efetivo => $efetivo,
		'QTD DE EQUIPES' => $qtd_equipes,
		'QTD DE VIATURAS' => $qtd_viaturas
	];

	$html = '<table class="equipe">';
	$html .= '<tr><td class="titulo" colspan="2">RESUMO ' . htmlspecialchars($titulo) . '</td></tr>';
	foreach ($linhas as $rotulo => $valor) {
		$html .= '<tr><td class="r-rotulo">' . $rotulo . ':</td><td class="r-valor">' . $valor . '</td></tr>';
	}
	$html .= '</table>';

	return $html;
}

//distribui as caixas em uma grade de 3 colunas por linha, igual ao documento oficial
function montarGrade($caixas) {
	$html = '<table class="grade">';
	$colunas = 3;
	$total = count($caixas);
	for ($i = 0; $i < $total; $i += $colunas) {
		$html .= '<tr>';
		for ($c = 0; $c < $colunas; $c++) {
			$conteudo = isset($caixas[$i + $c]) ? $caixas[$i + $c] : '';
			$html .= '<td class="celula">' . $conteudo . '</td>';
		}
		$html .= '</tr>';
	}
	$html .= '</table>';

	return $html;
}

//monta o bloco de assinatura no padrão: linha, NOME - POSTO QOPM, CARGO DA 3ª CIA/1º BPRAIO, M.F Nº matrícula
function assinaturaBloco($assinado, $data, $nome_login, $nome_completo, $posto_nome, $matricula, $prefixo_cargo, $abrev_posto_assinatura) {
	$linha = '<div class="linha-assinatura">&nbsp;</div>';

	$nome_exibido = $nome_completo ?: $nome_login;
	if (empty($nome_exibido)) {
		return $linha;
	}

	//QOPM/QOAPM é só para oficiais (Cel, Ten Cel, Major, Capitão, 1º/2º Ten); SubTen, Sgt, Cabo e Soldado são PM
	//o quadro exibido (QOPM ou QOAPM) segue o posto cadastrado do policial, não é fixo
	$posto_chave = mb_strtolower(trim($posto_nome ?? ''), 'UTF-8');
	if (substr($posto_chave, -5) === 'qoapm') {
		$quadro = 'QOAPM';
	} elseif (substr($posto_chave, -4) === 'qopm') {
		$quadro = 'QOPM';
	} else {
		$quadro = 'PM';
	}

	$linha_nome = mb_strtoupper($nome_exibido, 'UTF-8');
	$posto_abrev = $nome_completo ? abreviar($posto_nome, $abrev_posto_assinatura) : '';
	if ($posto_abrev) {
		$linha_nome .= ' - ' . $posto_abrev . ' ' . $quadro;
	}

	$html = $linha . '<div class="assinante">' . htmlspecialchars($linha_nome) . '</div>';
	$html .= '<div class="cargo">' . htmlspecialchars($prefixo_cargo) . ' DA 3ª CIA/1º BPRAIO</div>';
	if (!empty($matricula)) {
		$html .= '<div class="unidade-cargo">M.F Nº ' . htmlspecialchars($matricula) . '</div>';
	}
	if ($assinado) {
		$dataFmt = date('d/m/Y H:i', strtotime($data));
		$html .= '<div class="assinado">DOCUMENTO ASSINADO ELETRONICAMENTE VIA SISTEMA DE ESCALA INTERNA EM ' . $dataFmt . '</div>';
	}
	return $html;
}

$assinatura_escalante = assinaturaBloco(
	$escala['assinado_escalante'], $escala['data_assinatura_escalante'], $escala['nome_escalante'],
	$escala['nome_completo_escalante_esperado'], $escala['posto_escalante_esperado'], $escala['matricula_escalante_esperado'],
	'ESCALANTE', $abrev_posto_assinatura
);
$assinatura_comandante = assinaturaBloco(
	$escala['assinado_comandante'], $escala['data_assinatura_comandante'], $escala['nome_comandante'],
	$escala['nome_completo_comandante_esperado'], $escala['posto_comandante_esperado'], $escala['matricula_comandante_esperado'],
	'CMT', $abrev_posto_assinatura
);

$rodape = '
<table class="assinaturas">
	<tr>
		<td width="50%">
			' . $assinatura_escalante . '
		</td>
		<td width="50%">
			' . $assinatura_comandante . '
		</td>
	</tr>
</table>';

//brasão da esquerda (logo_rel) e da direita (logo_rel2), ambos opcionais
function imagemCabecalho($arquivo) {
	if (empty($arquivo)) {
		return '';
	}
	$caminho = realpath(__DIR__ . '/../../img/' . $arquivo);
	if (!$caminho) {
		return '';
	}
	return '<img src="' . str_replace('\\', '/', $caminho) . '" class="brasao">';
}

$html_logo = imagemCabecalho($logo_rel);
$html_logo2 = imagemCabecalho($logo_rel2);

//monta a seção separada de Guarda/Administrativo (escala diferente: 24h / dias úteis),
//exibida logo após a grade de equipes normais do Turno A
function montarSecaoEspecial($titulo, $equipes, $resumo_apenas_efetivo = false, $rotulo_efetivo = 'EFETIVO OPERACIONAL', $cor_fixa = null) {
	if (empty($equipes)) {
		return '';
	}
	global $abrev_funcao, $abrev_posto;

	$caixas = [];
	foreach ($equipes as $equipe) {
		$caixas[] = montarCaixaEquipe($equipe, $abrev_funcao, $abrev_posto, false, $cor_fixa);
	}
	$caixas[] = montarCaixaResumo($titulo, $equipes, $resumo_apenas_efetivo, $rotulo_efetivo);

	return '<div class="titulo-secao">' . htmlspecialchars($titulo) . '</div>' . montarGrade($caixas);
}

//uma página por turno, na ordem A e depois B; Guarda/Administrativo aparecem só uma vez,
//na página do Turno A, logo após as equipes normais
$paginas = '';
$primeiro = true;
foreach (['A', 'B'] as $turno) {
	$tem_normais = !empty($turnos[$turno]);
	$tem_especiais = ($turno === 'A') && (!empty($equipes_guarda) || !empty($equipes_admin));

	if (!$tem_normais && !$tem_especiais) {
		continue;
	}

	$secao_normal = '';
	if ($tem_normais) {
		$caixas = [];
		foreach ($turnos[$turno] as $equipe) {
			$caixas[] = montarCaixaEquipe($equipe, $abrev_funcao, $abrev_posto);
		}
		$caixas[] = montarCaixaResumo('TURNO ' . $turno, $turnos[$turno]);
		$secao_normal = montarGrade($caixas);
	}

	$secao_especial = '';
	if ($tem_especiais) {
		$secao_especial .= montarSecaoEspecial('GUARDA DO QUARTEL', $equipes_guarda, true, 'EFETIVO DA GUARDA', '#B25900');
		$secao_especial .= montarSecaoEspecial('ADMINISTRATIVO', $equipes_admin, true, 'EFETIVO ADMINISTRATIVO', '#006400');
	}

	$paginas .= '<div class="pagina' . ($primeiro ? '' : ' quebra') . '">
		<table class="cabecalho">
			<tr>
				<td class="col-brasao">' . $html_logo . '</td>
				<td class="col-titulo">
					<div class="orgao">POLÍCIA MILITAR DO CEARÁ</div>
					<div class="unidade">' . htmlspecialchars($nome_sistema) . '</div>
					<div class="documento">ESCALA DE SERVIÇO OPERACIONAL - GRUPO ' . htmlspecialchars(mb_strtoupper($labels_grupo[$grupo_principal] ?? $grupo_principal, 'UTF-8')) . '</div>
				</td>
				<td class="col-brasao">' . $html_logo2 . '</td>
			</tr>
		</table>

		<div class="faixa-data">' . $dia_semana . '  -  ' . $dataF . '  -  TURNO ' . $turno . '</div>

		' . $secao_normal . '

		' . $secao_especial . '

		' . $rodape . '
	</div>';

	$primeiro = false;
}

$html_conteudo = '
<html>
<head>
<meta charset="utf-8">
<style>
	@page { margin: 22px 18px; }
	body { font-family: "DejaVu Sans", sans-serif; font-size: 8px; color: #000; }

	.quebra { page-break-before: always; }

	table.cabecalho { width: 100%; border-collapse: collapse; }
	table.cabecalho td { vertical-align: middle; padding: 0; }
	.col-brasao { width: 85px; text-align: center; }
	.brasao { height: 40px; }
	.col-titulo { text-align: center; }
	.orgao { font-size: 13px; font-weight: bold; }
	.unidade { font-size: 9px; color: #333; }
	.documento { font-size: 9px; font-weight: bold; margin-top: 2px; }

	.faixa-data {
		background: #1f3864; color: #fff; font-weight: bold; font-size: 10px;
		text-align: center; padding: 4px 0; margin: 8px 0 6px 0;
	}

	.titulo-secao {
		background: #555555; color: #fff; font-weight: bold; font-size: 9px;
		text-align: center; padding: 3px 0; margin: 10px 0 4px 0;
	}

	table.grade { width: 100%; border-collapse: separate; border-spacing: 4px; table-layout: fixed; }
	td.celula { width: 33.33%; vertical-align: top; padding: 0; }

	table.equipe { width: 100%; border-collapse: collapse; table-layout: fixed; border: 0.6pt solid #808080; }
	table.equipe td { border: none; padding: 2px 3px; font-size: 7px; }

	table.equipe td.titulo {
		background: #1f3864; color: #fff; font-weight: bold; font-size: 9px;
		text-align: center; padding: 3px 2px;
	}
	table.equipe td.info {
		background: #dce6f1; font-size: 6.5px; text-align: center; font-weight: bold;
	}
	table.equipe td.area {
		background: #eef3fa; font-size: 6px; text-align: center; font-style: italic;
	}

	.c-func  { width: 17%; text-align: center; font-weight: bold; background: #f2f2f2; }
	.c-posto { width: 14%; text-align: center; }
	.c-mat   { width: 20%; text-align: center; }
	.c-nome  { width: 49%; }

	.r-rotulo { font-size: 7px; }
	.r-valor  { width: 30%; text-align: center; font-weight: bold; font-size: 8px; }

	table.assinaturas { width: 100%; margin-top: 26px; text-align: center; border-collapse: collapse; }
	table.assinaturas td { vertical-align: bottom; padding: 0 10px; }
	.linha-assinatura { border-bottom: 0.6pt solid #000; margin: 0 20px 3px 20px; height: 22px; }
	.assinado { font-size: 6px; color: #444; }
	.assinante { font-size: 8px; font-weight: bold; margin: 2px 20px 0 20px; padding-top: 2px; }
	.cargo { font-size: 8px; font-weight: bold; }
	.unidade-cargo { font-size: 8px; font-weight: bold; color: #333; }
</style>
</head>
<body>' . $paginas . '</body>
</html>';

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);
$options->set('chroot', realpath(__DIR__ . '/../../'));
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html_conteudo, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Escala_" . $escala['data_escala'] . "_" . $escala['grupo'] . ".pdf", array("Attachment" => false));
