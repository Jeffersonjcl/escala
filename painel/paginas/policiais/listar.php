<?php
require_once(__DIR__ . '/../_guard.php');
$tabela = 'policiais';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT p.*, f.nome funcao_nome, po.nome posto_nome FROM $tabela p INNER JOIN funcoes f ON f.id = p.funcao_id LEFT JOIN postos po ON po.id = p.posto_id ORDER BY CASE po.nome
	WHEN 'Coronel PM' THEN 1
	WHEN 'Tenente Coronel PM' THEN 2
	WHEN 'Tenente Coronel QOPM' THEN 3
	WHEN 'Tenente Coronel QOAPM' THEN 4
	WHEN 'Major QOPM' THEN 5
	WHEN 'Major QOAPM' THEN 6
	WHEN 'Capitão QOPM' THEN 7
	WHEN 'Capitão QOAPM' THEN 8
	WHEN '1º Tenente QOPM' THEN 9
	WHEN '1º Tenente QOAPM' THEN 10
	WHEN '2º Tenente QOPM' THEN 11
	WHEN '2º Tenente QOAPM' THEN 12
	WHEN 'SubTenente PM' THEN 13
	WHEN '1º Sargento PM' THEN 14
	WHEN '2º Sargento PM' THEN 15
	WHEN '3º Sargento PM' THEN 16
	WHEN 'Cabo PM' THEN 17
	WHEN 'Soldado PM' THEN 18
	ELSE 19
END, (p.numeral IS NULL OR p.numeral = '') ASC, CAST(NULLIF(p.numeral, '') AS UNSIGNED) ASC, p.nome_guerra ASC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	echo <<<HTML
<small>
	<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead>
	<tr>
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Foto</th>
	<th>Posto/Grad.</th>
	<th>Nome de Guerra</th>
	<th>Grupo</th>
	<th>Turno</th>
	<th>Função</th>
	<th>Disponibilidade</th>
	<th>Ações</th>
	</tr>
	</thead>
	<tbody>
HTML;
} else {
	echo 'Não encontrei nenhum Policial cadastrado!';
}

for ($i = 0; $i < $linhas; $i++) {
	$id = $res[$i]['id'];
	$nome_guerra = $res[$i]['nome_guerra'];
	$nome_completo = @$res[$i]['nome_completo'];
	$matricula = @$res[$i]['matricula'];
	$telefone = @$res[$i]['telefone'];
	$grupo = $res[$i]['grupo'];
	$drso = $res[$i]['drso'];
	$turno_padrao = $res[$i]['turno_padrao'];
	$funcao_id = $res[$i]['funcao_id'];
	$funcao_nome = $res[$i]['funcao_nome'];
	$posto_id = @$res[$i]['posto_id'];
	$posto_nome = @$res[$i]['posto_nome'];
	$numeral = @$res[$i]['numeral'];
	$foto = $res[$i]['foto'] ?: 'sem-foto.jpg';
	$disponivel = $res[$i]['disponivel'];
	$motivo_indispo = @$res[$i]['motivo_indispo'];
	$motivo_js = htmlspecialchars($motivo_indispo ?? '', ENT_QUOTES);
	$data_inicio_indispo = @$res[$i]['data_inicio_indispo'];
	$dias_indispo = @$res[$i]['dias_indispo'];
	$data_fim_indispo = @$res[$i]['data_fim_indispo'];

	if ($disponivel == 1) {
		$badge = '<span class="badge bg-success">Disponível</span>';
	} else {
		$titulo_badge = htmlspecialchars($motivo_indispo ?? '');
		if ($data_fim_indispo) {
			$titulo_badge .= ' (retorna em ' . date('d/m/Y', strtotime($data_fim_indispo)) . ')';
		}
		$badge = '<span class="badge bg-danger" title="' . $titulo_badge . '">Indisponível</span>';
	}

	$posto_display = $posto_nome ? htmlspecialchars($posto_nome) : '-';
	if ($numeral) {
		$posto_display .= ' ' . htmlspecialchars($numeral);
	}
	$numeral_js = htmlspecialchars($numeral ?? '', ENT_QUOTES);

	$labels_grupo = ['Adm' => 'Administrativo', 'Alpha' => 'Alpha', 'Bravo' => 'Bravo', 'Guarda01' => 'Guarda 01', 'Guarda02' => 'Guarda 02', 'Guarda03' => 'Guarda 03', 'Guarda04' => 'Guarda 04'];
	$labels_turno = ['Adm' => 'Administrativo', 'A' => 'Turno A', 'B' => 'Turno B', '24H' => 'Turno 24H'];

	$grupo_display = $grupo ? ($labels_grupo[$grupo] ?? $grupo) : '<span class="text-muted">Qualquer</span>';
	if ($drso) {
		$grupo_display .= ' <span class="badge bg-secondary" title="Disponível para ser escalado em grupo diferente do seu">DRSO</span>';
	}
	$turno_display = $turno_padrao ? ($labels_turno[$turno_padrao] ?? $turno_padrao) : '<span class="text-muted">Qualquer</span>';

	echo <<<HTML
<tr>
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td><img src="images/perfil/{$foto}" width="30px" style="border-radius:50%"></td>
<td>{$posto_display}</td>
<td>{$nome_guerra}</td>
<td>{$grupo_display}</td>
<td>{$turno_display}</td>
<td>{$funcao_nome}</td>
<td>{$badge}</td>
<td>
	<a class="btn btn-info btn-sm" href="#" onclick='editar({$id}, "{$nome_guerra}", "{$nome_completo}", "{$matricula}", "{$telefone}", "{$grupo}", "{$turno_padrao}", "{$funcao_id}", "{$disponivel}", "{$motivo_js}", "{$foto}", "{$data_inicio_indispo}", "{$dias_indispo}", "{$posto_id}", "{$numeral_js}", "{$drso}")' title="Editar Dados"><i class="fa fa-edit"></i></a>

<div class="dropdown" style="display: inline-block;">
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown" title="Excluir Policial"><i class="fa fa-trash-can"></i> </a>
		<div  class="dropdown-menu tx-13">
			<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
			<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger"><button class="btn-danger">Sim</button></span></a></p>
			</div>
		</div>
	</div>

</td>
</tr>
HTML;
}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;
?>



