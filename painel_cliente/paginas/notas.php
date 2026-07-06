<?php
$pag = 'notas';

//verificar se ele tem a permissão de estar nessa página
if (@$notas == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}
?>


<div class="justify-content-between">
	<div class="left-content mt-2 mb-3">
		<form action="rel/lista_orcamentos_class.php" target="_blank" method="POST">
			<div class="left-content mt-2 mb-3">

				<a style="margin-bottom: 20px; margin-top: 20px" class="btn ripple btn-success text-white" onclick="inserir()" type="button"><i class="fa fa-file-circle-plus me-1"></i>Adicionar <?php echo ucfirst($pag); ?></a>

				<div style="display: inline-block; margin-bottom: 10px; margin: 10px 10px;">
					<input type="date" name="dataInicial" class="form-control2" id="dataInicial" style="height:35px; width:49%; font-size: 13px;" value="<?php echo date('Y-m-01') ?>" onchange="buscar()">
					<input type="date" name="dataFinal" class="form-control2" id="dataFinal" style="height:35px; width:49%; font-size: 13px;" value="<?php echo date('Y-m-t') ?>" onchange="buscar()">
				</div>

				<div style="display: inline-block; padding-left: 5px;" title="Filtrar por Status">
					<select class="form-select" aria-label="Default select example" name="status" id="status" onchange="buscar()">
						<option value="">Todos Status</option>
						<option value="Aprovada">Aprovadas</option>
						<option value="Faturada">Faturadas</option>
						<option value="Pendente">Pendentes</option>
					</select>
				</div>

			</div>
		</form>
	</div>
</div>



<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card custom-card">
			<div class="card-body" id="listar">

			</div>
		</div>
	</div>
</div>



<!-- Modal Notas-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="titulo_inserir"></h4>
				<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="post" id="form-notas">
				<div class="modal-body">

					<div class="row">

						<div class="col-md-3 col-6">
							<div class="form-group">
								<label>Data da Nota</label>
								<input type="date" class="form-control" name="data_entrega" id="data_entrega" required>
							</div>
						</div>

						<div class="col-md-3 col-6">
							<div class="form-group">
								<label>Valor da Nota</label>
								<input type="text" pattern="(?:\.|,|[0-9])*" title="Permitido apenas valores sem ponto (.) caso o valor tenha centavos acrescentar vírgula (,) com no maximo duas casas decimais." class="form-control" name="valor" id="valor" placeholder="0,00" required>
							</div>
						</div>

						<div class="col-md-6 col-12">
							<div class="form-group" style="margin-top: -2px;">
								<label>Loja</label>
								<select class="form-select sel2" name="fornecedor" id="fornecedor" style="width:100%;" required>

									<option value="">Selecione uma Loja</option>

									<?php
									$query = $pdo->query("SELECT * FROM fornecedores order by nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}

									?>
										<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?> </option>

									<?php } ?>

								</select>
							</div>
						</div>

					</div>


					<div class="row">

						<div class="col-md-12 mb-12">
							<label>Observações</label>
							<input type="text" class="form-control" id="obs" name="obs" placeholder="Observações">
						</div>

					</div>


					<div class="row" style="margin-top: 30px;">

						<div class="col-md-8 mb-6">
							<div class="form-group">
								<label>Nota</label>
								<input type="file" name="arquivo" id="arquivo" onChange="carregarImg();">
							</div>
						</div>

						<div class="col-md-4 mb-6">
							<div id="divImg">
								<img src="../painel/images/notas/sem-foto.png" width="100px" id="target">
							</div>
						</div>

					</div>

					<br>

					<input type="hidden" name="id" id="id">

					<small>
						<div id="mensagem" align="center" class="mt-3"></div>
					</small>

				</div>


				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Salvar<i class="fa-solid fa-check ms-2"></i></button>
				</div>

			</form>
		</div>
	</div>
</div>




<!-- Modal Dados -->
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"><span id="id_dados"></span><span id="titulo_dados"></span></span></h4>
				<button id="btn-fechar-dados" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>


			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="tile">
							<div class="table-responsive">

								<table id="" class="text-left table table-bordered">

									<tr>
										<td width="40%" class="bg-primary text-white">Nº Controle</td>
										<td><span id="numero_nota_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Data da Nota</td>
										<td><span id="data_entrega_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Valor da Nota</td>
										<td><span id="valor_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">loja</td>
										<td><span id="fornecedor_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Atualizada Por</td>
										<td><span id="usu_cad_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Data</td>
										<td><span id="data_cad_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Hora</td>
										<td><span id="hora_cad_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Observações</td>
										<td><span id="obs_dados"></span></td>
									</tr>

								</table>

							</div>
						</div>
					</div>


					<div class="col-md-6">
						<div class="tile">
							<div class="table-responsive">
								<table id="" class="text-left table table-bordered">

									<tr>
										<td align="center"><a id="link_notas" target="_blank"><img src="" id="nota_dados" width="200px"></a></td>
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





