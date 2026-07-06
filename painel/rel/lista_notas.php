<?php
include('../../conexao.php');

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$filtroCliente = $_GET['filtroCliente'];
$status = $_GET['status'];

include('data_formatada.php');

$dataInicialF = implode('/', array_reverse(@explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(@explode('-', $dataFinal)));
$datas = "";
if ($dataInicial == $dataFinal) {
	$datas = $dataInicialF;
} else {
	$datas = $dataInicialF . ' à ' . $dataFinalF;
}

$texto_filtro = 'Apurado em: ' . $datas;
$texto_titulo = '';

if ($status == 'Pendente') {
	$texto_titulo = ' PENDENTES';
}

if ($status == 'Aprovado') {
	$texto_titulo = ' APROVADOS';
}

if ($filtroCliente != '') {
	$sqlCliente = "and cliente = " . $filtroCliente;
} else {
	$sqlCliente = "";
}

?>
<!DOCTYPE html>
<html>

<head>

	<style>
		@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');

		@page {
			margin: 145px 20px 25px 20px;
		}

		#header {
			position: fixed;
			left: 0px;
			top: -110px;
			bottom: 100px;
			right: 0px;
			height: 35px;
			text-align: center;
			padding-bottom: 100px;
		}

		#content {
			margin-top: 0px;
		}

		#footer {
			position: fixed;
			left: 0px;
			bottom: -60px;
			right: 0px;
			height: 80px;
		}

		#footer .page:after {
			content: counter(page, my-sec-counter);
		}

		body {
			font-family: 'Tw Cen MT', sans-serif;
		}

		.marca {
			position: fixed;
			left: 50;
			top: 180;
			width: 80%;
			opacity: 8%;
			/* transform: rotate(-30deg); */
		}
		
	</style>

</head>

