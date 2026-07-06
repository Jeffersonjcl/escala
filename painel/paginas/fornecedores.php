<?php
$pag = 'fornecedores';

//verificar se ele tem a permissão de estar nessa página
if (@$fornecedores == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}

?>
<div class="breadcrumb-header justify-content-between">
	<div class="left-content mt-2">

		<a class="btn ripple btn-success text-white" onclick="inserir()" type="button"><i class="fe fe-user-plus me-1"></i> Adicionar <?php echo ucfirst($pag); ?></a>

		<div class="dropdown" style="display: inline-block;">
			<a href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="btn btn-danger dropdown" id="btn-deletar" style="display:none"><i class="fe fe-trash-2"></i> Deletar</a>
			<div class="dropdown-menu tx-13">
				<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
					<p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger"><button class="btn-danger">Sim</button></span></a></p>
				</div>
			</div>
		</div>

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


<input type="hidden" id="ids">


<!-- Modal Perfil -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6 mb-12">
							<label>Nome</label>
							<input type="text" class="form-control" id="nome" name="nome" placeholder="Seu Nome" required>
						</div>

						<div class="col-md-6 mb-12">
							<label>Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Seu Email">
						</div>

					</div>


					<div class="row">

						<div class="col-md-6 mb-2 col-6">
							<label>Telefone</label>
							<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone" required>
						</div>


						<div class="col-md-6 col-6">
							<label>Pix</label>
							<input type="text" class="form-control" id="pix" name="pix" placeholder="CPF xxxxx">
						</div>

					</div>

					<div class="row">

						<div class="col-md-10 mb-6">
							<label>Endereço</label>
							<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Seu Endereço">
						</div>

						<div class="col-md-2 mb-6">
							<label>Comissão </label>
							<input type="number" min="0" class="form-control" id="comissao" name="comissao" placeholder="0,00">
						</div>

					</div>

					<input type="hidden" class="form-control" id="id" name="id">

					<br>

					<small>
						<div id="mensagem" align="center"></div>
					</small>

				</div>

				<div class="modal-footer">
					<button type="submit" id="btn_salvar" class="btn btn-success">Salvar<i class="fa-solid fa-check ms-2"></i></button>
				</div>

			</form>
		</div>
	</div>
</div>





<!-- Modal Dados -->
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"></span></h4>
				<button id="btn-fechar-dados" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>

			<div class="modal-body">


				<div class="col-md-12">
					<div class="tile">
						<div class="table-responsive">
							<table id="" class="text-left table table-bordered">

								<tr>
									<td class="bg-primary text-white">Telefone</td>
									<td><span id="telefone_dados"></span></td>
								</tr>

								<tr>
									<td class="bg-primary text-white">Email</td>
									<td><span id="email_dados"></span></td>
								</tr>

								<tr>
									<td class="bg-primary text-white w_150">Chave Pix</td>
									<td><span id="pix_dados"></span></td>
								</tr>

								<tr>
									<td class="bg-primary text-white w_150">Comissão %</td>
									<td><span id="comissao_dados"></span></td>
								</tr>

								<tr>
									<td class="bg-primary text-white w_150">Data Cadastro</td>
									<td><span id="data_dados"></span></td>
								</tr>

								<tr>
									<td class="bg-primary text-white w_150">Endereço</td>
									<td><span id="endereco_dados"></span></td>
								</tr>


							</table>
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
	const modalForm = document.getElementById('modalForm')
	const nome = document.getElementById('nome')

	modalForm.addEventListener('shown.bs.modal', () => {
		nome.focus()
	})
</script>