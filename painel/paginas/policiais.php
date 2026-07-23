<?php
$pag = 'policiais';

//verificar se ele tem a permissão de estar nessa página
if (@$policiais == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}

$query = $pdo->query("SELECT * from funcoes order by nome asc");
$funcoes_cadastradas = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT * from postos order by id asc");
$postos_cadastrados = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="breadcrumb-header justify-content-between">
	<div class="left-content mt-2">
		<a class="btn ripple btn-success text-white" onclick="inserir()" type="button"><i class="fe fe-user-plus me-1"></i> Adicionar Policial</a>

		<a class="btn ripple btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalImportar" type="button"><i class="fe fe-upload me-1"></i> Importar Excel</a>

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
							<label>Posto/Graduação</label>
							<select class="form-select" id="posto_id" name="posto_id" required>
								<option value="">Selecione</option>
								<?php foreach ($postos_cadastrados as $p) { ?>
									<option value="<?php echo $p['id'] ?>"><?php echo $p['nome'] ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-md-6 mb-2">
							<label>Numeral</label>
							<input type="text" class="form-control" id="numeral" name="numeral" placeholder="Numeral do Policial">
						</div>
					</div>

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
							<select class="form-select" id="grupo" name="grupo">
								<option value="">Sem Grupo (qualquer)</option>
								<option value="Alpha">Alpha</option>
								<option value="Bravo">Bravo</option>
							</select>
						</div>

						<div class="col-md-4 mb-2">
							<label>Turno Padrão</label>
							<select class="form-select" id="turno_padrao" name="turno_padrao">
								<option value="">Sem Turno (qualquer)</option>
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
						<div class="col-md-12 mb-2">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="drso" name="drso" value="1">
								<label class="form-check-label" for="drso">DRSO (disponível para ser escalado em grupo diferente do seu)</label>
							</div>
						</div>
					</div>

					<div class="row" id="campoDiasDrso" style="display:none">
						<div class="col-md-12 mb-2">
							<?php
							$nomes_meses = [1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
							$mes_atual_nome = $nomes_meses[(int) date('n')];
							?>
							<label>
								Dias disponíveis em <?php echo $mes_atual_nome ?>/<?php echo date('Y') ?>
							</label>
							<div class="text-muted" style="font-size:12px; margin-bottom:6px">
								Marque os dias deste mês em que o policial pode ser escalado fora do seu grupo. Ao virar o mês, essa marcação limpa e precisa ser refeita.
							</div>
							<div class="d-flex flex-wrap gap-1">
								<?php
								$total_dias_mes = (int) date('t');
								for ($dia = 1; $dia <= $total_dias_mes; $dia++) {
								?>
									<div class="form-check form-check-inline m-0" style="width:42px">
										<input class="form-check-input dia-drso" type="checkbox" id="dia_drso_<?php echo $dia ?>" name="dias_drso[]" value="<?php echo $dia ?>">
										<label class="form-check-label" for="dia_drso_<?php echo $dia ?>"><?php echo $dia ?></label>
									</div>
								<?php } ?>
							</div>
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
					</div>

					<div class="row" id="campoMotivo" style="display:none">
						<div class="col-md-12 mb-2">
							<label>Motivo da Indisponibilidade</label>
							<textarea class="form-control" id="motivo_indispo" name="motivo_indispo" placeholder="Férias, licença, cautela, dispensa médica..."></textarea>
						</div>

						<div class="col-md-4 mb-2">
							<label>Início da Indisponibilidade</label>
							<input type="date" class="form-control" id="data_inicio_indispo" name="data_inicio_indispo">
						</div>

						<div class="col-md-4 mb-2">
							<label>Total de Dias</label>
							<input type="number" min="1" class="form-control" id="dias_indispo" name="dias_indispo" placeholder="Ex: 30">
						</div>

						<div class="col-md-4 mb-2">
							<label>Retorna em</label>
							<input type="text" class="form-control" id="data_fim_indispo" readonly>
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


<!-- Modal Importar Excel -->
<div class="modal fade" id="modalImportar" tabindex="-1" aria-labelledby="modalImportarLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="modalImportarLabel">Importar Policiais via Excel</h4>
				<button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="formImportar">
				<div class="modal-body">

					<p>
						Envie uma planilha <b>.xlsx</b> ou <b>.csv</b> com as colunas:
						Nome de Guerra, Nome Completo, Matrícula, Telefone, Grupo, Turno, Função, Posto/Graduação, Numeral, Disponível.
					</p>

					<p class="text-muted">
						Grupo e Turno podem ficar em branco: o policial fica disponível para qualquer grupo/turno.
					</p>

					<p>
						<a href="paginas/policiais/modelo_importacao_policiais.csv" download>
							<i class="fa fa-file-arrow-down me-1"></i> Baixar planilha modelo
						</a>
					</p>

					<div class="mb-2">
						<label>Arquivo</label>
						<input type="file" class="form-control" id="arquivo_importar" name="arquivo" accept=".xlsx,.csv" required>
					</div>

					<div id="resultadoImportar"></div>

				</div>
				<div class="modal-footer">
					<button type="submit" id="btn_importar" class="btn btn-success"><span id="btn_importar_texto">Importar</span><i class="fa-solid fa-check ms-2"></i></button>
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
			$('#data_inicio_indispo').attr('required', true);
			$('#dias_indispo').attr('required', true);
			if (!$('#data_inicio_indispo').val()) {
				var hoje = new Date();
				var iso = hoje.getFullYear() + '-' + String(hoje.getMonth() + 1).padStart(2, '0') + '-' + String(hoje.getDate()).padStart(2, '0');
				$('#data_inicio_indispo').val(iso);
			}
			calcularRetornoIndispo();
		} else {
			$('#campoMotivo').fadeOut().find('textarea').val('');
			$('#motivo_indispo').attr('required', false);
			$('#data_inicio_indispo').attr('required', false).val('');
			$('#dias_indispo').attr('required', false).val('');
			$('#data_fim_indispo').val('');
		}
	});

	function calcularRetornoIndispo() {
		var inicio = $('#data_inicio_indispo').val();
		var dias = parseInt($('#dias_indispo').val());

		if (!inicio || !dias || dias < 1) {
			$('#data_fim_indispo').val('');
			return;
		}

		var data = new Date(inicio + 'T00:00:00');
		data.setDate(data.getDate() + dias);

		var dia = String(data.getDate()).padStart(2, '0');
		var mes = String(data.getMonth() + 1).padStart(2, '0');
		var ano = data.getFullYear();

		$('#data_fim_indispo').val(dia + '/' + mes + '/' + ano);
	}

	$('#data_inicio_indispo, #dias_indispo').on('input change', calcularRetornoIndispo);

	$('#criar_acesso').change(function() {
		if ($(this).is(':checked')) {
			$('#campoEmailAcesso').fadeIn();
			$('#email_acesso').attr('required', true);
		} else {
			$('#campoEmailAcesso').fadeOut().find('input').val('');
			$('#email_acesso').attr('required', false);
		}
	});

	$('#drso').change(function() {
		if ($(this).is(':checked')) {
			$('#campoDiasDrso').fadeIn();
		} else {
			$('#campoDiasDrso').fadeOut().find('.dia-drso').prop('checked', false);
		}
	});
