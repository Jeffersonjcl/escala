<?php
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'minhas_comissoes';

$data_hoje = date('Y-m-d');
$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_hoje)));

$mes_atual = Date('m');
$ano_atual = Date('Y');

$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01";

if ($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11') {

	$dia_final_mes = '30';
} else if ($mes_atual == '2' && $ano_atual % 4 != 0) {

	$dia_final_mes = '28';
} else if ($mes_atual == '2') {

	$dia_final_mes = '29';
} else {

	$dia_final_mes = '31';
}

$data_final_mes = $ano_atual . "-" . $mes_atual . "-" . $dia_final_mes;

//verificar se ele tem a permissão de estar nessa página
if (@$minhas_comissoes == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}
?>
<div class="justify-content-between">
	<div class="left-content mt-4 mb-3">

		<!-- Botão Relatório PDF-->
		<div style="float: right; margin-top: 6px;">
			<div>
				<form action="rel/comissoes_class.php" target="_blank" method="POST">

					<input type="hidden" name="dataInicial" id="dataInicial">
					<input type="hidden" name="dataFinal" id="dataFinal">
					<input type="hidden" name="pago" id="pago_rel">

					<button type="submit" class="btn btn-danger link-botao"><span class=""> Relatório</span><i class="fa fa-file-pdf-o ms-2" class="text-danger"></i></button>

				</form>
			</div>
		</div>


		<!-- Botão Relatório Excel-->
		<div style="float: right; margin-top: 6px; margin-right: 20px;">
			<div>
				<form action="rel/excel_comissao.php" target="_blank" method="POST">

					<input type="hidden" name="dataInicialExcel" id="dataInicialExcel">
					<input type="hidden" name="dataFinalExcel" id="dataFinalExcel">

					<button type="submit" class="btn btn-success link-botao esc"><span class=""> Relatório</span><i class="fa fa-file-excel-o ms-2" class="text-danger"></i></button>

				</form>
			</div>
		</div>


		<div class="row">

			<div style="display: inline-block; margin-bottom: 10px; margin: 10px 10px;">
				<div style="float:left; margin-right:20px">
					<input type="date" class="form-control " name="data-inicial" id="data-inicial-caixa" value="<?php echo $data_hoje ?>" required>
				</div>

				<div style="float:left; margin-right:30px">
					<input type="date" class="form-control " name="data-final" id="data-final-caixa" value="<?php echo $data_hoje ?>" required>
				</div>
			</div>


			<div style="display: inline-block; margin-top: 16px;">
				<div>
					<span class="ocultar_mobile" style="font-size: 14px; border:1px solid #6092a8; padding:5px; ">
						<a title="Conta de Ontem" class="text-muted" href="#" onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')"><span>Ontem</span></a> /
						<a title="Conta de Hoje" class="text-muted" href="#" onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')"><span>Hoje</span></a> /
						<a title="Conta do Mês" class="text-muted" href="#" onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')"><span>Mês</span></a>
					</span>
				</div>
			</div>


			<!-- <div class="ocultar_mobile" style="display: inline-block; margin-top: 16px;">
				<div>
					<span class="ocultar_mobile" style="font-size: 14px; border:1px solid #6092a8; padding:5px; ">
						<a title="Todos os Serviços" class="text-muted" href="#" onclick="buscarContas('')"><span>Todos</span></a> /
						<a title="Pendentes" class="text-muted" href="#" onclick="buscarContas('Não')"><span>Pendentes</span></a> /
						<a title="Pagos" class="text-muted" href="#" onclick="buscarContas('Sim')"><span>Pagos</span></a>
					</span>
				</div>
			</div> -->


			<input type="hidden" id="buscar-contas">


		</div>


		<hr>
		<div id="listar">

		</div>
	</div>
</div>



<!-- Modal Dados-->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_dados"></span></h4>
				<button id="btn-fechar-perfil" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>

			<div class="modal-body">

				<div class="row">

					<div class="col-md-12">
						<div class="tile">
							<div class="table-responsive">
								<table id="" class="text-left table table-bordered">

									<tr>
										<td width="35%" class="bg-primary text-white">Valor</td>
										<td><span id="valor_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Data Lançamento</td>
										<td><span id="data_lanc_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Data Vencimento</td>
										<td><span id="data_venc_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Data PGTO</td>
										<td><span id="data_pgto_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Usuário Lanc</td>
										<td><span id="usuario_lanc_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Usuário Baixa</td>
										<td><span id="usuario_baixa_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Telefone</td>
										<td><span id="telefone_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Chave Pix</td>
										<td><span id="chave_pix_dados"></span></td>
									</tr>

								</table>
							</div>
						</div>
					</div>
					
				</div>

			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<script src="js/ajax.js"></script>



<script type="text/javascript">
	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#foto").files[0];


		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target').attr('src', "img/pdf.png");
			return;
		}

		if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target').attr('src', "img/rar.png");
			return;
		}



		var reader = new FileReader();

		reader.onloadend = function() {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>



<script type="text/javascript">
	function valorData(dataInicio, dataFinal) {
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);
		listar();
	}
</script>



<script type="text/javascript">
	$('#data-inicial-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar();
	});

	$('#data-final-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar();
	});
</script>



<script type="text/javascript">
	function listar() {

		var dataInicial = $('#data-inicial-caixa').val();
		var dataFinal = $('#data-final-caixa').val();

		$('#dataInicial').val(dataInicial);
		$('#dataFinal').val(dataFinal);

		$('#dataInicialExcel').val(dataInicial);
		$('#dataFinalExcel').val(dataFinal);

		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				dataInicial,
				dataFinal,
			},
			dataType: "html",

			success: function(result) {
				$("#listar").html(result);
				$('#mensagem-excluir').text('');
			}
		});
	}
</script>



<script type="text/javascript">
	function buscarContas(status) {
		$('#buscar-contas').val(status);
		listar();
	}
</script>