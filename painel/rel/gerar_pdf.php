<?php
require_once("../../conexao.php");
require_once '../../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'];

$query = $pdo->prepare("SELECT ed.*, ue.nome nome_escalante, uc.nome nome_comandante
	FROM escalas_diarias ed
	LEFT JOIN usuarios ue ON ue.id = ed.escalante_id
	LEFT JOIN usuarios uc ON uc.id = ed.comandante_id
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

//abreviações usadas nas colunas Função e Posto (padrão do documento oficial)
$abrev_funcao = [
	'comandante' => 'CMT',
	'comandante de equipe' => 'CMT',
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
	'2º tenente pm' => '2TEN',
	'1º tenente pm' => '1TEN',
	'capitão pm' => 'CAP',
	'major pm' => 'MAJ',
	'tenente coronel pm' => 'TC',
	'coronel pm' => 'CEL'
];

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
	WHERE em.equipe_id = :equipe_id ORDER BY f.id ASC, p.nome_guerra ASC");

//separa as equipes por turno já carregando os membros de cada uma
$turnos = [];
foreach ($equipes as $equipe) {
	$query_membros->bindValue(":equipe_id", $equipe['id']);
	$query_membros->execute();
	$equipe['membros'] = $query_membros->fetchAll(PDO::FETCH_ASSOC);
	$turnos[$equipe['turno']][] = $equipe;
}

function montarCaixaEquipe($equipe, $abrev_funcao, $abrev_posto) {
	$titulo = mb_strtoupper($equipe['nome_equipe'], 'UTF-8');

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
			<td class="c-mat">' . htmlspecialchars($m['matricula'] ?? '') . '</td>
			<td class="c-nome">' . htmlspecialchars($nome) . '</td>
		</tr>';
		$total_linhas++;
	}

	//mantém todas as caixas com a mesma altura mínima (equipe padrão = 4 policiais)
	while ($total_linhas < 4) {
		$html .= '<tr><td class="c-func">&nbsp;</td><td class="c-posto">&nbsp;</td><td class="c-mat">&nbsp;</td><td class="c-nome">&nbsp;</td></tr>';
		$total_linhas++;
	}

	$html .= '</table>';

	return $html;
}

function montarCaixaResumo($turno, $equipes_turno) {
	$qtd_equipes = count($equipes_turno);
	$qtd_viaturas = 0;
	$efetivo = 0;
	foreach ($equipes_turno as $e) {
		$efetivo += count($e['membros']);
		if (!empty($e['viatura'])) {
			$qtd_viaturas++;
		}
	}

	$linhas = [
		'EFETIVO OPERACIONAL' => $efetivo,
		'QTD DE EQUIPES' => $qtd_equipes,
		'QTD DE VIATURAS' => $qtd_viaturas
	];

	$html = '<table class="equipe">';
	$html .= '<tr><td class="titulo" colspan="2">RESUMO TURNO ' . htmlspecialchars($turno) . '</td></tr>';
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

function assinaturaTexto($assinado, $nome, $data) {
	if ($assinado) {
		$dataFmt = date('d/m/Y H:i', strtotime($data));
		return '<div class="assinado">DOCUMENTO ASSINADO ELETRONICAMENTE VIA SISTEMA DE ESCALA INTERNA EM ' . $dataFmt . '</div>
			<div class="assinante">' . htmlspecialchars($nome ?? '') . '</div>';
	}
	return '<div class="linha-assinatura">&nbsp;</div>';
}

$assinatura_escalante = assinaturaTexto($escala['assinado_escalante'], $escala['nome_escalante'], $escala['data_assinatura_escalante']);
$assinatura_comandante = assinaturaTexto($escala['assinado_comandante'], $escala['nome_comandante'], $escala['data_assinatura_comandante']);

$rodape = '
<table class="assinaturas">
	<tr>
		<td width="50%">
			' . $assinatura_escalante . '
			<div class="cargo">ESCALANTE</div>
			<div class="unidade-cargo">1º Pel / 1ª Cia / 1º BPRAIO</div>
		</td>
		<td width="50%">
			' . $assinatura_comandante . '
			<div class="cargo">COMANDANTE DA 1ª CIA</div>
			<div class="unidade-cargo">1º BPRAIO</div>
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

//uma página por turno, na ordem A e depois B
$paginas = '';
$primeiro = true;
foreach (['A', 'B'] as $turno) {
	if (empty($turnos[$turno])) {
		continue;
	}

	$caixas = [];
	foreach ($turnos[$turno] as $equipe) {
		$caixas[] = montarCaixaEquipe($equipe, $abrev_funcao, $abrev_posto);
	}
	$caixas[] = montarCaixaResumo($turno, $turnos[$turno]);

	$paginas .= '<div class="pagina' . ($primeiro ? '' : ' quebra') . '">
		<table class="cabecalho">
			<tr>
				<td class="col-brasao">' . $html_logo . '</td>
				<td class="col-titulo">
					<div class="orgao">POLÍCIA MILITAR DO CEARÁ</div>
					<div class="unidade">' . htmlspecialchars($nome_sistema) . '</div>
					<div class="documento">ESCALA DE SERVIÇO OPERACIONAL - GRUPO ' . htmlspecialchars(mb_strtoupper($escala['grupo'] ?? '', 'UTF-8')) . '</div>
				</td>
				<td class="col-brasao">' . $html_logo2 . '</td>
			</tr>
		</table>

		<div class="faixa-data">' . $dia_semana . '  -  ' . $dataF . '  -  TURNO ' . $turno . '</div>

		' . montarGrade($caixas) . '

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
	body { font-family: Arial, Helvetica, sans-serif; font-size: 8px; color: #000; }

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

	table.grade { width: 100%; border-collapse: separate; border-spacing: 4px; table-layout: fixed; }
	td.celula { width: 33.33%; vertical-align: top; padding: 0; }

	table.equipe { width: 100%; border-collapse: collapse; table-layout: fixed; }
	table.equipe td { border: 0.6pt solid #808080; padding: 2px 3px; font-size: 7px; }

	table.equipe td.titulo {
		background: #1f3864; color: #fff; font-weight: bold; font-size: 9px;
		text-align: center; padding: 3px 2px; border-color: #1f3864;
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
	.assinante { font-size: 8px; font-weight: bold; border-top: 0.6pt solid #000; margin: 2px 20px 0 20px; padding-top: 2px; }
	.cargo { font-size: 8px; font-weight: bold; }
	.unidade-cargo { font-size: 7px; color: #333; }
</style>
</head>
<body>' . $paginas . '</body>
</html>';

$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isHtml5ParserEnabled', true);
$options->set('chroot', realpath(__DIR__ . '/../../'));
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html_conteudo, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Escala_" . $escala['data_escala'] . "_" . $escala['grupo'] . ".pdf", array("Attachment" => false));