</script>

<script type="text/javascript">
	$('#formImportar').submit(function(event) {
		event.preventDefault();

		var formData = new FormData(this);

		$('#resultadoImportar').html('');
		$('#btn_importar').prop('disabled', true);
		$('#btn_importar_texto').text('Importando...');

		$.ajax({
			url: 'paginas/policiais/importar.php',
			type: 'POST',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',

			success: function(res) {
				if (res.erro) {
					$('#resultadoImportar').html('<div class="alert alert-danger mt-2">' + res.erro + '</div>');
					return;
				}

				var html = '<div class="alert alert-success mt-2">' + res.importados + ' policial(is) importado(s) com sucesso.</div>';

				if (res.erros && res.erros.length > 0) {
					html += '<div class="alert alert-warning mt-2"><b>' + res.erros.length + ' linha(s) ignorada(s):</b><ul class="mb-0">';
					res.erros.forEach(function(e) {
						html += '<li>' + e + '</li>';
					});
					html += '</ul></div>';
				}

				$('#resultadoImportar').html(html);

				if (res.importados > 0) {
					listar();
				}
			},

			error: function() {
				$('#resultadoImportar').html('<div class="alert alert-danger mt-2">Erro ao importar o arquivo.</div>');
			},

			complete: function() {
				$('#btn_importar').prop('disabled', false);
				$('#btn_importar_texto').text('Importar');
			}
		});
	});

	document.getElementById('modalImportar').addEventListener('hidden.bs.modal', () => {
		$('#formImportar')[0].reset();
		$('#resultadoImportar').html('');
	});
</script>

<script type="text/javascript">
	const modalForm = document.getElementById('modalForm')
	const nome_guerra = document.getElementById('nome_guerra')

	modalForm.addEventListener('shown.bs.modal', () => {
		nome_guerra.focus()
	})
</script>