<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"language": {},
			"ordering": false,
			"stateSave": true
		});
	});
</script>

<script type="text/javascript">
	function editar(id, nome_guerra, nome_completo, matricula, telefone, grupo, turno_padrao, funcao_id, disponivel, motivo_indispo, foto, data_inicio_indispo, dias_indispo, posto_id, numeral, drso) {
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Policial');

		$('#id').val(id);
		$('#nome_guerra').val(nome_guerra);
		$('#nome_completo').val(nome_completo);
		$('#matricula').val(matricula);
		$('#telefone').val(telefone);
		$('#grupo').val(grupo);
		$('#drso').prop('checked', drso == '1');
		$('.dia-drso').prop('checked', false);
		if (drso == '1') {
			$('#campoDiasDrso').show();
			$.ajax({
				url: 'paginas/policiais/dias_drso.php',
				method: 'POST',
				data: { policial_id: id },
				dataType: 'json',
				success: function(dias) {
					dias.forEach(function(dia) {
						$('#dia_drso_' + dia).prop('checked', true);
					});
				}
			});
		} else {
			$('#campoDiasDrso').hide();
		}
		$('#turno_padrao').val(turno_padrao);
		$('#funcao_id').val(funcao_id);
		$('#posto_id').val(posto_id);
		$('#numeral').val(numeral);
		$('#disponivel').val(disponivel).change();
		$('#motivo_indispo').val(motivo_indispo);
		$('#data_inicio_indispo').val(data_inicio_indispo);
		$('#dias_indispo').val(dias_indispo);
		calcularRetornoIndispo();
		$('#foto_atual').val(foto);

		// acesso ao painel do policial só é oferecido no cadastro inicial
		$('#linhaCriarAcesso').hide();

		$('#modalForm').modal('show');
	}

	function limparCampos() {
		$('#id').val('');
		$('#nome_guerra').val('');
		$('#nome_completo').val('');
		$('#matricula').val('');
		$('#telefone').val('');
		$('#grupo').val('');
		$('#drso').prop('checked', false);
		$('#campoDiasDrso').hide();
		$('.dia-drso').prop('checked', false);
		$('#turno_padrao').val('');
		$('#funcao_id').val('');
		$('#posto_id').val('');
		$('#numeral').val('');
		$('#disponivel').val('1').change();
		$('#motivo_indispo').val('');
		$('#data_inicio_indispo').val('');
		$('#dias_indispo').val('');
		$('#data_fim_indispo').val('');
		$('#foto_atual').val('');
		$('#criar_acesso').prop('checked', false).change();
		$('#linhaCriarAcesso').show();

		$('#ids').val('');
		$('#btn-deletar').hide();
	}

	function selecionar(id) {

		var ids = $('#ids').val();

		if ($('#seletor-' + id).is(":checked") == true) {
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		} else {
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if (ids_final == "") {
			$('#btn-deletar').hide();
		} else {
			$('#btn-deletar').show();
		}
	}

	function deletarSel() {
		var ids = $('#ids').val();
		var id = ids.split("-");

		for (i = 0; i < id.length - 1; i++) {
			excluirMultiplos(id[i]);
		}

		setTimeout(() => {
			listar();
		}, 1000);

		limparCampos();
	}
</script>
