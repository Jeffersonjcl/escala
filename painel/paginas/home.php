<?php
$pag = 'home';

//total de os
$query = $pdo->query("SELECT * from os where status = 'Aberta' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$os_abertas = @count($res);

//total de orcamentos
$query = $pdo->query("SELECT * from notas where status = 'Pendente' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$orc_pendentes = @count($res);

//total receber hoje
$receber_hoje_rs = 0;
$query = $pdo->query("SELECT * from receber where data_venc = curDate() and pago = 'Não' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$receber_hoje = @count($res);
if ($receber_hoje > 0) {
	for ($i = 0; $i < $receber_hoje; $i++) {
		$valor = $res[$i]['valor'];
		$receber_hoje_rs +=	$valor;
	}
}
$receber_hoje_rsF = number_format($receber_hoje_rs, 2, ',', '.');

//total pagar hoje
$pagar_hoje_rs = 0;
$query = $pdo->query("SELECT * from pagar where data_venc = curDate() and pago = 'Não' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$pagar_hoje = @count($res);
if ($pagar_hoje > 0) {
	for ($i = 0; $i < $pagar_hoje; $i++) {
		$valor = $res[$i]['valor'];
		$pagar_hoje_rs +=	$valor;
	}
}
$pagar_hoje_rsF = number_format($pagar_hoje_rs, 2, ',', '.');


//total vendas hoje
$vendas_hoje_rs = 0;
$query = $pdo->query("SELECT * from receber where data_pgto = curDate() and pago = 'Sim' and referencia = 'Venda' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$vendas_hoje = @count($res);
if ($vendas_hoje > 0) {
	for ($i = 0; $i < $vendas_hoje; $i++) {
		$valor = $res[$i]['valor'];
		$vendas_hoje_rs +=	$valor;
	}
}


//total serviços hoje
$servicos_hoje_rs = 0;
$query = $pdo->query("SELECT * from receber where data_pgto = curDate() and pago = 'Sim' and referencia = 'Serviço' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$servicos_hoje = @count($res);
if ($servicos_hoje > 0) {
	for ($i = 0; $i < $servicos_hoje; $i++) {
		$valor = $res[$i]['valor'];
		$servicos_hoje_rs +=	$valor;
	}
}


$total_serv_prod = $servicos_hoje_rs + $vendas_hoje_rs;


$total_serv_prodF = number_format($total_serv_prod, 2, ',', '.');

$data_inicio = date('Y-m-01');
$data_final = date('Y-m-t');
//total de os atrasadas
$query = $pdo->query("SELECT * from notas where data_entrega >= '$data_inicio' and  data_entrega <= '$data_final'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_nota_mes = @count($res);


//total de Notas no mês
$query = $pdo->query("SELECT * from notas where data >= '$data_mes' and data <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_orc_mes = @count($res);

//total de Notas no mês aprovados
$query = $pdo->query("SELECT * from notas where data >= '$data_mes' and data <= curDate() and status = 'Aprovado'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_orc_mes_aprovados = @count($res);

if ($total_orc_mes > 0 and $total_orc_mes_aprovados > 0) {
	$porcentagem_orc = ($total_orc_mes_aprovados / $total_orc_mes) * 100;
} else {
	$porcentagem_orc = 0;
}

$porcentagem_orcF = number_format($porcentagem_orc, 2, ',', '.');


//total de os no mês
$query = $pdo->query("SELECT * from os where data >= '$data_mes' and data <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_os_mes = @count($res);

//total de os no mês aprovados
$query = $pdo->query("SELECT * from os where data >= '$data_mes' and data <= curDate() and status = 'Entregue'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_os_mes_aprovados = @count($res);

if ($total_os_mes > 0 and $total_os_mes_aprovados > 0) {
	$porcentagem_os = ($total_os_mes_aprovados / $total_os_mes) * 100;
} else {
	$porcentagem_os = 0;
}

$porcentagem_osF = number_format($porcentagem_os, 2, ',', '.');


//total clientes
$query = $pdo->query("SELECT * from clientes");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_clientes = @count($res);

//total clientes mes
$query = $pdo->query("SELECT * from clientes where data_cad >= '$data_inicio_mes' and data_cad <= '$data_final_mes'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_clientes_mes = @count($res);

//total pagar vencidas
$total_pagar_vencidas = 0;
$query = $pdo->query("SELECT * from pagar where data_venc < curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);
for ($i = 0; $i < $contas_pagar_vencidas; $i++) {
	$valor_1 = $res[$i]['valor'];
	$total_pagar_vencidas += $valor_1;
}
$total_pagar_vencidasF = @number_format($total_pagar_vencidas, 2, ',', '.');


//total receber vencidas
$total_receber_vencidas = 0;
$query = $pdo->query("SELECT * from receber where data_venc < curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_receber_vencidas = @count($res);
for ($i = 0; $i < $contas_receber_vencidas; $i++) {
	$valor_2 = $res[$i]['valor'];
	$total_receber_vencidas += $valor_2;
}
$total_receber_vencidasF = @number_format($total_receber_vencidas, 2, ',', '.');


//total recebidas mes
$total_recebidas_mes = 0;
$query = $pdo->query("SELECT * from receber where data_pgto >= '$data_inicio_mes' and data_pgto <= '$data_final_mes' and pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_recebidas_mes = @count($res);
for ($i = 0; $i < $contas_recebidas_mes; $i++) {
	$valor_3 = $res[$i]['valor'];
	$total_recebidas_mes += $valor_3;
}
$total_recebidas_mesF = @number_format($total_recebidas_mes, 2, ',', '.');

//total contas pagas mes
$total_pagas_mes = 0;
$query = $pdo->query("SELECT * from pagar where data_pgto >= '$data_inicio_mes' and data_pgto <= '$data_final_mes' and pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagas_mes = @count($res);
for ($i = 0; $i < $contas_pagas_mes; $i++) {
	$valor_4 = $res[$i]['valor'];
	$total_pagas_mes += $valor_4;
}
$total_pagas_mesF = @number_format($total_pagas_mes, 2, ',', '.');


$total_saldo_mes = $total_recebidas_mes - $total_pagas_mes;
$total_saldo_mesF = @number_format($total_saldo_mes, 2, ',', '.');
if ($total_saldo_mes >= 0) {
	$classe_saldo = 'text-success';
} else {
	$classe_saldo = 'text-danger';
}




//total de contas receber no mês
$query = $pdo->query("SELECT * from receber where data_venc >= '$data_mes' and data_venc <= '$data_final_mes'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_receber_mes = @count($res);

//total de os no mês aprovados
$query = $pdo->query("SELECT * from receber where data_venc >= '$data_mes' and data_venc <= '$data_final_mes' and pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_receber_mes_pagas = @count($res);

if ($total_receber_mes > 0 and $total_receber_mes_pagas > 0) {
	$porcentagem_receber = ($total_receber_mes_pagas / $total_receber_mes) * 100;
} else {
	$porcentagem_receber = 0;
}

$porcentagem_receberF = @number_format($porcentagem_receber, 2, ',', '.');


//total recebidas dia
$total_recebidas_dia = 0;
$query = $pdo->query("SELECT * from receber where data_pgto = curDate() and pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_recebidas_dia = @count($res);
for ($i = 0; $i < $contas_recebidas_dia; $i++) {
	$valor_3 = $res[$i]['valor'];
	$total_recebidas_dia += $valor_3;
}
$total_recebidas_diaF = @number_format($total_recebidas_dia, 2, ',', '.');

//total contas pagas dia
$total_pagas_dia = 0;
$query = $pdo->query("SELECT * from pagar where data_pgto = curDate() and pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagas_dia = @count($res);
for ($i = 0; $i < $contas_pagas_dia; $i++) {
	$valor_4 = $res[$i]['valor'];
	$total_pagas_dia += $valor_4;
}
$total_pagas_diaF = @number_format($total_pagas_dia, 2, ',', '.');


$total_saldo_dia = $total_recebidas_dia - $total_pagas_dia;
$total_saldo_diaF = @number_format($total_saldo_dia, 2, ',', '.');
if ($total_saldo_dia >= 0) {
	$classe_saldo_dia = 'bg-success';
} else {
	$classe_saldo_dia = 'bg-danger';
}




//valores para o grafico de linhas (ano atual, despesas e recebimentos)
$total_meses_pagar_grafico = '';
$total_meses_receber_grafico = '';

for ($i = 1; $i <= 12; $i++) {
	if ($i < 10) {
		$mes_verificado = '0' . $i;
	} else {
		$mes_verificado = $i;
	}


	$data_inicio_mes_grafico = $ano_atual . "-" . $mes_verificado . "-01";

	if ($mes_verificado == '04' || $mes_verificado == '06' || $mes_verificado == '09' || $mes_verificado == '11') {
		$data_final_mes_grafico = $ano_atual . '-' . $mes_verificado . '-30';
	} else if ($mes_verificado == '02') {
		$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $mes_verificado));
		if ($bissexto == 1) {
			$data_final_mes_grafico = $ano_atual . '-' . $mes_verificado . '-29';
		} else {
			$data_final_mes_grafico = $ano_atual . '-' . $mes_verificado . '-28';
		}
	} else {
		$data_final_mes_grafico = $ano_atual . '-' . $mes_verificado . '-31';
	}




	//total recebidas mes
	$total_recebidas_mes_grafico = 0;
	$query2 = $pdo->query("SELECT * from receber where data_pgto >= '$data_inicio_mes_grafico' and data_pgto <= '$data_final_mes_grafico' and pago = 'Sim'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$contas_recebidas_mes_grafico = @count($res2);
	for ($i2 = 0; $i2 < $contas_recebidas_mes_grafico; $i2++) {
		$valor_3 = $res2[$i2]['valor'];
		$total_recebidas_mes_grafico += $valor_3;
	}


	//total contas pagas mes
	$total_pagas_mes_grafico = 0;
	$query2 = $pdo->query("SELECT * from pagar where data_pgto >= '$data_inicio_mes_grafico' and data_pgto <= '$data_final_mes_grafico' and pago = 'Sim'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$contas_pagas_mes_grafico = @count($res2);
	for ($i2 = 0; $i2 < $contas_pagas_mes_grafico; $i2++) {
		$valor_4 = $res2[$i2]['valor'];
		$total_pagas_mes_grafico += $valor_4;
	}


	$total_meses_pagar_grafico .= $total_pagas_mes_grafico . '-';
	$total_meses_receber_grafico .= $total_recebidas_mes_grafico . '-';
}


//verificar se ele tem a permissão de estar nessa página
if (@$home == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}
?>

<?php if ($ativo_sistema == '') { ?>
	<div style="background: #ffc341; color:#3e3e3e; padding:10px; font-size:14px; margin-bottom:10px">
		<div><i class="fa fa-info-circle"></i> <b>Aviso: </b> Prezado Cliente, não identificamos o pagamento de sua última mensalidade, entre em contato conosco o mais rápido possivel para regularizar o pagamento, caso contário seu acesso ao sistema será desativado.</div>
	</div>
<?php } ?>



<div class="mt-4 justify-content-between">




	<div class="row">

		<div class="col-xl-4 col-lg-12 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1); height:130px; border-radius: 10px;">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<div class="">
								<h6 class="mb-2 tx-12 ">Pagamentos Hoje</h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<h4 class="tx-20 font-weight-semibold mb-2">R$ <?php echo $pagar_hoje_rsF ?></h4>
								</div>
								<p class="mb-0 tx-12 text-muted">Total de Pagamentos<i class="fa fa-caret-down mx-2 text-danger"></i>
									<span class="text-danger font-weight-semibold"><?php echo $pagar_hoje ?></span>
								</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<a href="pagar">
							<div class=" hov circle-icon bg-secondary-transparent text-center align-self-center overflow-hidden">
								<i class="fe fe-dollar-sign tx-16 text-secondary"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xl-4 col-lg-12 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1); height:130px; border-radius: 10px">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<div class="">
								<h6 class="mb-2 tx-12">Recebimentos Hoje</h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<h4 class="tx-20 font-weight-semibold mb-2">R$ <?php echo $receber_hoje_rsF ?></h4>
								</div>
								<p class="mb-0 tx-12 text-muted">Total de Recebimentos<i class="fa fa-caret-up mx-2 text-success"></i>
									<span class="font-weight-semibold text-success"><?php echo $receber_hoje ?></span>
								</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<a href="receber">
							<div class="hov circle-icon bg-success-transparent text-center align-self-center overflow-hidden">
								<i class="fa fa-sack-dollar tx-16 text-success"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-lg-12 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1); height:130px; border-radius: 10px">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<div class="">
								<h6 class="mb-2 tx-12">Notas Pedentes de Aprovação</h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<h4 class="tx-20 font-weight-semibold mb-2"><?php echo $orc_pendentes ?></h4>
								</div>
								<p class="mb-0 tx-12 text-muted">Total Notas Mês<i class="fa fa-caret-up mx-2 text-success"></i>
									<span class="font-weight-semibold text-success"><?php echo $total_nota_mes ?></span>
								</p>

							</div>
						</div>
					</div>
					<div class="col-4">
						<a href="notas">
							<div class=" hov circle-icon bg-secondary-transparent text-center align-self-center overflow-hidden">
								<i class="fe fe-file-text tx-16 text-secondary"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

	</div>



	<div class="row">
		
		<div class="col-xl-4 col-lg-12 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);height:130px; border-radius: 10px">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<div class="">
								<h6 class="mb-2 tx-12">Pagamentos Vencidos</h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<h4 class="tx-20 font-weight-semibold mb-2">R$ <?php echo $total_pagar_vencidasF ?></h4>
								</div>
								<p class="mb-0 tx-12 text-muted">Total de Pagamentos<i class="fa fa-caret-down mx-2 text-danger"></i>
									<span class="font-weight-semibold text-danger"><?php echo $contas_pagar_vencidas ?></span>
								</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<a href="pagar">
							<div class=" hov circle-icon bg-info-transparent text-center align-self-center overflow-hidden">
								<i class="fe fe-dollar-sign tx-16 text-info"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-lg-12 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);height:130px; border-radius: 10px">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<div class="">
								<h6 class="mb-2 tx-12">Recebimentos Vencidas</h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<h4 class="tx-20 font-weight-semibold mb-2">R$ <?php echo $total_receber_vencidasF ?></h4>
								</div>
								<p class="mb-0 tx-12 text-muted">Total de Recebimentos<i class="fa fa-caret-up mx-2 text-success"></i>
									<span class=" text-success font-weight-semibold"> <?php echo $contas_receber_vencidas ?></span>
								</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<a href="receber">
							<div class=" hov circle-icon bg-secondary-transparent text-center align-self-center overflow-hidden">
								<i class="fe fe-dollar-sign tx-16 text-secondary"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-lg-12 col-md-6 col-xs-12">
			<div class="card sales-card" style="box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 0.1);height:130px; border-radius: 10px">
				<div class="row">
					<div class="col-8">
						<div class="ps-4 pt-4 pe-3 pb-4">
							<div class="">
								<h6 class="mb-2 tx-12">Saldo no Mês</h6>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<h4 class="tx-18 font-weight-semibold mb-2 <?php echo $classe_saldo ?>">R$ <?php echo $total_saldo_mesF ?></h4>
								</div>
								<p class="mb-0 tx-13  text-muted">Saldo Hoje<i class="fa fa-caret-up mx-2 text-success"></i>
									<span class="text-success font-weight-semibold"> R$ <?php echo $total_serv_prodF ?></span>
								</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<a href="lista_vendasS">
							<div class=" hov circle-icon bg-warning-transparent text-center align-self-center overflow-hidden">
								<i class="fe fe-dollar-sign tx-16 text-warning"></i>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="statistics2"></div>

	<div class="card custom-card overflow-hidden ocultar_mobile" style="border-radius: 10px;">
		<div class="card-header border-bottom-0">
			<div>
				<h3 class="card-title mb-2 ">Recebimentos / Despesas <?php echo $ano_atual ?></h3> <span class="d-block tx-12 mb-0 text-muted"></span>
			</div>
		</div>
		<div class="card-body">
			<div id="statistics1"></div>
		</div>
	</div>

</div>



<script type="text/javascript">
	// GRAFICO DE BARRAS
	function statistics1() {
		var total_pagar = "<?= $total_meses_pagar_grafico ?>";
		var total_receber = "<?= $total_meses_receber_grafico ?>";

		var split_pagar = total_pagar.split("-");
		var split_receber = total_receber.split("-");


		setTimeout(() => {
			var options1 = {
				series: [{
					name: 'Contas à Receber',
					data: [split_receber[0], split_receber[1], split_receber[2], split_receber[3], split_receber[4], split_receber[5], split_receber[6], split_receber[7], split_receber[8], split_receber[9], split_receber[10], split_receber[11]],
				}, {
					name: 'Contas à Pagar',
					data: [split_pagar[0], split_pagar[1], split_pagar[2], split_pagar[3], split_pagar[4], split_pagar[5], split_pagar[6], split_pagar[7], split_pagar[8], split_pagar[9], split_pagar[10], split_pagar[11]],
				}],
				chart: {
					type: 'bar',
					height: 280
				},
				grid: {
					borderColor: '#f2f6f7',
				},
				colors: ["#098522", "#bf281d"],
				plotOptions: {
					bar: {
						colors: {
							ranges: [{
								from: -100,
								to: -46,
								color: '#098522'
							}, {
								from: -45,
								to: 0,
								color: '#bf281d'
							}]
						},
						columnWidth: '40%',
					}
				},
				dataLabels: {
					enabled: false,
				},
				stroke: {
					show: true,
					width: 4,
					colors: ['transparent']
				},
				legend: {
					show: true,
					position: 'top',
				},
				yaxis: {
					title: {
						text: 'Valores',
						style: {
							color: '#adb5be',
							fontSize: '14px',
							fontFamily: 'poppins, sans-serif',
							fontWeight: 600,
							cssClass: 'apexcharts-yaxis-label',
						},
					},
					labels: {
						formatter: function(y) {
							return y.toFixed(0) + "";
						}
					}
				},
				xaxis: {
					type: 'month',
					categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
					axisBorder: {
						show: true,
						color: 'rgba(119, 119, 142, 0.05)',
						offsetX: 0,
						offsetY: 0,
					},
					axisTicks: {
						show: true,
						borderType: 'solid',
						color: 'rgba(119, 119, 142, 0.05)',
						width: 6,
						offsetX: 0,
						offsetY: 0
					},
					labels: {
						rotate: -90
					}
				}
			};
			document.getElementById('statistics1').innerHTML = '';
			var chart1 = new ApexCharts(document.querySelector("#statistics1"), options1);
			chart1.render();
		}, 300);
	}
</script>