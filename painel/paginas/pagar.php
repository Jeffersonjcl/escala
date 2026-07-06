<?php
$pag = 'pagar';

//verifica se ele tem a permissão de estar nessa página
if (@$pagar == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}


?>

<div class="justify-content-between">
	<form action="rel/pagar_class.php" target="_blank" method="POST">

		<div class="left-content mt-2">

			<a style="margin-bottom: 20px; margin-top: 20px" class="btn ripple btn-success text-white" onclick="inserir()" type="button"><i class="fa fa-file-circle-plus me-2"></i>Adicionar Pagamento</a>

			<div class="col-md-3" style="display: inline-block; margin-bottom: 10px; margin: 10px 10px;">
				<input type="date" name="data-inicial" class="form-control2" id="data-inicial" style="height:35px; width:49%; font-size: 13px;" value="<?php echo date('Y-m-d') ?>" required onchange="$('#filtro_input').val(''); buscar()">

				<input type="date" name="data-final" class="form-control2" id="data-final" style="height:35px; width:49%; font-size: 13px;" value="<?php echo date('Y-m-d') ?>" required onchange="$('#filtro_input').val(''); buscar()">
			</div>


			<div class="card-group" style="margin-bottom: -30px">

				<div class="card text-center mb-5" style="width: 100%; margin-right: 10px; border-radius: 10px; height:110px">
					<a class="text-white" href="#" onclick="$('#filtro_input').val('Vencidas'); buscar(); ">
						<div class="card-header bg-red border-light">
							Vencidas
							<i class="fa fa-external-link pull-right"></i>
						</div>
						<div class="card-body">
							<p class="card-text" style="margin-top:-15px;">
							<h4><span class="text-danger" id="total_vencidas">R$ 0,0</span></h4>
							</p>
						</div>
					</a>
				</div>


				<div class="card text-center mb-5" style="width: 100%; margin-right: 10px; border-radius: 10px; height:110px">
					<a href="#" onclick="$('#filtro_input').val('Hoje'); buscar(); ">
						<div class="card-header bg-orange border-light text-white">
							Vence Hoje
							<i class="fa fa-external-link pull-right"></i>
						</div>
						<div class="card-body">
							<p class="card-text" style="margin-top:-15px;">
							<h4><span style="color: #f05800" id="total_hoje">R$ 0,0</span></h4>
							</p>
						</div>
					</a>
				</div>


				<div class="card text-center mb-5" style="width: 100%; margin-right: 10px; border-radius: 10px; height:110px">
					<a href="#" onclick="$('#filtro_input').val('Amanha'); buscar(); ">
						<div class="card-header border-light text-white" style="background: gray">
							Vence Amanhã
							<i class="fa fa-external-link pull-right"></i>
						</div>
						<div class="card-body">
							<p class="card-text" style="margin-top:-15px;">
							<h4><span style="color: gray" id="total_amanha">R$ 0,0</span></h4>
							</p>
						</div>
					</a>
				</div>


				<div class="card text-center mb-5" style="width: 100%; margin-right: 10px; border-radius: 10px; height:110px">
					<a href="#" onclick=" $('#filtro_input').val('Recebidas'); buscar();">
						<div class="card-header border-light text-white" style="background: #2b7a00">
							Recebidas
							<i class="fa fa-external-link pull-right"></i>
						</div>
						<div class="card-body">
							<p class="card-text" style="margin-top:-15px;">
							<h4><span style="color: #2b7a00" id="total_recebidas">R$ 0,0</span></h4>
							</p>
						</div>
					</a>
				</div>


				<div class="card text-center mb-5" style="width: 100%; margin-right: 10px; border-radius: 10px; height:110px">
					<a href="#" onclick="$('#filtro_input').val('Todas'); buscar();">
						<div class="card-header border-light text-white" style="background: #1f1f1f;">
							Total
							<i class="fa fa-external-link pull-right"></i>
						</div>
						<div class="card-body">
							<p class="card-text" style="margin-top:-15px;">
							<h4><span class="texto-branco" id="total_total">R$ 0,0</span></h4>
							</p>
						</div>
					</a>
				</div>

			</div>

		</div>

		<input type="hidden" name="filtro_input" id="filtro_input">

	</form>

</div>


<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card custom-card">
			<div class="card-body" id="listar">

			</div>
		</div>
	</div>
</div>


