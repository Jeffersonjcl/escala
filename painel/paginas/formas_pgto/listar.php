<?php
$tabela = 'formas_pgto';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT * from $tabela order by nome asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	echo <<<HTML
	<small>
		<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
		<thead> 
		<tr>
		<th align="center" width="5%" class="text-center">Selecionar</th> 
		<th width="70%">Nome</th>			
		<th>Ações</th>
		</tr> 
		</thead> 
		<tbody>	
	HTML;
} else {
	echo 'Não encontrei nenhuma Forma de Pagamento cadastrada!';
}

for ($i = 0; $i < $linhas; $i++) {
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];


	echo <<<HTML
	<tr>
	<td align="center">
	<div class="custom-checkbox custom-control">
	<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
	<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
	</div>
	</td>
	<td> {$nome}
	</td>
	</td>

	<td>
		
	<a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

	<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown" title="Excluir Forma de Pagamento"><i class="fa fa-trash-can"></i> </a>
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
			"language": {
				//"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
			},
			"ordering": false,
			"stateSave": true
		});
	});
</script>

<script type="text/javascript">
	function editar(id, nome) {
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#nome').val(nome);


		$('#modalForm').modal('show');
	}




	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');

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