<?php
require_once("../../../conexao.php");
$pagina = 'pagar';
$data_atual = date('Y-m-d');

$total_valor = 0;
$total_valorF = 0;
$total_total = 0;
$total_totalF = 0;
$total_vencidas = 0;
$total_vencidasF = 0;
$total_hoje = 0;
$total_hojeF = 0;
$total_amanha = 0;
$total_amanhaF = 0;
$total_recebidas = 0;
$total_recebidasF = 0;

$filtro = @$_POST['p1'];
$dataInicial = @$_POST['p2'];
$dataFinal = @$_POST['p3'];

if ($dataInicial == "") {
	$dataInicial = $data_atual;
}

if ($dataFinal == "") {
	$dataFinal = $data_atual;
}

//PEGAR O TOTAL DAS CONTAS A PAGAR PENDENTES
$query = $pdo->query("SELECT * from $pagina where pago = 'Não'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$total_valor += $res[$i]['valor'];
		$total_valorF = number_format($total_valor, 2, ',', '.');
	}
}

//PEGAR O TOTAL DAS CONTAS A PAGAR
$query = $pdo->query("SELECT * from $pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$total_total += $res[$i]['valor'];
		$total_totalF = number_format($total_total, 2, ',', '.');
	}
}

//PEGAR O TOTAL DAS CONTAS A PAGAR PEDENTES
$query = $pdo->query("SELECT * from $pagina where data_venc < curDate() and pago = 'Não'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$total_vencidas += $res[$i]['valor'];
		$total_vencidasF = number_format($total_vencidas, 2, ',', '.');
	}
}

