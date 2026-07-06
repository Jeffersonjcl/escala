<?php 
$tabela = 'receber';
require_once("../../../conexao.php");


$id = $_POST['id'];

echo <<<HTML
<small><table id="example_res" class="table table-striped table-light table-hover my-4" style="font-weight: 10px">
<thead>
<tr>
<th>Quantidade</th>
<th>Produto</th>	
<th>Vlr Unit</th>							
<th>Total</th>
</tr>
</thead>
<tbody>
HTML;


	$res = $pdo->query("SELECT * from itens_venda where id_venda = '$id' order by id asc");
		$dados = $res->fetchAll(PDO::FETCH_ASSOC);
		$linhas = count($dados);

		$sub_tot;
		$total_itens = 0;
		for ($i=0; $i < count($dados); $i++) { 
			foreach ($dados[$i] as $key => $value) {
			}

			$id_produto = $dados[$i]['produto']; 
			$quantidade = $dados[$i]['quantidade'];
			$valor = $dados[$i]['valor'];
			$total= $dados[$i]['total'];

			
			$res_p = $pdo->query("SELECT * from produtos where id = '$id_produto' ");
				$dados_p = $res_p->fetchAll(PDO::FETCH_ASSOC);
				$nome_produto = $dados_p[0]['nome'];				
				$valor = $dados_p[0]['valor_venda'];			
				$total_item = $valor * $quantidade;	

				$valorF = number_format($valor, 2, ',', '.');
				$total_itemF = number_format($total_item, 2, ',', '.');

	

echo <<<HTML
	<tr>
	<td>{$quantidade}</td>
	<td>{$nome_produto}</td>
	<td>R$ {$valorF}</td>		
	<td>R$ {$total_itemF}</td>							
		
	</tr>
HTML;
} 
echo <<<HTML
</tbody>
</table>
</small>
HTML;

?>

