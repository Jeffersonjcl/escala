<?php

use function PHPSTORM_META\type;

$tabela = 'receber';
require_once("../../../conexao.php");

//PEGAR A DATA DE HJE
$data_atual = date('Y-m-d');

$id = @$_POST['id'];

$total_pago = 0;
$total_pendentes = 0;
$total_vencidas = 0;
$total_pendentesF = 0;
$total_vencidasF = 0;
$query = $pdo->query("SELECT * from $tabela where cliente = '$id' and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	echo <<<HTML
<small><small>
	<table class="table table-bordered" id="tabela3">
	<thead> 
	<tr> 
	<th>Descrição</th>
	<th>Valor</th>	
	<th>Vencimento</th>		
	<th>Baixar</th>				
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $linhas; $i++) {
		$id_conta = $res[$i]['id'];
		$descricao = $res[$i]['descricao'];
		$valor = $res[$i]['valor'];
		$data_venc = $res[$i]['data_venc'];
		$pago = $res[$i]['pago'];
		$saida = $res[$i]['saida'];

		$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
		$valorF = number_format($valor, 2, ',', '.');
		$valorFF = str_replace('.', ',', $valor);

		if ($pago == 'Sim') {
			$classe_pago = 'table-success';
			$total_pago += $valor;
			$classe_baixar = 'ocultar';
		} else {
			$classe_pago = 'table-danger text-danger';

			$total_pendentes += $valor;
			$classe_baixar = '';
		}

		//COMPARAR DATA strtotime($data_venc) < strtotime($data_atual)
		$debito = '';
		if ($pago != 'Sim' and strtotime($data_venc) < strtotime($data_atual)) {
			$total_vencidas += $valor;
			$debito = 'table-danger';
		}

		$total_pagoF = number_format($total_pago, 2, ',', '.');
		$total_pendentesF = number_format($total_pendentes, 2, ',', '.');
		$total_vencidasF = number_format($total_vencidas, 2, ',', '.');

		echo <<<HTML
<tr class="{$debito}">
<td ><i class="fa fa-square {$classe_pago} mr-1"></i> {$descricao}</td>
<td>R$ {$valorF}</td>
<td>{$data_vencF}</td>
<td>
<big><a class="{$classe_baixar}" href="#" onclick="baixar('{$id_conta}', '{$valorFF}', '{$descricao}', '{$saida}')" title="Baixar Conta"><i class="fa fa-check-square " style="color:#079934"></i></a></big>
</td>
</tr>
HTML;
	}
} else {
	echo '<small>Não possui nenhuma conta!</small>';
}


echo <<<HTML
</tbody>
</table>
</small></small>
<br>
	<div align="right" style="font-size: 14px; font-weight: bold;"><span>Total Pendentes: <span class="text-verde">R$ {$total_pendentesF}</span></span> 
	<span style="margin-left: 25px">Total Vencidas: <span class="text-danger">R$ {$total_vencidasF}</span></span>
	</div>
HTML;
?>


<script type="text/javascript">

	$(document).ready(function() {
		$('#tabela3').DataTable({
			"language": {
				//"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
			},
			"ordering": false,
			"stateSave": true
		});
	});


	function baixar(id, valor, descricao, saida) {
		$('#id-baixar').val(id);
		$('#descricao-baixar').text(descricao);
		$('#valor-baixar').val(valor);
		$('#saida-baixar').val(saida).change();
		$('#subtotal').val(valor);


		$('#valor-juros').val('');
		$('#valor-desconto').val('');
		$('#valor-multa').val('');

		$('#modalBaixar').modal('show');
		$('#mensagem-baixar').text('');
	}

</script>