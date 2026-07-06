<?php
require_once("../../../conexao.php");
$pagina = 'receber';
$data_atual = date('Y-m-d');

@session_start();
$id_usuario = @$_SESSION['id_ref'];

$total_valor = 0;
$total_valorF = 0;


$status = '%' . @$_POST['p1'] . '%';

//PEGAR O TOTAL DAS CONTAS A PAGAR VENCIDAS
$query = $pdo->query("SELECT * from $pagina where pago = 'Não' and cliente = '$id_usuario' and data_venc < curDate()");
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


$query = $pdo->query("SELECT * from $pagina WHERE pago LIKE '$status' and cliente = '$id_usuario' order by id desc ");

echo <<<HTML
<small>
HTML;
$total_pago = 0;
$total_pendentes = 0;
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	echo <<<HTML
	<table class="table table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
			<tr> 				
				<th>Descrição</th>
				<th class="esc">Tipo</th>
				<th class="esc">Valor</th> 
				<th class="esc">Lançamento</th> 
				<th class="esc">Vencimento</th> 
				<th class="esc">Pagamento</th>				
				<th>Nota</th>				
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

		$data_lancF = implode('/', array_reverse(explode('-', $data_lanc)));
		$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
		$data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));
		$valorF = number_format($valor, 2, ',', '.');

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
		$tipo_pessoa = 'Pessoa';
		$pix_pessoa = 'Sem Registro';
		$tel_pessoa = 'Sem Registro';

		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_pessoa = $res2[0]['nome'];
			$tipo_pessoa = 'Cliente';
			$tel_pessoa = $res2[0]['telefone'];
		}


		if ($pago == 'Sim') {
			$classe_pago = 'verde';
			$cor_pago = '#198754';
			$ocultar = 'ocultar';
			$total_pago += $valor;
		} else {
			$classe_pago = 'text-danger';
			$cor_pago = '#dc3545';
			$ocultar = '';
			$total_pendentes += $valor;
		}


		//PEGAR RESIDUOS DA CONTA
		$total_resid = 0;
		$valor_com_residuos = 0;
		$query2 = $pdo->query("SELECT * FROM valor_parcial WHERE id_conta = '$id' and tipo = 'Receber'");
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

		if ($referencia == 'Venda') {
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

		if ($data_pgtoF == '00/00/0000') {
			$data_pgtoF = 'Pendente';
		}
		echo <<<HTML
			<tr> 
				<td><i class="fa fa-square {$classe_pago} mr-1"></i> {$descricao}</td> 
				<td class="esc">{$referencia}</td>
				<td class="esc" style="color: {$cor_pago}; font-weight:bold">R$ {$valorF} <small><a href="#" onclick="mostrarResiduos('{$id}')" class="text-danger" title="Ver Resíduos">{$vlr_antigo_conta}</a></small></td>	
				<td class="esc">{$data_lancF}</td>
				<td class="esc">{$data_vencF}</td>
				<td class="esc">{$data_pgtoF}</td>
				
				<td><a href="../painel/images/notas/{$arquivo}" target="_blank"><img class="hovv" src="../painel/images/notas/{$tumb_arquivo}" width="30px" height="30px"></a></td>

				<td>
					
					<a class="btn btn-warning btn-sm" href="#" onclick="mostrar('{$id}', '{$descricao}', '{$nome_pessoa}', '{$tipo_pessoa}','{$valorF}','{$data_lancF}','{$data_vencF}','{$data_pgtoF}','{$nome_usu_lanc}','{$nome_usu_pgto}','{$nome_frequencia}','{$saida}','{$arquivo}','{$pago}','{$obs}','{$pix_pessoa}','{$tel_pessoa}')" title="Ver Dados"><i class="fa fa-info-circle"></i></a>

					<a class="btn btn-dark btn-sm" href="#" onclick="arquivo('{$id}', '{$descricao}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o"></i></a>

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
</small>
HTML;
} else {
	echo 'Não Encontrei Nenhuma Conta para este Status!';
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

	});




	function mostrar(id, descricao, pessoa, tipo_pessoa, valor, data_lanc, data_venc, data_pgto, usuario_lanc, usuario_pgto, frequencia, saida, arquivo, pago, obs, pix, tel) {


		if (data_pgto == "00/00/0000") {
			data_pgto = 'Não Pago Ainda';
		}

		$('#pessoa_mostrar').text(pessoa);
		$('#tipo_pessoa_mostrar').text(tipo_pessoa);


		$('#nome_mostrar').text(descricao);
		$('#tel_mostrar').text(tel);
		$('#valor_mostrar').text(valor);
		$('#lanc_mostrar').text(data_lanc);
		$('#venc_mostrar').text(data_venc);
		$('#pgto_mostrar').text(data_pgto);
		$('#usu_lanc_mostrar').text(usuario_lanc);
		$('#usu_pgto_mostrar').text(usuario_pgto);
		$('#freq_mostrar').text(frequencia);
		$('#saida_mostrar').text(saida);
		$('#pago_mostrar').text(pago);
		$('#obs_mostrar').text(obs);

		$('#pix_mostrar').text(pix);

		$('#link_arquivo').attr('href', '../painel/images/notas/' + arquivo);


		$('#modalMostrar').modal('show');

		resultado = arquivo.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target_mostrar').attr('src', "../painel/images/pdf.png");
			return;
		} else if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target_mostrar').attr('src', "../painel/images/rar.png");
			return;
		} else if (resultado[1] === 'doc' || resultado[1] === 'docx') {
			$('#target_mostrar').attr('src', "../painel/images/word.png");
			return;
		} else if (resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls') {
			$('#target_mostrar').attr('src', "../painel/images/excel.png");
			return;
		} else if (resultado[1] === 'txt') {
			$('#target_mostrar').attr('src', "../painel/images/txt.png");
			return;
		} else if (resultado[1] === 'xml') {
			$('#target_mostrar').attr('src', "../painel/images/xml.png");
			return;
		} else {
			$('#target_mostrar').attr('src', '../painel/images/notas/' + arquivo);
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
		$('#target').attr('src', '../painel/images/notas/sem-foto.png');
		$('#obs').val('');
		$('#cliente').val('').change();

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






	function arquivo(id, nome) {
		$('#id-arquivo').val(id);
		$('#nome-arquivo').text(nome);
		$('#modalArquivos').modal('show');
		$('#mensagem-arquivo').text('');
		listarArquivos();
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
</script>