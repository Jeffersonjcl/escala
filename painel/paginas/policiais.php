<?php
$pag = 'policiais';

//verificar se ele tem a permissão de estar nessa página
if (@$policiais == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}

$query = $pdo->query("SELECT * from funcoes order by nome asc");
$funcoes_cadastradas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="breadcrumb-header justify-content-between">
	<div class="left-content mt-2">
		<a class="btn ripple btn-success text-white" onclick="inserir()" type="button"><i class="fe fe-user-plus me-1"></i> Adicionar Policial</a>

		<div class="dropdown" style="display: inline-block;">
			<a href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="btn btn-danger dropdown" id="btn-deletar" style="display:none"><i class="fe fe-trash-2"></i> Deletar</a>
			<div class="dropdown-menu tx-13">
				<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
					<p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger">Sim</span></a></p>
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


<!-- Modal Policial -->
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
						<div class="col-md-6 mb-2">
							<label>Nome de Guerra</label>
							<input type="text" class="form-control" id="nome_guerra" name="nome_guerra" placeholder="Nome de Guerra" required>
						</div>

						<div class="col-md-6 mb-2">
							<label>Nome Completo</label>
							<input type="text" class="form-control" id="nome_completo" name="nome_completo" placeholder="Nome Completo">
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 mb-2">
							<label>Matrícula</label>
							<input type="text" class="form-control" id="matricula" name="matricula" placeholder="Matrícula">
						</div>

						<div class="col-md-4 mb-2">
							<label>Telefone</label>
							<input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">
						</div>

						<div class="col-md-4 mb-2">
							<label>Foto</label>
							<input type="file" class="form-control" id="foto" name="foto" accept="image/*">
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 mb-2">
							<label>Grupo</label>
							<select class="form-select" id="grupo" name="grupo" required>
								<option value="">Selecione</option>
								<option value="Alpha">Alpha</option>
								<option value="Bravo">Bravo</option>
							</select>
						</div>

						<div class="col-md-4 mb-2">
							<label>Turno Padrão</label>
							<select class="form-select" id="turno_padrao" name="turno_padrao" required>
								<option value="">Selecione</option>
								<option value="A">Turno A</option>
								<option value="B">Turno B</option>
							</select>
						</div>

						<div class="col-md-4 mb-2">
							<label>Função</label>
							<select class="form-select" id="funcao_id" name="funcao_id" required>
								<option value="">Selecione</option>
								<?php foreach ($funcoes_cadastradas as $f) { ?>
									<option value="<?php echo $f['id'] ?>"><?php echo $f['nome'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 mb-2">
							<label>Disponível para Serviço</label>
							<select class="form-select" id="disponivel" name="disponivel">
								<option value="1">Sim</option>
								<option value="0">Não</option>
							</select>
						</div>

						<div class="col-md-8 mb-2" id="campoMotivo" style="display:none">
							<label>Motivo da Indisponibilidade</label>
							<textarea class="form-control" id="motivo_indispo" name="motivo_indispo" placeholder="Férias, licença, cautela, dispensa médica..."></textarea>
						</div>
					</div>

					<hr>

					<div class="row" id="linhaCriarAcesso">
						<div class="col-md-12 mb-2">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="criar_acesso" name="criar_acesso" value="Sim">
								<label class="form-check-label" for="criar_acesso">Criar acesso ao Painel do Policial (para consultar a própria escala)</label>
							</div>
						</div>

						<div class="col-md-6 mb-2" id="campoEmailAcesso" style="display:none">
							<label>Email de Acesso</label>
							<input type="email" class="form-control" id="email_acesso" name="email_acesso" placeholder="email@exemplo.com">
						</div>
					</div>

					<input type="hidden" class="form-control" id="id" name="id">
					<input type="hidden" id="foto_atual" name="foto_atual">

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


<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>

<script src="js/ajax.js"></script>

<script type="text/javascript">
	$('#disponivel').change(function() {
		if ($(this).val() == '0') {
			$('#campoMotivo').fadeIn();
			$('#motivo_indispo').attr('required', true);
		} else {
			$('#campoMotivo').fadeOut().find('textarea').val('');
			$('#motivo_indispo').attr('required', false);
		}
	});

	$('#criar_acesso').change(function() {
		if ($(this).is(':checked')) {
			$('#campoEmailAcesso').fadeIn();
			$('#email_acesso').attr('required', true);
		} else {
			$('#campoEmailAcesso').fadeOut().find('input').val('');
			$('#email_acesso').attr('required', false);
		}
	});
</script>

<script type="text/javascript">
	const modalForm = document.getElementById('modalForm')
	const nome_guerra = document.getElementById('nome_guerra')

	modalForm.addEventListener('shown.bs.modal', () => {
		nome_guerra.focus()
	})
</script>
