<?php
require_once("../../verificar.php");
require_once("../../../conexao.php");

$policial_id = @$_SESSION['id_ref'];

$query = $pdo->prepare("SELECT em.id em_id, em.ciente, em.data_ciencia, ed.data_escala, ee.turno, ee.nome_equipe, f.nome funcao_nome
	FROM escala_membros em
	INNER JOIN escala_equipes ee ON ee.id = em.equipe_id
	INNER JOIN escalas_diarias ed ON ed.id = ee.escala_id
	INNER JOIN funcoes f ON f.id = em.funcao_na_escala_id
	WHERE em.policial_id = :policial_id AND ed.status = 'Publicada'
	ORDER BY ed.data_escala DESC, ee.turno ASC");
$query->bindValue(":policial_id", $policial_id);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if ($linhas > 0) {
	echo <<<HTML
<table class="table table-hover table-bordered text-nowrap" id="tabela">
<thead>
<tr>
<th>Data</th>
<th>Turno</th>
<th>Equipe</th>
<th>Função</th>
<th>Ciência</th>
</tr>
</thead>
<tbody>
HTML;

	foreach ($res as $e) {
		$dataF = implode('/', array_reverse(explode('-', $e['data_escala'])));

		if ($e['ciente']) {
			$ciencia_html = '<span class="badge bg-success">Confirmada em ' . date('d/m/Y H:i', strtotime($e['data_ciencia'])) . '</span>';
		} else {
			$ciencia_html = '<button type="button" class="btn btn-sm btn-warning" onclick="confirmarCiencia(' . $e['em_id'] . ')">Confirmar Ciência</button>';
		}

		echo "<tr>
			<td>$dataF</td>
			<td>Turno {$e['turno']}</td>
			<td>{$e['nome_equipe']}</td>
			<td>{$e['funcao_nome']}</td>
			<td>$ciencia_html</td>
		</tr>";
	}

	echo '</tbody></table>';
} else {
	echo '<p class="text-muted mb-0">Você ainda não possui nenhuma Escala publicada.</p>';
}
?>

<script type="text/javascript">
	function confirmarCiencia(em_id) {
		if (!confirm('Confirmar ciência dessa escala?')) return;

		$.ajax({
			url: 'paginas/minha-escala/confirmar-ciencia.php',
			method: 'POST',
			data: {
				em_id
			},
			dataType: 'json',
			success: function(res) {
				alert(res.message);
				if (res.status == 'success') listar();
			}
		});
	}
</script>