//PEGAR O TOTAL DAS CONTAS A PAGAR QUE VENCE HOJE
$query = $pdo->query("SELECT * from $pagina where data_venc = curDate() and pago = 'Não'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_am = @count($res);
if ($total_am > 0) {
	for ($i = 0; $i < $total_am; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$total_hoje += $res[$i]['valor'];
		$total_hojeF = number_format($total_hoje, 2, ',', '.');
	}
}

//PEGAR O TOTAL DAS CONTAS A PAGAR RECEBIDAS
$query = $pdo->query("SELECT * from $pagina where pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_pg = @count($res);
if ($total_pg > 0) {
	for ($i = 0; $i < $total_pg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$total_recebidas += $res[$i]['valor'];
		$total_recebidasF = number_format($total_recebidas, 2, ',', '.');
	}
}



$data_hoje = date('Y-m-d');
$data_amanha = date('Y/m/d', strtotime("+1 days", strtotime($data_hoje)));


//PEGAR O TOTAL DAS CONTAS A PAGAR QUE VENCE AMANHÃ
$query = $pdo->query("SELECT * from $pagina where data_venc = '$data_amanha' and pago = 'Não'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_am = @count($res);
if ($total_am > 0) {
	for ($i = 0; $i < $total_am; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$total_amanha += $res[$i]['valor'];
		$total_amanhaF = number_format($total_amanha, 2, ',', '.');
	}
}




if ($filtro == 'Vencidas') {
	$query = $pdo->query("SELECT * from $pagina where data_venc < curDate() and pago = 'Não' order by id desc ");
} else if ($filtro == 'Recebidas') {
	$query = $pdo->query("SELECT * from $pagina where pago = 'Sim' order by id desc ");
} else if ($filtro == 'Hoje') {
	$query = $pdo->query("SELECT * from $pagina where data_venc = curDate() and pago = 'Não' order by id desc ");
} else if ($filtro == 'Amanha') {
	$query = $pdo->query("SELECT * from $pagina where data_venc = '$data_amanha' and pago = 'Não' order by id desc ");
} else if ($filtro == 'Todas') {
	$query = $pdo->query("SELECT * from $pagina order by id desc ");
} else {
	$query = $pdo->query("SELECT * from $pagina WHERE data_venc >= '$dataInicial' and data_venc <= '$dataFinal' order by id desc ");
}

echo <<<HTML

HTML;
$total_pago = 0;
$total_pendentes = 0;
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	echo <<<HTML
	<table class="table table-borderless table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
<tr> 				
	<th>Descrição</th>
	<th>Loja</th>
	<th>Data Lanç.</th>
	<th>Valor</th>
	<th>Arquivo</th>
	<th>Ações</th>
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
		$valor = $res[$i]['valor'];
		$data_lanc = $res[$i]['data_lanc'];
		$data_venc = $res[$i]['data_venc'];
		$data_pgto = $res[$i]['data_pgto'];
		$usuario_lanc = $res[$i]['usuario_lanc'];
		$usuario_pgto = $res[$i]['usuario_pgto'];
		$frequencia = $res[$i]['frequencia'];
		$saida = $res[$i]['saida'];
		$arquivo = $res[$i]['arquivo'];
		$pago = $res[$i]['pago'];
		$obs = $res[$i]['obs'];
		$funcionario = $res[$i]['funcionario'];
		$fornecedor = $res[$i]['fornecedor'];
		$referencia = $res[$i]['referencia'];

		//extensão do arquivo
		$ext = pathinfo($arquivo, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png';
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';
		} else if ($ext == 'doc' || $ext == 'docx') {
			$tumb_arquivo = 'word.png';
		} else if ($ext == 'xlsx' || $ext == 'xlsm' || $ext == 'xls') {
			$tumb_arquivo = 'excel.png';
		} else if ($ext == 'txt') {
			$tumb_arquivo = 'txt.png';
		} else if ($ext == 'xml') {
			$tumb_arquivo = 'xml.png';
		} else {
			$tumb_arquivo = $arquivo;
		}

		$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
		$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
		$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
		$valorF = number_format($valor, 2, ',', '.');
		$valorB = str_replace('.', ',', $valor);


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_usu_lanc = $res2[0]['nome'];
		} else {
			$nome_usu_lanc = 'Sem Usuário';
		}


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_pgto'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_usu_pgto = $res2[0]['nome'];
		} else {
			$nome_usu_pgto = 'Sem Usuário';
		}


		$query2 = $pdo->query("SELECT * FROM frequencias where dias = '$frequencia'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_frequencia = $res2[0]['frequencia'];
		} else {
			$nome_frequencia = 'Única';
		}

		$nome_pessoa = 'Sem Registro';
		$tipo_pessoa = 'Sem Registro';
		$tel_pessoa = 'Sem Registro';
		

		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_pessoa = $res2[0]['nome'];
			$tipo_pessoa = 'Cliente';
			$tel_pessoa = $res2[0]['telefone'];
		}

		$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$fornecedor'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_pessoa = $res2[0]['nome'];
			$pix_pessoa = $res2[0]['pix'];
			$tipo_pessoa = 'Loja';
			$tel_pessoa = $res2[0]['telefone'];
		}

		if ($pix_pessoa == '') {
			$pix_pessoa = 'Sem Registro';
		}

		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_pessoa = $res2[0]['nome'];
			$tipo_pessoa = 'Funcionário';
			$tel_pessoa = $res2[0]['telefone'];
		}

		if ($pago == 'Sim') {
			$classe_pago = 'verde';
			$classe_pago2 = 'table-success';
			$ocultar = 'ocultar';
			$ocultar_pendentes = '';
			$cor_status = 'Green';
			$total_pago += $valor;
		} else if ($pago == 'Não' and $data_venc > $data_atual) {
			$classe_pago2 = '';
			$classe_pago = 'text-danger';
			$ocultar = '';
			$ocultar_pendentes = 'ocultar';
			$cor_status = 'Red';
			$total_pago += $valor;
		} else {
			$classe_pago = 'text-danger';
			$classe_pago2 = 'table-danger';
			$ocultar = '';
			$ocultar_pendentes = 'ocultar';
			$cor_status = 'Red';
			$total_pendentes += $valor;
		}

		//PEGAR RESIDUOS DA CONTA
		$total_resid = 0;
		$valor_com_residuos = 0;
		$query2 = $pdo->query("SELECT * FROM valor_parcial WHERE id_conta = '$id' and tipo = 'Pagar'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {

			$descricao = '(Resíduo) - ' . $descricao;

			for ($i2 = 0; $i2 < @count($res2); $i2++) {
				foreach ($res2[$i2] as $key => $value) {
				}
				$id_res = $res2[$i2]['id'];
				$valor_resid = $res2[$i2]['valor'];
				$total_resid += $valor_resid;
			}


			$valor_com_residuos = $valor + $total_resid;
		}
		if ($valor_com_residuos > 0) {
			$vlr_antigo_conta = '(' . $valor_com_residuos . ')';
			$descricao_link = '';
			$descricao_texto = 'd-none';
		} else {
			$vlr_antigo_conta = '';
			$descricao_link = 'd-none';
			$descricao_texto = '';
		}

		$total_pagoF = number_format($total_pago, 2, ',', '.');
		$total_pendentesF = number_format($total_pendentes, 2, ',', '.');

		if ($referencia == 'Nota') {
			$ocultar_excluir = 'ocultar';
		} else {
			$ocultar_excluir = '';
		}

		if ($tel_pessoa == "Sem Registro") {
			$ocultar_whats = 'ocultar';
		} else {
			$ocultar_whats = '';
		}

		$tel_pessoaF = '55' . preg_replace('/[ ()-]+/', '', $tel_pessoa);


		echo <<<HTML


		<tr class="{$classe_pago2}">

		<td><i class="fa fa-square {$classe_pago} mr-1"></i> {$descricao}</td>
		<td>{$nome_pessoa}</td>
		<td>{$data_vencF}</td>
		<td style="color: {$cor_status}; font-weight:bold"> R$ {$valorF} <small><a href="#" onclick="mostrarResiduos('{$id}')" class="text-danger" title="Ver Resíduos">{$vlr_antigo_conta}</a></small></td>
		<td><a href="images/notas/{$arquivo}" target="_blank"><img class="hovv" src="images/notas/{$tumb_arquivo}" width="30px" height="30px"></a></td>

		<td>

			<a class="btn btn-success btn-sm {$ocultar}"  href="#" onclick="baixar('{$id}', '{$valorB}', '{$descricao}', '{$saida}')" title="Baixar Conta"><i class="bi bi-check-square-fill"></i></a>
				
			<a class="btn btn-info btn-sm {$ocultar}" href="#" onclick="editar('{$id}', '{$descricao}', '{$cliente}','{$valorF}','{$data_venc}','{$frequencia}','{$saida}','{$arquivo}', '{$funcionario}', '{$obs}','{$fornecedor}','{$data_pgto}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

			<a class="btn btn-warning btn-sm" href="#" onclick="mostrar('{$id}', '{$descricao}','{$nome_pessoa}','{$tipo_pessoa}','{$tel_pessoa}','{$valorF}','{$data_lancF}','{$data_vencF}','{$data_pgtoF}','{$nome_usu_lanc}','{$nome_usu_pgto}','{$nome_frequencia}','{$saida}','{$arquivo}','{$pago}','{$obs}','{$pix_pessoa}')" title="Ver Dados"><i class="fa fa-info-circle"></i></a>

			<a class="btn btn-secondary btn-sm {$ocultar}"  href="#" onclick="parcelar('{$id}', '{$valorF}', '{$descricao}')" title="Parcelar Conta"><i class="fa fa-calendar-o " ></i></a>


			<a class="btn btn-dark btn-sm" href="#" onclick="arquivo('{$id}', '{$descricao}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o "></i></a>

			<a class="btn btn-success btn-sm {$ocultar_whats}" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_pessoaF}" title="Whatsapp" target="_blank"><i class="bi bi-whatsapp "></i></a>


			<form method="POST" action="rel/recibo_conta_pagar_class.php" target="_blank" style="display:inline-block">
				<input type="hidden" name="id" value="{$id}">
				<button class="btn btn-danger btn-sm {$ocultar_pendentes} " title="PDF do Recibo Conta"><i class="fa fa-file-pdf-o " style="color:#FFF"></i></button>
			</form>


			<!-- <form method="POST" action="rel/imp_recibo_pagar.php" target="_blank" style="display:inline-block">
				<input type="hidden" name="id" value="{$id}">
				<button class="btn btn-secondary btn-sm {$ocultar_pendentes} " title="Imprimir Recibo 80mmm" ><i class="fa fa-print " style="color:#FFF"></i></button>
			</form> -->


			<div class="dropdown" style="display: inline-block;">                      
				<a class="btn btn-danger btn-sm {$ocultar_excluir}" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown" title="Excluir Pagamento"><i class="fa fa-trash-can"></i> </a>
				<div  class="dropdown-menu tx-13">
					<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
						<p>Confirmar Exclusão? <a href="#" onclick="excluirConta('{$id}')"><span class="text-danger"><button class="btn-danger">Sim</button></span></a></p>
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
	<br>
	<div align="right"><span>Total Pendentes: <span class="text-danger">{$total_pendentesF}</span></span> <span style="margin-left: 25px">Total Pago: <span class="verde">{$total_pagoF}</span></span></div>