<!-- Modal Pagar-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="titulo_inserir"></h4>
				<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="post" id="form-contas">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-8 col-8">
							<div class="form-group">
								<label>Descrição</label>
								<input type="text" class="form-control" name="descricao" id="descricao" placeholder="Descrição">
							</div>
						</div>

						<div class="col-md-4 col-4">
							<div class="form-group">
								<label>Valor</label>
								<input type="text" pattern="(?:\.|,|[0-9])*" title="Permitido apenas valores sem ponto (.) caso o valor tenha centavos acrescentar vírgula (,) com no maximo duas casas decimais." class="form-control" name="valor" id="valor" placeholder="R$ 0,00" required>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-4 col-6">
							<div class="form-group">
								<label>Cliente</label>
								<select class="form-select sel2" name="cliente" id="cliente" style="width:100%;">

									<option value="">Selecionar Cliente</option>

									<?php
									$query = $pdo->query("SELECT * FROM clientes order by nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}

									?>
										<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?> - <?php echo $res[$i]['cpf'] ?></option>

									<?php } ?>

								</select>
							</div>
						</div>


						<div class="col-md-4 col-6">
							<div class="form-group">
								<label>Funcionário</label>
								<select class="form-select sel2" name="funcionario" id="funcionario" style="width:100%;">

									<option value="">Selecionar Funcionário</option>

									<?php
									$query = $pdo->query("SELECT * FROM usuarios where nivel != 'Administrador' and nivel != 'Cliente' order by nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}

									?>
										<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>

									<?php } ?>

								</select>
							</div>
						</div>

						<div class="col-md-4 col-6">
							<div class="form-group">
								<label>Fornecedor</label>
								<select class="form-select sel2" name="fornecedor" id="fornecedor" style="width:100%;">

									<option value="">Selecionar Fornecedor</option>

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

						<div class="col-md-3 col-6">
							<div class="form-group">
								<label>Frequência</label>
								<select class="form-select sel2" name="frequencia" id="frequencia" style="width:100%;">
									<option value="0">Uma Vez</option>
									<?php
									$query = $pdo->query("SELECT * FROM frequencias order by id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}

									?>
										<option value="<?php echo $res[$i]['dias'] ?>"><?php echo $res[$i]['frequencia'] ?></option>

									<?php } ?>

								</select>
							</div>
						</div>

						<div class="col-md-3 col-6">
							<div class="form-group">
								<label>Vencimento</label>
								<input type="date" class="form-control" name="data_venc" id="data_venc" required>
							</div>
						</div>

						<div class="col-md-3 col-6">
							<div class="form-group">
								<label>Data Pagamento</label>
								<input type="date" class="form-control" name="data_pgto" id="data_pgto">
							</div>
						</div>

						<div class="col-md-3 col-6">
							<div class="form-group">
								<label>Forma de Pagamento</label>
								<select class="form-select sel2" name="saida" id="saida" style="width:100%;">
									<option value="">Forma de Pgto</option>
									<?php
									$query = $pdo->query("SELECT * FROM formas_pgto order by id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}

									?>
										<option value="<?php echo $res[$i]['nome'] ?>"><?php echo $res[$i]['nome'] ?></option>

									<?php } ?>

								</select>
							</div>
						</div>
					</div>


					<div class="row">

						<div class="col-md-6 mb-3">
							<label>Observações</label>
							<input type="text" class="form-control" id="obs" name="obs" placeholder="Observações">
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Arquivo</label>
								<input type="file" name="arquivo" onChange="carregarImg();" id="arquivo">
							</div>
						</div>

						<div class="col-md-2">
							<div id="divImg">
								<img src="images/contas/sem-foto.png" width="100px" id="target">
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




<!-- Modal Mostrar -->
<div class="modal fade" id="modalMostrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="tituloModal"><span id="id_mostrar"></span></h4>
				<button id="btn-fechar-dados" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>

			<div class="modal-body">
				<div class="row">

					<div class="col-md-6">
						<div class="tile">
							<div class="table-responsive">
								<table id="" class="text-left table table-bordered">

									<tr>
										<td class="bg-primary text-white">Descrição</td>
										<td><span id="descricao_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Tipo</td>
										<td><span id="tipo_pessoa_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Nome</td>
										<td><span id="pessoa_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Telefone</td>
										<td><span id="tel_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white">Valor</td>
										<td><span id="valor_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Chave Pix</td>
										<td><span id="pix_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Data Lançamento</td>
										<td><span id="lanc_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Data Vencimento</td>
										<td><span id="venc_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Data PGTO</td>
										<td><span id="pgto_mostrar"></span></td>
									</tr>
									<tr>
										<td class="bg-primary text-white w_150">Usuário Cadastro</td>
										<td><span id="usu_lanc_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Usuário Baixa</td>
										<td><span id="usu_pgto_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Frequência</td>
										<td><span id="freq_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Forma Pagamento</td>
										<td><span id="saida_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">Pago</td>
										<td><span id="pago_mostrar"></span></td>
									</tr>

									<tr>
										<td class="bg-primary text-white w_150">OBS</td>
										<td><span id="obs_mostrar"></span></td>
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
										<td align="center"><img src="" id="target_mostrar" width="200px"></td>
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



