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

$query = $pdo->prepare("SELECT * from escala_equipes where escala_id = :id order by turno asc, id asc");
$query->bindValue(":id", $id);
$query->execute();
$equipes = $query->fetchAll(PDO::FETCH_ASSOC);

$html_equipes = '';
$turno_atual = null;
foreach ($equipes as $equipe) {
	if ($equipe['turno'] !== $turno_atual) {
		$turno_atual = $equipe['turno'];
		$html_equipes .= '<h4 style="margin-top:20px; margin-bottom:0;">TURNO ' . htmlspecialchars($turno_atual) . '</h4>';
	}

	$query2 = $pdo->prepare("SELECT p.nome_guerra, p.matricula, f.nome funcao_nome
		FROM escala_membros em
		INNER JOIN policiais p ON p.id = em.policial_id
		INNER JOIN funcoes f ON f.id = em.funcao_na_escala_id
		WHERE em.equipe_id = :equipe_id ORDER BY f.id ASC");
	$query2->bindValue(":equipe_id", $equipe['id']);
	$query2->execute();
	$membros = $query2->fetchAll(PDO::FETCH_ASSOC);

	$titulo_equipe = htmlspecialchars($equipe['nome_equipe']);
	if (!empty($equipe['viatura'])) {
		$titulo_equipe .= ' - Viatura ' . htmlspecialchars($equipe['viatura']);
	}

	$info_equipe = [];
	if (!empty($equipe['horario_inicio']) && !empty($equipe['horario_fim'])) {
		$info_equipe[] = 'Horário: ' . substr($equipe['horario_inicio'], 0, 5) . ' às ' . substr($equipe['horario_fim'], 0, 5);
	}
	if (!empty($equipe['area_atuacao'])) {
		$info_equipe[] = 'Área de Atuação: ' . htmlspecialchars($equipe['area_atuacao']);
	}
	if (count($info_equipe) > 0) {
		$titulo_equipe .= '<br><span style="font-weight:normal; font-size:11px;">' . implode(' &nbsp;|&nbsp; ', $info_equipe) . '</span>';
	}

	$linhas_membros = '';
	foreach ($membros as $m) {
		$linhas_membros .= '<tr>
			<td>' . htmlspecialchars($m['nome_guerra']) . '</td>
			<td>' . htmlspecialchars($m['matricula'] ?? '') . '</td>
			<td>' . htmlspecialchars($m['funcao_nome']) . '</td>
		</tr>';
	}

	$html_equipes .= '
	<table width="100%" style="margin-top:15px; border-collapse:collapse;" border="1" cellpadding="5">
		<tr style="background:#dfe6f1;">
			<td colspan="3"><b>' . $titulo_equipe . '</b></td>
		</tr>
		<tr style="background:#f2f2f2;">
			<td width="45%"><b>Nome de Guerra</b></td>
			<td width="25%"><b>Matrícula</b></td>
			<td width="30%"><b>Função</b></td>
		</tr>
		' . $linhas_membros . '
	</table>';
}

function assinaturaTexto($assinado, $nome, $data) {
	if ($assinado) {
		$dataFmt = date('d/m/Y H:i', strtotime($data));
		return '<p style="font-size:10px; color:#333;">DOCUMENTO ASSINADO ELETRONICAMENTE VIA SISTEMA DE ESCALA INTERNA EM ' . $dataFmt . '<br><b>' . htmlspecialchars($nome ?? '') . '</b></p>';
	}
	return '<p>___________________________________</p>';
}

$assinatura_escalante = assinaturaTexto($escala['assinado_escalante'], $escala['nome_escalante'], $escala['data_assinatura_escalante']);
$assinatura_comandante = assinaturaTexto($escala['assinado_comandante'], $escala['nome_comandante'], $escala['data_assinatura_comandante']);

$html_conteudo = '
<h3 style="text-align: center; margin-bottom: 5px;">POLÍCIA MILITAR DO CEARÁ</h3>
<h4 style="text-align: center; margin-top: 0;">' . htmlspecialchars($nome_sistema) . '</h4>
<p style="text-align: center;"><b>ESCALA DE SERVIÇO OPERACIONAL - GRUPO ' . strtoupper($escala['grupo'] ?? '') . ' - ' . $dataF . '</b></p>

' . $html_equipes . '

<table width="100%" style="margin-top: 60px; text-align: center;">
	<tr>
		<td width="50%">
			' . $assinatura_escalante . '
			<b>ESCALANTE</b><br>
			1º Pel / 1ª Cia / 1º BPRAIO
		</td>
		<td width="50%">
			' . $assinatura_comandante . '
			<b>COMANDANTE DA 1ª CIA</b><br>
			1º BPRAIO
		</td>
	</tr>
</table>
';

$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html_conteudo);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Escala_" . $escala['data_escala'] . "_" . $escala['grupo'] . ".pdf", array("Attachment" => false));