HTML;
} else {
	echo 'Não encontrei nenhum Pagamento para esta data ou período!';
}

?>


<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true,
		});
		$('#tabela_filter label input').focus();
		$('#total_itens').text('R$ <?= $total_valorF ?>');
		$('#total_total').text('R$ <?= $total_totalF ?>');
		$('#total_vencidas').text('R$ <?= $total_vencidasF ?>');
		$('#total_hoje').text('R$ <?= $total_hojeF ?>');
		$('#total_amanha').text('R$ <?= $total_amanhaF ?>');
		$('#total_recebidas').text('R$ <?= $total_recebidasF ?>');
	});



	function editar(id, descricao, cliente, valor, data_venc, frequencia, saida, arquivo, funcionario, obs, fornecedor, data_pgto) {

		if (cliente == 0) {
			cliente = "";
		}

		if (funcionario == 0) {
			funcionario = "";
		}

		if (fornecedor == 0) {
			fornecedor = "";
		}



		$('#id').val(id);
		$('#descricao').val(descricao);
		$('#cliente').val(cliente).change();
		$('#valor').val(valor);
		$('#data_venc').val(data_venc);
		$('#data_pgto').val(data_pgto);
		$('#frequencia').val(frequencia).change();
		$('#saida').val(saida).change();
		$('#funcionario').val(funcionario).change();
		$('#fornecedor').val(fornecedor).change();
		$('#obs').val(obs);
		$('#foto').val('');

		$('#arquivo').val('');


		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#mensagem').text('');

		resultado = arquivo.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target').attr('src', "images/pdf.png");
			return;
		} else if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target').attr('src', "images/rar.png");
			return;
		} else if (resultado[1] === 'doc' || resultado[1] === 'docx') {
			$('#target').attr('src', "images/word.png");
			return;
		} else if (resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls') {
			$('#target').attr('src', "images/excel.png");
			return;
		} else if (resultado[1] === 'txt') {
			$('#target').attr('src', "images/txt.png");
			return;
		} else if (resultado[1] === 'xml') {
			$('#target').attr('src', "images/xml.png");
			return;	
		} else {
			$('#target').attr('src', 'images/notas/' + arquivo);
			return;
		}

	}



	function mostrar(id, descricao, pessoa, tipo_pessoa, tel, valor, data_lanc, data_venc, data_pgto, usuario_lanc, usuario_pgto, frequencia, saida, arquivo, pago, obs, pix) {

		if (data_pgto == "00/00/0000") {
			data_pgto = 'Pagamento não efetuado';
		}

		$('#id_mostrar').text(id);

		$('#descricao_mostrar').text(descricao);
		$('#pessoa_mostrar').text(pessoa);
		$('#tipo_pessoa_mostrar').text(tipo_pessoa);
		$('#tel_mostrar').text(tel);
		$('#valor_mostrar').text('R$ ' + valor);
		$('#lanc_mostrar').text(data_lanc);
		$('#venc_mostrar').text(data_venc);
		$('#pgto_mostrar').text(data_pgto);
		$('#usu_lanc_mostrar').text(usuario_lanc);
		$('#usu_pgto_mostrar').text(usuario_pgto);
		$('#freq_mostrar').text(frequencia);
		$('#saida_mostrar').text(saida);
		$('#link_arquivo').attr('href', 'images/notas/' + arquivo);
		$('#pago_mostrar').text(pago);
		$('#obs_mostrar').text(obs);

		$('#pix_mostrar').text(pix);

		$('#modalMostrar').modal('show');

		resultado = arquivo.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target_mostrar').attr('src', "images/pdf.png");
			return;
		} else if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target_mostrar').attr('src', "images/rar.png");
			return;
		} else if (resultado[1] === 'doc' || resultado[1] === 'docx') {
			$('#target_mostrar').attr('src', "images/word.png");
			return;
		} else if (resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls') {
			$('#target_mostrar').attr('src', "images/excel.png");
			return;
		} else if (resultado[1] === 'txt') {
			$('#target_mostrar').attr('src', "images/txt.png");
			return;
		} else if (resultado[1] === 'xml') {
			$('#target_mostrar').attr('src', "images/xml.png");
			return;
		} else {
			$('#target_mostrar').attr('src', 'images/notas/' + arquivo);
			return;
		}

	}

	function limparCampos() {
		$('#id').val('');
		$('#descricao').val('');
		$('#valor').val('');
		$('#data_pgto').val('');
		$('#data_venc').val('<?= $data_atual ?>');
		$('#arquivo').val('');
		$('#target').attr('src', 'images/notas/sem-foto.png');
		$('#obs').val('');
		$('#cliente').val('').change();
		$('#funcionario').val('').change();
		$('#fornecedor').val('').change();
	}


	function parcelar(id, valor, nome) {
		$('#id-parcelar').val(id);
		$('#valor-parcelar').val(valor);
		$('#qtd-parcelar').val('');
		$('#nome-parcelar').text(nome);
		$('#nome-input-parcelar').val(nome);
		$('#modalParcelar').modal('show');
		$('#mensagem-parcelar').text('');
	}


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



	function mostrarResiduos(id) {

		$.ajax({
			url: 'paginas/' + pag + "/listar-residuos.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "html",

			success: function(result) {
				$("#listar-residuos").html(result);
			}
		});
		$('#modalResiduos').modal('show');


	}


	function arquivo(id, nome) {
		$('#id-arquivo').val(id);
		$('#nome-arquivo').text(nome);
		$('#modalArquivos').modal('show');
		$('#mensagem-arquivo').text('');
		listarArquivos();
	}
</script>