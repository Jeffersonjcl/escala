<?php
@session_start();
require_once("../verificar.php");
require_once("../../conexao.php");

$dataInicial = $_POST['dataInicialExcel'];
$dataFinal = $_POST['dataFinalExcel'];

$dadosXls = "";
$dadosXls .= " <table border='1' >";

$dadosXls .= " <tr>";
$dadosXls .= " <th>Nota</th>";
$dadosXls .= " <th>Nome do Cliente</th>";
$dadosXls .= " <th>Nome da Loja</th>";
$dadosXls .= " <th>Valor Total da Nota</th>";
$dadosXls .= " <th>% da Comissao</th>";
$dadosXls .= " <th>Valor da Comissao</th>";
$dadosXls .= " <th>Data da Baixa Finaceira</th>";
$dadosXls .= " </tr>";


$query = $pdo->query("SELECT * from receber where data_pgto >= '$dataInicial' and data_pgto <= '$dataFinal' and pago = 'Sim' order by descricao asc, data_lanc asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	for ($i = 0; $i < $linhas; $i++) {
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


		$valorF = number_format($valor, 2, ',', '.');
		$comissaoF = number_format($comissao, 2, ',', '.');
		$porc_comissaoF = number_format($porc_comissao, 2, ',', '.'); 
		$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
		$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
		$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
		

		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_cliente = $res2[0]['nome'];
		} else {
			$nome_cliente = "Sem Registro";
		}

		$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$fornecedor'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_fornecedor = $res2[0]['nome'];
		} else {
			$nome_fornecedor = 'Loja Não Cadastrada';
		}

		$dadosXls .= " <tr>";
		$dadosXls .= " <td>" . @utf8_decode($descricao) . "</td>";
		$dadosXls .= " <td>" . @utf8_decode($nome_cliente) . "</td>";
		$dadosXls .= " <td>" . @utf8_decode($nome_fornecedor) . "</td>";
		$dadosXls .= " <td>" . 'R$ ' . $valorF . "</td>";
		$dadosXls .= " <td>" . $porc_comissaoF . ' %' . "</td>";
		$dadosXls .= " <td>" . 'R$ ' . $comissaoF . "</td>";
		$dadosXls .= " <td>" . $data_pgtoF . "</td>";
		$dadosXls .= " </tr>";

	}
}

$dadosXls .= " </table>";

$arquivo = "rel-comissao.xls";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $arquivo . '"');
header('Cache-Control: max-age=0');

echo $dadosXls;
exit;
