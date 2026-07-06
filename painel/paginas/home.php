<?php
$pag = 'home';

//verificar se ele tem a permissão de estar nessa página
if (@$home == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}

$query = $pdo->query("SELECT COUNT(*) total FROM policiais");
$total_policiais = $query->fetch(PDO::FETCH_ASSOC)['total'];

$query = $pdo->query("SELECT COUNT(*) total FROM policiais WHERE disponivel = 1");
$total_disponiveis = $query->fetch(PDO::FETCH_ASSOC)['total'];

$query = $pdo->query("SELECT COUNT(*) total FROM policiais WHERE disponivel = 0");
$total_indisponiveis = $query->fetch(PDO::FETCH_ASSOC)['total'];

$query = $pdo->query("SELECT COUNT(*) total FROM escalas_diarias WHERE MONTH(data_escala) = MONTH(CURDATE()) AND YEAR(data_escala) = YEAR(CURDATE())");
$total_escalas_mes = $query->fetch(PDO::FETCH_ASSOC)['total'];

$query = $pdo->query("SELECT COUNT(*) total FROM escalas_diarias WHERE status = 'Publicada' AND (assinado_escalante = 0 OR assinado_comandante = 0)");
$total_pendentes_assinatura = $query->fetch(PDO::FETCH_ASSOC)['total'];

$query = $pdo->query("SELECT ed.*, (SELECT COUNT(*) FROM escala_membros em INNER JOIN escala_equipes ee ON ee.id = em.equipe_id WHERE ee.escala_id = ed.id) total_membros FROM escalas_diarias ed WHERE ed.data_escala >= CURDATE() ORDER BY ed.data_escala ASC, ed.turno ASC LIMIT 5");
$proximas_escalas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mt-4 justify-content-between">

	<div class="row">

		<div class="col-xl-3 col-lg-6 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1); height:130px; border-radius: 10px;">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<h6 class="mb-2 tx-12">Efetivo Cadastrado</h6>
							<h4 class="tx-20 font-weight-semibold mb-2"><?php echo $total_policiais ?></h4>
							<p class="mb-0 tx-12 text-muted">Policiais no sistema</p>
						</div>
					</div>
					<div class="col-4">
						<a href="policiais">
							<div class="hov circle-icon bg-secondary-transparent text-center align-self-center overflow-hidden">
								<i class="fa fa-users tx-16 text-secondary"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-lg-6 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1); height:130px; border-radius: 10px">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<h6 class="mb-2 tx-12">Efetivo Disponível</h6>
							<h4 class="tx-20 font-weight-semibold mb-2 text-success"><?php echo $total_disponiveis ?></h4>
							<p class="mb-0 tx-12 text-muted"><?php echo $total_indisponiveis ?> indisponível(is)</p>
						</div>
					</div>
					<div class="col-4">
						<a href="policiais">
							<div class="hov circle-icon bg-success-transparent text-center align-self-center overflow-hidden">
								<i class="fa fa-user-check tx-16 text-success"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-lg-6 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1); height:130px; border-radius: 10px">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<h6 class="mb-2 tx-12">Escalas no Mês</h6>
							<h4 class="tx-20 font-weight-semibold mb-2"><?php echo $total_escalas_mes ?></h4>
							<p class="mb-0 tx-12 text-muted">Rascunho + Publicadas</p>
						</div>
					</div>
					<div class="col-4">
						<a href="escalas_listagem">
							<div class="hov circle-icon bg-secondary-transparent text-center align-self-center overflow-hidden">
								<i class="fa fa-calendar-days tx-16 text-secondary"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-lg-6 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1); height:130px; border-radius: 10px">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<h6 class="mb-2 tx-12">Pendentes de Assinatura</h6>
							<h4 class="tx-20 font-weight-semibold mb-2 <?php echo $total_pendentes_assinatura > 0 ? 'text-danger' : 'text-success' ?>"><?php echo $total_pendentes_assinatura ?></h4>
							<p class="mb-0 tx-12 text-muted">Escalante e/ou Comandante</p>
						</div>
					</div>
					<div class="col-4">
						<a href="escalas_listagem">
							<div class="hov circle-icon bg-warning-transparent text-center align-self-center overflow-hidden">
								<i class="fa fa-signature tx-16 text-warning"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="card custom-card overflow-hidden" style="border-radius: 10px;">
		<div class="card-header border-bottom-0">
			<h3 class="card-title mb-2">Próximas Escalas</h3>
		</div>
		<div class="card-body">
			<?php if (@count($proximas_escalas) > 0) { ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Data</th>
							<th>Turno</th>
							<th>Equipes</th>
							<th>Status</th>
							<th>Escalante</th>
							<th>Comandante</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($proximas_escalas as $e) {
							$dataF = implode('/', array_reverse(explode('-', $e['data_escala'])));
						?>
							<tr>
								<td><?php echo $dataF ?></td>
								<td>Turno <?php echo $e['turno'] ?></td>
								<td><?php echo $e['total_membros'] ?> membro(s)</td>
								<td><span class="badge <?php echo $e['status'] == 'Publicada' ? 'bg-success' : 'bg-secondary' ?>"><?php echo $e['status'] ?></span></td>
								<td><?php echo $e['assinado_escalante'] ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-clock text-warning"></i>' ?></td>
								<td><?php echo $e['assinado_comandante'] ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-clock text-warning"></i>' ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<p class="text-muted mb-0">Nenhuma escala futura cadastrada.</p>
			<?php } ?>
		</div>
	</div>

</div>
