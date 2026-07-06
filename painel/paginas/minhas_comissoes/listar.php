<?php
@session_start();
require_once("../../../conexao.php");

$tabela = 'receber';
$data_hoje = date('Y-m-d');

$id_usuario = @$_SESSION['id'];

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$funcionario = $id_usuario;

if ($dataInicial == "") {
	$dataInicial = $data_hoje;
}

if ($dataFinal == "") {
	$dataFinal = $data_hoje;
}

$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);
if ($total_reg2 > 0) {
	$nome_func2 = $res2[0]['nome'];
} else {
	$nome_func2 = 'Sem Referência!';
}

$total_comissao = 0;

$query = $pdo->query("SELECT * FROM $tabela where data_pgto >= '$dataInicial' and data_pgto <= '$dataFinal' and pago = 'Sim' ORDER BY descricao asc, data_lanc asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead>
	<tr>
	<th>Nota</th>
	<th>Cliente</th>
	<th>Loja</th>
	<th>Valor Total da Nota</th> 	
	<th>% da Comissão</th>
	<th>Valor da Comissão</th>
	<th>Data da Baixa Financeira</th>	
	<!-- <th>Ações</th> -->
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$id = $res[$i]['id'];
		$descricao = $res[$i]['descricao'];
		$cliente = $res[$i]['cliente'];
		$fornecedor = $res[$i]['fornecedor'];
		$tipo = $res[$i]['referencia'];
		$valor = $res[$i]['valor'];
		$data_lanc = $res[$i]['data_lanc'];
		$data_venc = $res[$i]['data_venc'];
		$data_pgto = $res[$i]['data_pgto'];
		$usuario_lanc = $res[$i]['usuario_lanc'];
		$usuario_baixa = $res[$i]['usuario_pgto'];
		$comissao = $res[$i]['comissao'];
		$porc_comissao = $res[$i]['porc_comissao'];
		$pago = $res[$i]['pago'];
		$foto = $res[$i]['arquivo'];


		$valorF = number_format($valor, 2, ',', '.');
		$comissaoF = number_format($comissao, 2, ',', '.');
		$porc_comissao = number_format($porc_comissao, 2, ',', '.'); 
		$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
		$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
		$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));


		$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$fornecedor'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_loja = $res2[0]['nome'];
		} else {
			$nome_loja = 'Loja Não Cadastrada';
		}


		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_cliente = $res2[0]['nome'];
		} else {
			$nome_cliente = 'Cliente Não Encontrado';
		}

		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_baixa'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_usuario_pgto = $res2[0]['nome'];
		} else {
			$nome_usuario_pgto = 'Nenhum!';
		}


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_usuario_lanc = $res2[0]['nome'];
		} else {
			$nome_usuario_lanc = 'Sem Referência!';
		}


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if ($total_reg2 > 0) {
			$nome_func = $res2[0]['nome'];
			$tel_func = $res2[0]['telefone'];
			$chave_pix_func = $res2[0]['chave_pix'];
		} else {
			$nome_func = 'Sem Referência!';
			$chave_pix_func = '';
			$tel_func = '';
		}


		$classe_alerta = 'verde';
		$total_comissao += $comissao;
		

		//extensão do arquivo
		$ext = pathinfo($foto, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png';
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';
		} else {
			$tumb_arquivo = $foto;
		}


		if ($data_venc < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro';
		} else {
			$classe_debito = '';
		}


		echo <<<HTML


		<tr class="{$classe_debito}">
		<td><i class="fa fa-square {$classe_alerta}"></i> {$descricao}</td>
		<td>{$nome_cliente}</td>
		<td>{$nome_loja}</td>
		<td>R$ {$valorF}</td>
		<td style="color: #198754; font-weight:bold">{$porc_comissao} %</td>
		<td style="color: #198754; font-weight:bold">R$ {$comissaoF}</td>
		<td>{$data_pgtoF}</td>
		<!-- <td>
		
		<a class="btn btn-warning btn-sm" href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancF}', '{$data_vencF}',  '{$data_pgtoF}', '{$nome_usuario_lanc}', '{$nome_usuario_pgto}', '{$nome_func}', '{$tel_func}', '{$chave_pix}')" title="Ver Dados"><i class="fa fa-info-circle"></i></a>

		<div class="dropdown" style="display: inline-block;">                      
			<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash-can"></i> </a>
			<div  class="dropdown-menu tx-13">
				<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
					<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger"><button class="btn-danger">Sim</button></span></a></p>
				</div>
			</div>
		</div>

		<div class="dropdown head-dpdn2" style="display: inline-block;" >                      
			<a class="btn btn-success btn-sm" title="Aprovar Orçamento" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-check-square"></i></a>
             <div  class="dropdown-menu tx-13">
                 <div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
                     <p>Confirmar Baixa na Conta? <a href="#" onclick="baixar('{$id}')"><span class="text-verde"><button class="btn-danger">Sim</button></span></a></p>
                 </div>
            </div>
        </div>

		</td> -->
		</tr>
HTML;
	}

	$total_comissaoF = number_format($total_comissao, 2, ',', '.');

	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>

<br>
<div align="right"><span style="margin-left: 25px">Valor Total da Comissão: <span style="color: #198754; font-weight:bold">R$ {$total_comissaoF}</span></span></div>

</small>
HTML;
} else {
	echo 'Não encontrei nenhuma Comissão para esta data ou período!';
}

?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>


<script type="text/javascript">
	function mostrar(descricao, valor, data_lanc, data_venc, data_pgto, usuario_lanc, usuario_pgto, func, tel, tipo_chave) {

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lanc_dados').text(data_lanc);
		$('#data_venc_dados').text(data_venc);
		$('#data_pgto_dados').text(data_pgto);
		$('#usuario_lanc_dados').text(usuario_lanc);
		$('#usuario_baixa_dados').text(usuario_pgto);
		$('#nome_func_dados').text(func);
		$('#telefone_dados').text(tel);
		$('#chave_pix_dados').text(tipo_chave);


		$('#modalDados').modal('show');
	}
</script>