<?php 
require_once("../../../conexao.php");
$tabela = 'acessos';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){	

	echo <<<HTML
	<small>
	<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th>Chave</th>	
	<th>Grupo</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
	HTML;

	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){}
			$id = $res[$i]['id'];
		$nome = $res[$i]['nome'];	
		$chave = $res[$i]['chave'];
		$grupo = $res[$i]['grupo'];	
		
		
		$query2 = $pdo->query("SELECT * FROM grupo_acessos where id = '$grupo'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_cat = $res2[0]['nome'];
		}else{
			$nome_cat = 'Nenhum!';
		}
		


		echo <<<HTML
		<tr>
		<td>{$nome}</td>
		<td>{$chave}</td>
		<td>{$nome_cat}</td>
		<td>
		<a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}', '{$chave}', '{$grupo}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown" title="Excluir Acesso"><i class="fa fa-trash-can"></i> </a>
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
	</small>
	HTML;


}else{
	echo 'Não possui nenhum cadastro!';
}

?>

<script type="text/javascript">
	$(document).ready( function () {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	} );
</script>


<script type="text/javascript">
	function editar(id, nome, chave, grupo){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#chave').val(chave);
		$('#grupo').val(grupo).change();
		
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');

		
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#chave').val('');	
	}
</script>


