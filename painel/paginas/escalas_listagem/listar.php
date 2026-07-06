<?php
require_once(__DIR__ . '/../_guard.php');
require_once("../../../conexao.php");
@session_start();

$mes = @$_POST['p1'] ?: date('m');
$ano = @$_POST['p2'] ?: date('Y');

$query = $pdo->prepare("SELECT ed.*,
	(SELECT COUNT(*) FROM escala_membros em INNER JOIN escala_equipes ee ON ee.id = em.equipe_id WHERE ee.escala_id = ed.id) total_membros,
	(SELECT COUNT(*) FROM escala_equipes ee WHERE ee.escala_id = ed.id) total_equipes,
	ue.nome nome_escalante, uc.nome nome_comandante
	FROM escalas_diarias ed
	LEFT JOIN usuarios ue ON ue.id = ed.escalante_id
	LEFT JOIN usuarios uc ON uc.id = ed.comandante_id
	WHERE MONTH(ed.data_escala) = :mes AND YEAR(ed.data_escala) = :ano
	ORDER BY ed.data_escala DESC, ed.turno ASC");
$query->bindValue(":mes", $mes);
$query->bindValue(":ano", $ano);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if ($linhas > 0) {
	echo <<<HTML
<small>
	<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead>
	<tr>
	<th>Data</th>
	<th>Turno</th>
	<th>Equipes</th>
	<th>Status</th>
	<th>Escalante</th>
	<th>Comandante</th>
	<th>Ações</th>
	</tr>
	</thead>
	<tbody>
HTML;
} else {
	echo 'Nenhuma Escala encontrada nesse período!';
}

foreach ($res as $e) {
	$id = $e['id'];
	$dataF = implode('/', array_reverse(explode('-', $e['data_escala'])));
	$turno = $e['turno'];
	$status = $e['status'];
	$total_equipes = $e['total_equipes'];
	$total_membros = $e['total_membros'];

	$badge_status = $status == 'Publicada' ? 'bg-success' : 'bg-secondary';

	if ($e['assinado_escalante']) {
		$escalante_html = '<i class="fa fa-check-circle text-success"></i> ' . htmlspecialchars($e['nome_escalante'] ?? '');
	} else {
		$escalante_html = '<i class="fa fa-clock text-warning"></i> Pendente';
	}

	if ($e['assinado_comandante']) {
		$comandante_html = '<i class="fa fa-check-circle text-success"></i> ' . htmlspecialchars($e['nome_comandante'] ?? '');
	} else {
		$comandante_html = '<i class="fa fa-clock text-warning"></i> Pendente';
	}

	$acoes = '';

	if ($status == 'Rascunho') {
		$acoes .= '<a class="btn btn-info btn-sm" href="index.php?pagina=escalas&id=' . $id . '" title="Editar"><i class="fa fa-edit"></i></a> ';
		$acoes .= '<a class="btn btn-success btn-sm" href="#" onclick="publicarEscala(' . $id . ')" title="Publicar"><i class="fa fa-check"></i></a> ';
		$acoes .= '<a class="btn btn-danger btn-sm" href="#" onclick="excluirEscala(' . $id . ')" title="Excluir"><i class="fa fa-trash-can"></i></a> ';
	}

	$acoes .= '<a class="btn btn-secondary btn-sm" href="rel/gerar_pdf.php?id=' . $id . '" target="_blank" title="Imprimir"><i class="fa fa-print"></i></a> ';

	if ($status == 'Publicada' and @$assinar_escalante != 'ocultar' and !$e['assinado_escalante']) {
		$acoes .= '<a class="btn btn-warning btn-sm" href="#" onclick="assinarEscala(' . $id . ', \'escalante\')" title="Assinar como Escalante"><i class="fa fa-signature"></i> P4</a> ';
	}

	if ($status == 'Publicada' and @$assinar_comandante != 'ocultar' and !$e['assinado_comandante']) {
		$acoes .= '<a class="btn btn-warning btn-sm" href="#" onclick="assinarEscala(' . $id . ', \'comandante\')" title="Assinar como Comandante"><i class="fa fa-signature"></i> Cmt</a> ';
	}

	echo "<tr>
		<td>$dataF</td>
		<td>Turno $turno</td>
		<td>$total_equipes equipe(s) / $total_membros membro(s)</td>
		<td><span class='badge $badge_status'>$status</span></td>
		<td>$escalante_html</td>
		<td>$comandante_html</td>
		<td>$acoes</td>
	</tr>";
}

if ($linhas > 0) {
	echo '</tbody></table>';
}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"language": {},
			"ordering": false,
			"stateSave": false
		});
	});
</script>

<script type="text/javascript">
	function publicarEscala(id) {
		if (!confirm('Publicar essa escala? Após publicada ela não poderá mais ser editada.')) return;

		$.ajax({
			url: 'paginas/escalas/publicar.php',
			method: 'POST',
			data: {
				id
			},
			dataType: 'json',
			success: function(res) {
				alert(res.message);
				if (res.status == 'success') filtrar();
			}
		});
	}

	function excluirEscala(id) {
		if (!confirm('Excluir essa escala e todas as suas equipes/membros?')) return;

		$.ajax({
			url: 'paginas/escalas/excluir.php',
			method: 'POST',
			data: {
				id
			},
			dataType: 'json',
			success: function(res) {
				alert(res.message);
				if (res.status == 'success') filtrar();
			}
		});
	}

	function assinarEscala(id, papel) {
		if (!confirm('Confirmar assinatura eletrônica como ' + papel + '?')) return;

		$.ajax({
			url: 'paginas/escalas/assinar.php',
			method: 'POST',
			data: {
				id,
				papel
			},
			dataType: 'json',
			success: function(res) {
				alert(res.message);
				if (res.status == 'success') filtrar();
			}
		});
	}
</script>