<body>

	<?php
	if ($marca_dagua == 'Sim') { ?>
		<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">
	<?php } ?>


	<div id="header">

		<div style="border-style: solid; font-size: 10px; height: 55px;">
			<table style="width: 100%; border: 0px solid #ccc;">
				<tr>
					<td style="border: 1px; width: 25%; text-align: left;">
						<img style="margin-top: 0px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="150px">
					</td>

					<td style="text-align: center; font-size: 10px; width: 50%;">

						<b><?php echo mb_strtoupper($nome_sistema) ?></b><br>
						CNPJ: <?php echo mb_strtoupper($cnpj_sistema) ?><br>
						INSTAGRAM: <b><a href="https://www.instagram.com/<?php echo mb_strtolower($instagram_sistema) ?>/" target="_blank"><?php echo mb_strtolower($instagram_sistema) ?></a></b><br>
						<!--EMAIL: <?php echo mb_strtolower($email_sistema) ?><br>-->
						<?php echo mb_convert_case($endereco_sistema, MB_CASE_TITLE, 'UTF-8') ?>

					</td>
					<td style="width: 30%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE NOTAS <?php echo $texto_titulo ?></big></b><br> <?php echo mb_strtoupper($texto_filtro) ?> <br> <?php echo mb_strtoupper($data_hoje) ?>
					</td>
				</tr>
			</table>
		</div>

		<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>

				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">

					<td style="width:10%">Nº CONTROLE</td>
					<td style="width:20%">CLIENTE</td>
					<td style="width:20%">LOJA</td>
					<td style="width:10%">DATA NOTA</td>
					<td style="width:10%">VALOR NOTA</td>
					<td style="width:10%">DATA CAD.</td>
					<td style="width:10%">HORA CAD.</td>
					<td style="width:10%">STATUS NOTA</td>

				</tr>
			</thead>
		</table>
	</div>

	<div id="footer" class="row">
		<hr style="margin-bottom: 0;">
		<table style="width:100%;">
			<tr style="width:100%;">
				<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
				<td style="width:40%; font-size: 10px; text-align: right;">
					<p class="page">Página </p>
				</td>
			</tr>
		</table>
	</div>

	<div id="content" style="margin-top: 0;">

		<table style="width: 100%; table-layout: fixed; font-size:9px; text-transform: uppercase;">
			<thead>
			<tbody>
				<?php
				$total_pago = 0;
				$total_faturado = 0;
				$total_pendente = 0;
				$total_pagoF = 0;
				$total_pendenteF = 0;
				$total_faturadoF = 0;
				$query = $pdo->query("SELECT * from notas WHERE data >= '$dataInicial' and data <= '$dataFinal' and status LIKE '%$status%' $sqlCliente order by id desc");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$linhas = @count($res);
				if ($linhas > 0) {
					for ($i = 0; $i < $linhas; $i++) {
						$id = $res[$i]['id'];
						$numero_nota = $res[$i]['numero_nota'];
						$cliente = $res[$i]['cliente'];
						$funcionario = $res[$i]['funcionario'];
						$fornecedor = $res[$i]['fornecedor'];
						$data = $res[$i]['data'];
						$hora = $res[$i]['hora'];
						$data_entrega = $res[$i]['data_entrega'];
						$valor = $res[$i]['valor'];
						$nota = $res[$i]['nota'];
						$status = $res[$i]['status'];
						$obs = $res[$i]['obs'];

						$dataF = implode('/', array_reverse(@explode('-', $data)));
						$data_entregaF = implode('/', array_reverse(@explode('-', $data_entrega)));

						$valorF = @number_format($valor, 2, ',', '.');
						
						$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
						$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						if (@count($res2) > 0) {
							$nome_usu_lanc = $res2[0]['nome'];
						} else {
							$nome_usu_lanc = 'Sem Usuário';
						}


						$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$fornecedor'");
						$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						if (@count($res2) > 0) {
							$nome_fornecedor = $res2[0]['nome'];
						} else {
							$nome_fornecedor = 'Não Lançado';
						}


						$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
						$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						if (@count($res2) > 0) {
							$nome_cliente = $res2[0]['nome'];
							$tel_cliente = $res2[0]['telefone'];
						}


						if ($status == 'Aprovada') {
							$cor_status = '#25958a';
							$classe_pago = 'verde.jpg';
							$total_pago += $valor;
						} else if ($status == 'Faturada') {
							$cor_status = '#198754';
							$classe_pago = 'verde-escuro.jpg';
							$total_faturado += $valor;
						} else if ($status == 'Pendente') {
							$cor_status = '#dc3545';
							$classe_pago = 'vermelho.jpg';
							$total_pendente += $valor;
						}


						$total_pagoF = @number_format($total_pago, 2, ',', '.');
						$total_faturadoF = @number_format($total_faturado, 2, ',', '.');
						$total_pendenteF = @number_format($total_pendente, 2, ',', '.');

						if ($tel_cliente == "Sem Registro") {
							$ocultar_whats = 'ocultar';
						} else {
							$ocultar_whats = '';
						}

						$tel_pessoaF = '55' . preg_replace('/[ ()-]+/', '', $tel_cliente);

						
						$ocultar_obs = '';
						$classe_obs = 'text-warning';
						if ($obs == "") {
							$ocultar_obs = 'ocultar';
							$classe_obs = 'text-primary';
						}

				?>


						<tr>

							<td style="width:10%">
								<img style="margin-top: 0px" src="<?php echo $url_sistema ?>painel/images/<?php echo $classe_pago ?>" width="8px">
								<?php echo $numero_nota ?>
							</td>
							<td style="width:20%"><?php echo $nome_cliente ?></td>
							<td style="width:20%"><?php echo $nome_fornecedor ?></td>
							<td style="width:10%"><?php echo $data_entregaF ?></td>
							<td style="width:10%; color: <?php echo $cor_status ?>; font-weight: bold;">R$ <?php echo $valorF ?></td>
							<td style="width:10%"><?php echo $dataF ?></td>
							<td style="width:10%"><?php echo $hora ?></td>
							<td style="width:10%; background-color: <?php echo $cor_status ?>; color: #ffffff"><?php echo $status ?></td>
							
						</tr>

				<?php }
				} ?>
			</tbody>

			</thead>
		</table>

	</div>

	<hr>

	<table>
		<thead>
		<tbody>
			<tr>

				<td style="font-size: 10px; width:440px; text-align: right;"><b>Total Faturado:<span style="color:green"> R$ <?php echo $total_faturadoF ?></span></td>
				<td style="font-size: 10px; width:150px; text-align: right;"><b>Total Aprovado:<span style="color:green"> R$ <?php echo $total_pagoF ?></span></td>
				<td style="font-size: 10px; width:150px; text-align: right;"><b>Total Pendente:<span style="color:red"> R$ <?php echo $total_pendenteF ?></span></td>

			</tr>
		</tbody>
		</thead>
	</table>

</body>

</html>