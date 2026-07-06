<?php 
include('../../conexao.php');

$id = $_GET['id'];

//BUSCAR AS INFORMAÇÕES DO PEDIDO
$query = $pdo->query("SELECT * from receber where id = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

		$id = $res[0]['id'];
		$descricao = $res[0]['descricao'];
		$cliente = $res[0]['cliente'];
		$valor = $res[0]['valor'];
		$data_lanc = $res[0]['data_lanc'];
		$data_venc = $res[0]['data_venc'];
		$data_pgto = $res[0]['data_pgto'];
		$usuario_lanc = $res[0]['usuario_lanc'];
		$usuario_pgto = $res[0]['usuario_pgto'];
		$frequencia = $res[0]['frequencia'];
		$saida = $res[0]['saida'];
		$arquivo = $res[0]['arquivo'];
		$pago = $res[0]['pago'];
		$obs = $res[0]['obs'];
		$desconto = $res[0]['desconto'];
		$troco = $res[0]['troco'];
		$hora = $res[0]['hora'];
		$cancelada = $res[0]['cancelada'];
		$garantia_venda = $res[0]['garantia_venda'];

if($troco > 0){
	$total_troco = $troco - $valor;
}else{
	$total_troco = 0;
}


$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
$valorF = number_format($valor, 2, ',', '.');
$descontoF = number_format($desconto, 2, ',', '.');
$trocoF = number_format($troco, 2, ',', '.');
$total_trocoF = number_format($total_troco, 2, ',', '.');

$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_usu_lanc = $res2[0]['nome'];
}else{
	$nome_usu_lanc = 'Sem Usuário';
}


$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_cliente = $res2[0]['nome'];	
	$tel_cliente = $res2[0]['telefone'];
}else{
	$nome_cliente = 'Não Informado';	
	$tel_cliente = '';
}


?>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php if(@$impressao_automatica == 'Sim' and @$_GET['imprimir'] != 'Não'){ ?>
<script type="text/javascript">
	$(document).ready(function() {    		
		window.print();
		window.close(); 
	} );
</script>
<?php } ?>


<style type="text/css">
	*{
	margin:0px;

	/*Espaçamento da margem da esquerda e da Direita*/
	padding:0px;
	background-color:#ffffff;
	
	font-color:#000;	
	font-family: TimesNewRoman, Geneva, sans-serif; 

}
.text {
	&-center { text-align: center; }
}
.ttu { text-transform: uppercase;
	font-weight: bold;
	font-size: 1.2em;
 }

.printer-ticket {
	display: table !important;
	width: 100%;

	/*largura do Campos que vai os textos*/
	max-width: 400px;
	font-weight: light;
	line-height: 1.3em;

	/*Espaçamento da margem da esquerda e da Direita*/
	padding: 0px;
	font-family: TimesNewRoman, Geneva, sans-serif; 

	/*tamanho da Fonte do Texto*/
	font-size: <?php echo $fonte_comprovante ?>; 
	font-color:#000;
	
	
	}
	
	th { 
		font-weight: inherit;

		/*Espaçamento entre as uma linha para outra*/
		padding:5px;
		text-align: center;

		/*largura dos tracinhos entre as linhas*/
		border-bottom: 1px dashed #000000;
	}


	

	
	
		
	.cor{
		color:#000000;
	}
	
	
	

	/*margem Superior entre as Linhas*/
	.margem-superior{
		padding-top:5px;
	}
	
	
} 
</style>



