<?php 
$tabela = 'servicos_orc';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');

$id = @$_POST['id'];

$total_pago = 0;
$total_pendentes = 0;
$total_vencidas = 0;
$total_pendentesF = 0;
$total_vencidasF = 0;
$query = $pdo->query("SELECT * from $tabela where cliente = '$id' order by id desc limit 10");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small><small>
	<table class="table table-bordered" id="">
	<thead> 
	<tr> 
	<th>Serviço</th>
	<th class="esc">Valor</th>	
	<th class="esc">Data</th>		
				
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i<$linhas; $i++){
	$id_conta = $res[$i]['id'];
	$servico = $res[$i]['servico'];
	$valor = $res[$i]['total'];
	$data = $res[$i]['data'];
	
	$dataF = implode('/', array_reverse(@explode('-', $data)));
	$valorF = number_format($valor, 2, ',', '.');

	$query2 = $pdo->query("SELECT * from servicos where id = '$servico'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_servico = $res2[0]['nome'];

echo <<<HTML
<tr class="">
<td > {$nome_servico}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$dataF}</td>

</tr>
HTML;

}

}else{
	echo '<small>Não possui nenhuma serviço feito ainda!</small>';
}


echo <<<HTML
</tbody>
</table>
</small></small>

	
HTML;
?>

