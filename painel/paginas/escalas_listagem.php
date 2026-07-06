<?php
$pag = 'escalas_listagem';

//verificar se ele tem a permissão de estar nessa página
if (@$escalas_listagem == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}
?>

<div class="breadcrumb-header justify-content-between">
	<div class="left-content mt-2">
		<h4 class="mb-0">Consulta Mensal de Escalas</h4>
	</div>
</div>

<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card custom-card">
			<div class="card-body">

				<div class="row mb-3">
					<div class="col-md-3">
						<label>Mês</label>
						<select class="form-select" id="mes">
							<?php
							$meses = ['01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'];
							foreach ($meses as $num => $nome) {
								$selected = ($num == date('m')) ? 'selected' : '';
								echo "<option value=\"$num\" $selected>$nome</option>";
							}
							?>
						</select>
					</div>
					<div class="col-md-3">
						<label>Ano</label>
						<select class="form-select" id="ano">
							<?php
							$ano_atual_filtro = date('Y');
							for ($a = $ano_atual_filtro - 1; $a <= $ano_atual_filtro + 1; $a++) {
								$selected = ($a == $ano_atual_filtro) ? 'selected' : '';
								echo "<option value=\"$a\" $selected>$a</option>";
							}
							?>
						</select>
					</div>
					<div class="col-md-3 d-flex align-items-end">
						<button type="button" class="btn btn-primary" onclick="filtrar()"><i class="fa fa-filter me-1"></i> Filtrar</button>
					</div>
					<div class="col-md-3 d-flex align-items-end justify-content-end">
						<a href="escalas" class="btn btn-success"><i class="fa fa-plus me-1"></i> Criar Escala</a>
					</div>
				</div>

				<div id="listar"></div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	function filtrar() {
		listar($('#mes').val(), $('#ano').val());
	}
</script>