<!-- Modal Parcelar-->
<div class="modal fade" id="modalParcelar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="tituloModal">Parcelar Conta: <span id="nome-parcelar"> </span></h4>
				<button id="btn-fechar-parcelar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form method="post" id="form-parcelar">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-3">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">Valor</label>
								<input type="text" class="form-control" name="valor-parcelar" id="valor-parcelar" readonly>
							</div>
						</div>

						<div class="col-md-3">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">Parcelas</label>
								<input type="number" min="0" class="form-control" name="qtd-parcelar" id="qtd-parcelar" placeholder="0" required>
							</div>
						</div>

						<div class="col-md-6 mt-1">
							<div class="form-group">
								<label>Frequência Parcelas</label>
								<select class="form-control sel3" name="frequencia" id="frequencia-parcelar" required style="width:100%;">
									<option value="0">Uma Vez</option>
									<?php
									$query = $pdo->query("SELECT * FROM frequencias order by id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}
										$id_item = $res[$i]['id'];
										$nome_item = $res[$i]['frequencia'];
										$dias = $res[$i]['dias'];

										if ($nome_item != 'Uma Vez' and $nome_item != 'Única') {

									?>
											<option <?php if ($nome_item == 'Mensal') { ?> selected <?php } ?> value="<?php echo $dias ?>"><?php echo $nome_item ?></option>

									<?php }
									} ?>


								</select>
							</div>
						</div>

					</div>


					<input type="hidden" name="id-parcelar" id="id-parcelar">
					<input type="hidden" name="nome-parcelar" id="nome-input-parcelar">
					<small>
						<div id="mensagem-parcelar" align="center" class="mt-3"></div>
					</small>


					<div align="right">
						<div class="col-md-4" style="margin-top:20px">
							<button type="submit" class="btn btn-success">Parcelar<i class="fa-solid fa-check ms-2"></i></button>
						</div>
					</div>

				</div>
			</form>
		</div>
	</div>
</div>



<!-- Modal Baixar-->
<div class="modal fade" id="modalBaixar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="tituloModal">Baixar Conta: <span id="descricao-baixar"> </span></h4>
				<button id="btn-fechar-baixar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form-baixar" method="post">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
								<label>Valor <small class="text-muted">(Total ou Parcial)</small></label>
								<input onkeyup="totalizar()" type="text" class="form-control" name="valor-baixar" id="valor-baixar" readonly>
							</div>
						</div>


						<div class="col-md-6 style=">
							<div class="form-group">
								<label>Local da Saída</label>
								<select class="form-select sel4" name="saida-baixar" id="saida-baixar" style="width:100%;" required>
									<option value="">Forma de Pgto</option>
									<?php
									$query = $pdo->query("SELECT * FROM formas_pgto order by id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for ($i = 0; $i < @count($res); $i++) {
										foreach ($res[$i] as $key => $value) {
										}

									?>
										<option value="<?php echo $res[$i]['nome'] ?>"><?php echo $res[$i]['nome'] ?></option>

									<?php } ?>

								</select>
							</div>
						</div>

					</div>


					<div class="row">

						<div class="col-md-4">
							<div class="mb-3">
								<label>Multa em R$</label>
								<input onkeyup="totalizar()" type="text" pattern="(?:\.|,|[0-9])*" title="Permitido apenas valores sem ponto (.) caso o valor tenha centavos acrescentar vírgula (,) com no maximo duas casas decimais." class="form-control" name="valor-multa" id="valor-multa" placeholder="R$ 0,00">
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-3">
								<label>Júros em R$</label>
								<input onkeyup="totalizar()" type="text" pattern="(?:\.|,|[0-9])*" title="Permitido apenas valores sem ponto (.) caso o valor tenha centavos acrescentar vírgula (,) com no maximo duas casas decimais." class="form-control" name="valor-juros" id="valor-juros" placeholder="R$ 0,00">
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-3">
								<label>Desconto em R$</label>
								<input onkeyup="totalizar()" type="text" pattern="(?:\.|,|[0-9])*" title="Permitido apenas valores sem ponto (.) caso o valor tenha centavos acrescentar vírgula (,) com no maximo duas casas decimais." class="form-control" name="valor-desconto" id="valor-desconto" placeholder="R$ 0,00">
							</div>
						</div>

					</div>


					<div class="row">

						<div class="col-md-6">
							<div class="mb-3">
								<label>Data da Baixa</label>
								<input type="date" class="form-control" name="data-baixar" id="data-baixar" value="<?php echo date('Y-m-d') ?>">
							</div>
						</div>


						<div class="col-md-6">
							<div class="mb-3">
								<label>SubTotal</label>
								<input type="text" class="form-control" name="subtotal" id="subtotal" readonly>
							</div>
						</div>
					</div>


					<small>
						<div id="mensagem-baixar" align="center"></div>
					</small>

					<input type="hidden" class="form-control" name="id-baixar" id="id-baixar">


				</div>
				<div class="modal-footer">

					<button type="submit" class="btn btn-success">Baixar<i class="fa-solid fa-check ms-2"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>



