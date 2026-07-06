<?php
$tabela = 'policiais';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT p.*, f.nome funcao_nome FROM $tabela p INNER JOIN funcoes f ON f.id = p.funcao_id ORDER BY p.nome_guerra ASC");
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
	$turno_padrao = $res[$i]['turno_padrao'];
	$funcao_id = $res[$i]['funcao_id'];
	$funcao_nome = $res[$i]['funcao_nome'];
	$foto = $res[$i]['foto'] ?: 'sem-foto.jpg';
	$disponivel = $res[$i]['disponivel'];
	$motivo_indispo = @$res[$i]['motivo_indispo'];
	$motivo_js = htmlspecialchars($motivo_indispo ?? '', ENT_QUOTES);

	if ($disponivel == 1) {
		$badge = '<span class="badge bg-success">Disponível</span>';
	} else {
		$badge = '<span class="badge bg-danger" title="' . htmlspecialchars($motivo_indispo ?? '') . '">Indisponível</span>';
	}

	echo <<<HTML
<tr>
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td><img src="images/perfil/{$foto}" width="30px" style="border-radius:50%"></td>
<td>{$nome_guerra}</td>
<td>{$grupo}</td>
<td>Turno {$turno_padrao}</td>
<td>{$funcao_nome}</td>
<td>{$badge}</td>
<td>
	<a class="btn btn-info btn-sm" href="#" onclick='editar({$id}, "{$nome_guerra}", "{$nome_completo}", "{$matricula}", "{$telefone}", "{$grupo}", "{$turno_padrao}", "{$funcao_id}", "{$disponivel}", "{$motivo_js}", "{$foto}")' title="Editar Dados"><i class="fa fa-edit"></i></a>

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
	function editar(id, nome_guerra, nome_completo, matricula, telefone, grupo, turno_padrao, funcao_id, disponivel, motivo_indispo, foto) {
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Policial');

		$('#id').val(id);
		$('#nome_guerra').val(nome_guerra);
		$('#nome_completo').val(nome_completo);
		$('#matricula').val(matricula);
		$('#telefone').val(telefone);
		$('#grupo').val(grupo);
		$('#turno_padrao').val(turno_padrao);
		$('#funcao_id').val(funcao_id);
		$('#disponivel').val(disponivel).change();
		$('#motivo_indispo').val(motivo_indispo);
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
		$('#turno_padrao').val('');
		$('#funcao_id').val('');
		$('#disponivel').val('1').change();
		$('#motivo_indispo').val('');
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
