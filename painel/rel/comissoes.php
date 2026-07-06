<?php
include('../../conexao.php');
include('data_formatada.php');
$data_hoje = date('Y-m-d');
$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];

if ($dataInicial == "") {
	$dataInicial = $data_hoje;
}

if ($dataFinal == "") {
	$dataFinal = $data_hoje;
}


$dataInicialF = implode('/', array_reverse(@explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(@explode('-', $dataFinal)));


$datas = "";
if ($dataInicial == $dataFinal) {
	$datas = $dataInicialF;
} else {
	$datas = $dataInicialF . ' à ' . $dataFinalF;
}

$texto_filtro = 'PERÍODO APURADO: ' . $datas;

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

		<div style="border-style: solid; font-size: 10px; height: 55px; margin-bottom:10px;">
			<table style="width: 100%; border: 0px solid #ccc;">
				<tr>
					<td style="border: 1px; width: 20%; text-align: left;">
						<img style="margin-top: 0px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="150px">
					</td>

					<td style="text-align: center; font-size: 10px; width: 60%;">

						<b><?php echo mb_strtoupper($nome_sistema) ?></b><br>
						CNPJ: <?php echo mb_strtoupper($cnpj_sistema) ?><br>
						INSTAGRAM: <b><a href="https://www.instagram.com/<?php echo mb_strtolower($instagram_sistema) ?>/" target="_blank"><?php echo mb_strtolower($instagram_sistema) ?></a></b><br>
						<!--EMAIL: <?php echo mb_strtolower($email_sistema) ?><br>-->
						<?php echo mb_convert_case($endereco_sistema, MB_CASE_TITLE, 'UTF-8') ?>

					</td>
					<td style="width: 40%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE COMISSÕES </big></b><br> <?php echo @mb_strtoupper($texto_filtro) ?> <br> <?php echo @mb_strtoupper($data_hojeF) ?>
					</td>
				</tr>
			</table>
		</div>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 9px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>

				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">

					<td style="width:15%">Nº CONTROLE</td>
					<td style="width:30%">NOME DA LOJA</td>
					<td style="width:15%">DATA DA BAIXA</td>
					<td style="width:15%">VALOR DA NOTA</td>
					<td style="width:10%">% COMISSÃO</td>
					<td style="width:15%">VALOR COMISSÃO</td>

				</tr>

			</thead>
		</table>

	</div>

	<div id="footer" class="row">
		<hr style="margin-bottom: 0;">
		<table style="width:100%;">
			<tr style="width:100%;">
				<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> - Telefone: <?php echo $telefone_sistema ?></td>
				<td style="width:40%; font-size: 10px; text-align: right;">
					<p class="page">Página </p>
				</td>
			</tr>
		</table>
	</div>


	<div id="content" style="margin-top: -15px;">

		<table style="width: 100%; table-layout: fixed; font-size:10px; text-transform: uppercase;">
			<thead>
			<tbody>
				<?php
				$total_pagas = 0;
				$total_pagasF = 0;

				$pendentes = 0;
				$pagas = 0;
				$query = $pdo->query("SELECT * FROM receber where data_pgto >= '$dataInicial' and data_pgto <= '$dataFinal' and comissao > 0 order by data_venc asc");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$linhas = @count($res);
				if ($linhas > 0) {
					for ($i = 0; $i < $linhas; $i++) {
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
						$fornecedor = $res[$i]['fornecedor'];
						$comissao = $res[$i]['comissao'];
						$porc_comissao = $res[$i]['porc_comissao'];
						$id_ref = $res[$i]['id_ref'];


						$valorF = number_format($comissao, 2, ',', '.');
						$porc_comissaoF = number_format($porc_comissao, 2, ',', '.');
						$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
						$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
						$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));


						$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$fornecedor'");
						$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						if (@count($res2) > 0) {
							$nome_fornecedor = $res2[0]['nome'];
						}


						if ($pago == 'Sim') {
							$classe_pago = 'verde-escuro.jpg';
							$cor = '#198754';
							$total_pagas += $comissao;
							$pagas += 1;
						}


						$total_pagasF = number_format($total_pagas, 2, ',', '.');


						if ($data_pgtoF == '00/00/0000') {
							$data_pgtoF = 'Pendente';
						}
				?>

						<tr>

							<td style="width:15%">
								<img style="margin-top: 0px" src="<?php echo $url_sistema ?>painel/images/<?php echo $classe_pago ?>" width="8px">
								<?php echo $descricao ?>
							</td>
							<td style="width:30%"><?php echo $nome_fornecedor ?></td>
							<td style="width:15%"><?php echo $data_pgtoF ?></td>
							<td style="width:15%"><?php echo $valorF ?></td>
							<td style="width:10%" style="color:<?php echo $cor ?>; font-weight: bold;"><?php echo $porc_comissaoF ?> %</td>
							<td style="width:15%" style="color:<?php echo $cor ?>; font-weight: bold;">R$ <?php echo $valorF ?> </td>
							
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

				<td style="font-size: 10px; width:300px; text-align: right;"></td>

				<td style="font-size: 10px; width:180px; text-align: right;"><b>Quant. Comissões: <span style="color:<?php echo $cor ?>; font-weight: bold;"><?php echo $pagas ?></span></td>

				<td style="font-size: 10px; width:210px; text-align: right;"><b>Valor Total Comissões: <span style="color:<?php echo $cor ?>; font-weight: bold;">R$ <?php echo $total_pagasF ?></span></td>

			</tr>
		</tbody>
		</thead>
	</table>

	<hr>

</body>

</html>