<table class="printer-ticket">

		<td>
		<img style="margin-top: 10px; margin-left: 40px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="220px">
	</td>

	<tr>
		<th class="ttu" class="title" colspan="3"></th>
	</tr>
	<tr style="font-size: 10px">
		<th colspan="3">
			<?php echo $endereco_sistema ?> <br />
			<?php if($cnpj_sistema != ""){ ?> CNPJ <?php echo  $cnpj_sistema  ?><?php } ?><br />
			Contato: <?php echo $telefone_sistema ?> 
		</th>
	</tr>

	<tr >
		<th colspan="3">Cliente <?php echo $nome_cliente ?> - Data: <?php echo $data_lancF ?>			
			<br>
			Venda: <?php echo $id ?> - <?php if($cancelada == 'Sim'){
				echo 'CANCELADA';
			}else{ ?>Pago : <?php echo $pago ?> <?php } ?>
			
			
		</th>
	</tr>
	
	<tr>
		<th class="ttu margem-superior" colspan="3">
			Comprovante de Venda
			
		</th>
	</tr>
	<tr>
		<?php if($garantia_venda != ''){ ?>
			<th colspan="3">
			Garantia de <?php echo $garantia_venda ?> Dias
			</th>
		<?php }else{ ?>
		<th colspan="3">
			CUMPOM NÃO FISCAL
			
		</th>
	<?php } ?>
	</tr>
	
	<tbody>

		<?php 

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

			?>

			<tr>
				
					<td colspan="2" width="70%"> <?php echo $quantidade ?> - <?php echo $nome_produto ?>
					</td>
				

				<td align="right">R$ <?php
				@$total_item;
				@$sub_tot = @$valor;
				@$sub_total = $sub_tot - $desconto;

				$total_itens += $total_item;
				
				$sub_tot = number_format( $sub_tot , 2, ',', '.');
				$sub_total = number_format( $sub_total , 2, ',', '.');
				$total_item = number_format( $total_item , 2, ',', '.');
				$total_itensF = number_format( $total_itens , 2, ',', '.');
				// $total = number_format( $cp1 , 2, ',', '.');


				

				echo $total_item ;
				?></td>
			</tr>

		<?php } ?>

				
	</tbody>
	<tfoot>

		<tfoot>

		<tr>
			<th class="ttu"  colspan="3" class="cor">
			<!-- _ _	_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ -->
			</th>
		</tr>	
		
		<?php if($desconto != 0 and $desconto != ""){ ?>
		<tr>
			<td colspan="2">Total</td>
			<td align="right">R$ <?php echo $total_itensF ?></td>
		</tr>
		<?php } ?>

		<?php if($desconto != 0 and $desconto != ""){ ?>
		<tr>
			<td colspan="2">Desconto</td>
			<td align="right">R$ <?php echo $descontoF ?></td>
		</tr>
		<?php } ?>

		
		</tr>

			<tr>
			<td colspan="2">SubTotal</td>
			<td align="right">R$ <?php echo $valorF ?></td>
		</tr>	

		<?php if($troco != 0 and $troco != ""){ ?>
		<tr>
			<td colspan="2">Valor Recebido</td>
			<td align="right">R$ <?php echo $trocoF ?></td>
		</tr>
		<?php } ?>

		<?php if($total_troco != 0){ ?>
		<tr>
			<td colspan="2">Troco</td>
			<td align="right">R$ <?php echo $total_trocoF ?></td>
		</tr>
		<?php } ?>	

		<tr>
			<th class="ttu" colspan="3" class="cor">
			<!-- _ _	_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ -->
			</th>
		</tr>	

		
		<tr>
			<td colspan="2">Forma de Pagamento</td>
			<td align="right"><?php echo $saida ?></td>
		</tr>

		<tr>
			<td colspan="2">Vendedor</td>
			<td align="right"><?php echo $nome_usu_lanc ?></td>
		</tr>

		<?php if($pago == 'Não'){ ?>
		<tr>
			<td colspan="2">Data de Pagamento</td>
			<td align="right"><?php echo $data_vencF ?></td>
		</tr>
		<?php } ?>

	</tfoot>
</table>

<?php if($pago == 'Não'){ ?>
<br><br>
<div align="center">__________________________</div>
<div align="center"><small>Assinatura do Cliente</small></div>
<?php } ?>