<!-- Modal Arquivos -->
<div class="modal fade" id="modalArquivos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="tituloModal">Gestão de Arquivos - <span id="nome-arquivo"> </span></h4>
				<button id="btn-fechar-arquivos" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form-arquivos" method="post">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Arquivo</label>
								<input class="form-control" type="file" name="arquivo_conta" onChange="carregarImgArquivos();" id="arquivo_conta">
							</div>
						</div>
						<div class="col-md-4">
							<div id="divImgArquivos">
								<img src="images/arquivos/sem-foto.png" width="60px" id="target-arquivos">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-8">
							<input type="text" class="form-control" name="nome-arq" id="nome-arq" placeholder="Nome do Arquivo * " required>
						</div>

						<div class="col-md-4">
							<button type="submit" class="btn btn-success">Inserir<i class="fa-solid fa-check ms-2"></i></button>
						</div>
					</div>

					<hr>

					<small>
						<div id="listar-arquivos"></div>
					</small>

					<br>
					<small>
						<div align="center" id="mensagem-arquivo"></div>
					</small>

					<input type="hidden" class="form-control" name="id-arquivo" id="id-arquivo">


				</div>
			</form>
		</div>
	</div>
</div>



<form id="form_rel" method="POST" action="rel/orcamento_class.php" target="_blank" style="display:none">
	<input type="text" name="id" id="id_orca">
	<input type="text" name="enviar" id="enviar_rel" value="Sim">
	<button type="submit" id="botao_disparar_rel">Enviar</button>
</form>



<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>


<script src="js/ajax.js"></script>

<script src="js/mascaras.js"></script>


<script type="text/javascript">
	$(document).ready(function() {

		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});

		$('.sel3').select2();

		$(document).on('select2:open', () => {
			document.querySelector('.select2-search__field').focus();
		});


		setTimeout(() => {

			listar();

		}, 1000);

	});


	function listarBusca(dataInicial, dataFinal, status, alterou_data) {
		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				dataInicial,
				dataFinal,
				status,
				alterou_data
			},
			dataType: "html",

			success: function(result) {
				$("#listar").html(result);
			}
		});
	}


	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#arquivo").files[0];

		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target').attr('src', "../painel/images/pdf.png");
			return;
		}

		if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target').attr('src', "../painel/images/rar.png");
			return;
		}

		if (resultado[1] === 'doc' || resultado[1] === 'docx') {
			$('#target').attr('src', "../painel/images/word.png");
			return;
		}

		if (resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls') {
			$('#target').attr('src', "../painel/images/excel.png");
			return;
		}

		if (resultado[1] === 'xml') {
			$('#target').attr('src', "../painel/images/xml.png");
			return;
		}

		if (resultado[1] === 'txt') {
			$('#target').attr('src', "../painel/images/txt.png");
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


	function carregarImgArquivos() {
		var target = document.getElementById('target-arquivos');
		var file = document.querySelector("#arquivo_conta").files[0];

		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target-arquivos').attr('src', "images/pdf.png");
			return;
		}

		if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target-arquivos').attr('src', "images/rar.png");
			return;
		}

		if (resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt') {
			$('#target-arquivos').attr('src', "images/word.png");
			return;
		}


		if (resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls') {
			$('#target-arquivos').attr('src', "images/excel.png");
			return;
		}

		if (resultado[1] === 'xml') {
			$('#target-arquivos').attr('src', "images/xml.png");
			return;
		}

		if (resultado[1] === 'txt') {
			$('#target-arquivos').attr('src', "images/txt.png");
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


	$("#form-notas").submit(function() {

		$('#mensagem').text('Salvando...');
		$('#btn_salvar').hide();

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/salvar.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {

					$('#btn-fechar').click();
					sucesso();
					listar();

					$('#mensagem').text('')

				} else {

					$('#mensagem').addClass('text-danger')
					$('#mensagem').text(mensagem)
				}

				$('#btn_salvar').show();

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});


	$("#form-arquivos").submit(function() {
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/arquivos.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem-arquivo').text('');
				$('#mensagem-arquivo').removeClass()
				if (mensagem.trim() == "Inserido com Sucesso") {

					//$('#btn-fechar-arquivos').click();
					$('#nome-arq').val('');
					$('#arquivo_conta').val('');
					$('#target-arquivos').attr('src', 'images/arquivos/sem-foto.png');
					listarArquivos();

				} else {
					$('#mensagem-arquivo').addClass('text-danger')
					$('#mensagem-arquivo').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});


	function listarArquivos() {
		var id = $('#id-arquivo').val();
		$.ajax({
			url: 'paginas/' + pag + "/listar-arquivos.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(result) {
				$("#listar-arquivos").html(result);
			}
		});
	}


	function buscar() {

		var dataInicial = $('#dataInicial').val();
		var dataFinal = $('#dataFinal').val();
		var status = $('#status').val();

		listar(dataInicial, dataFinal, status);

	}
</script>