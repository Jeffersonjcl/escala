<?php 
$tabela = 'produtos';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT * from $tabela where estoque <= nivel_estoque order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr>

	<th>Nome</th>
	<th>Categoria</th>	
	<th>Valor Compra</th>	
	<th>Valor Venda</th>
	<th>Estoque</th>	
	<th>Nível Mínimo</th>		
	<th>Foto</th>	
	
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$categoria = $res[$i]['categoria'];
	$valor_venda = $res[$i]['valor_venda'];
	$valor_compra = $res[$i]['valor_compra'];
	$estoque = $res[$i]['estoque'];
	$nivel_estoque = $res[$i]['nivel_estoque'];	
	$foto = $res[$i]['foto'];	
	$ativo = $res[$i]['ativo'];
	
	
	if($ativo == 'Sim'){
	$icone = 'fa-check-square';
	$titulo_link = 'Desativar Usuário';
	$acao = 'Não';
	$classe_ativo = '';
	}else{
		$icone = 'fa-square-o';
		$titulo_link = 'Ativar Usuário';
		$acao = 'Sim';
		$classe_ativo = '#c4c4c4';
	}

	$valor_vendaF = number_format($valor_venda, 2, ',', '.'); 
	$valor_compraF = number_format($valor_compra, 2, ',', '.');

	$query2 = $pdo->query("SELECT * from categorias where id = '$categoria'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$nome_categoria = @$res2[0]['nome'];

	$classe_estoque = '';
	if($estoque <= $nivel_estoque){
		$classe_estoque = 'text-danger';
	}


echo <<<HTML
<tr>
<td> {$nome}</td>
<td>{$nome_categoria}</td>
<td>R$ {$valor_compraF}</td>
<td class="text-verde">R$ {$valor_vendaF}</td>
<td class="{$classe_estoque}">{$estoque}</td>
<td>{$nivel_estoque}</td>
<td><img class="hovv" src="images/produtos/{$foto}" width="25px"></td>

</tr>
HTML;

}

}else{
	echo 'Não possui nenhum produto com estoque baixo!';
}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;
?>



<script type="text/javascript">
	$(document).ready( function () {		
    $('#tabela').DataTable({
    	"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
        },
        "ordering": false,
		"stateSave": true
    });
} );
</script>