<!-- Modal Residuos-->
<div class="modal fade" id="modalResiduos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="tituloModal">Residuos da Conta</h4>
				<button id="btn-fechar-dados" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">

				<small>
					<div id="listar-residuos"></div>
				</small>

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



<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>

<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});

	});
</script>


<script type="text/javascript">
	$(document).ready(function() {
		$('.sel3').select2({
			dropdownParent: $('#modalParcelar')
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.sel4').select2({
			dropdownParent: $('#modalBaixar')
		});
	});
</script>


<script type="text/javascript">
	function excluirConta(id) {
		$('#mensagem-excluir').text('Excluindo...')

		$.ajax({
			url: 'paginas/' + pag + "/excluir.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "html",

			success: function(mensagem) {
				if (mensagem.trim() == "Excluído com Sucesso") {
					buscar();
				} else {
					$('#mensagem-excluir').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
				}
			}
		});
	}
</script>



<script type="text/javascript">
	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#arquivo").files[0];

		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target').attr('src', "images/pdf.png");
			return;
		}

		if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target').attr('src', "images/rar.png");
			return;
		}

		if (resultado[1] === 'doc' || resultado[1] === 'docx') {
			$('#target').attr('src', "images/word.png");
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
	$("#form-parcelar").submit(function() {
		$('#mensagem-parcelar').text('Carregando...');
		$('#btn-salvar-parcelar').hide();
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/parcelar.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem-parcelar').text('');
				$('#mensagem-parcelar').removeClass()
				if (mensagem.trim() == "Parcelado com Sucesso") {
					$('#btn-fechar-parcelar').click();
					buscar()
				} else {
					$('#mensagem-parcelar').addClass('text-danger')
					$('#mensagem-parcelar').text(mensagem)
				}
				$('#btn-salvar-parcelar').show();

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>



<script type="text/javascript">
	$("#form-baixar").submit(function() {
		$('#mensagem-baixar').text('Carregando...');
		$('#btn-salvar-baixar').hide();
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/baixar.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem-baixar').text('');
				$('#mensagem-baixar').removeClass()
				if (mensagem.trim() == "Baixado com Sucesso") {
					$('#btn-fechar-baixar').click();
					baixado();
					buscar();
				} else {
					$('#mensagem-baixar').addClass('text-danger')
					$('#mensagem-baixar').text(mensagem)
				}

				$('#btn-salvar-baixar').show();
			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>


<script type="text/javascript">
	function totalizar() {

		valor = $('#valor-baixar').val();
		desconto = $('#valor-desconto').val();
		juros = $('#valor-juros').val();
		multa = $('#valor-multa').val();

		valor = valor.replace(",", ".");
		desconto = desconto.replace(",", ".");
		juros = juros.replace(",", ".");
		multa = multa.replace(",", ".");

		if (valor == "") {
			valor = 0;
		}

		if (desconto == "") {
			desconto = 0;
		}

		if (juros == "") {
			juros = 0;
		}

		if (multa == "") {
			multa = 0;
		}

		subtotal = parseFloat(valor) + parseFloat(juros) + parseFloat(multa) - parseFloat(desconto);

		$('#subtotal').val(subtotal.toLocaleString('pt-BR', {
			minimumFractionDigits: 2
		}));

	}
</script>


<script type="text/javascript">
	function buscar() {
		var filtro = $('#filtro_input').val();
		var dataInicial = $('#data-inicial').val();
		var dataFinal = $('#data-final').val();
		listar(filtro, dataInicial, dataFinal)
	}
</script>


<script type="text/javascript">
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
</script>

<script type="text/javascript">
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
</script>




<script type="text/javascript">
	$("#form-contas").submit(function() {
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/inserir.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {
					$('#btn-fechar').click();
					buscar()
				} else {
					$('#mensagem').addClass('text-danger')
					$('#mensagem').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>


<!-- Auto Foco no campo da modal -->
<script type="text/javascript">
	const modalForm = document.getElementById('modalForm')
	const descricao = document.getElementById('descricao')

	modalForm.addEventListener('shown.bs.modal', () => {
		descricao.focus()
	})
</script>