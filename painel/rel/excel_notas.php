<?php
@session_start();
require_once("../verificar.php");
require_once("../../conexao.php");

$dataInicial = date('Y-m-01');
$dataFinal = date('Y-m-t');

$dadosXls = "";
$dadosXls .= " <table border='1' >";

$dadosXls .= " <tr>";
$dadosXls .= " <th>N. Controle</th>";
$dadosXls .= " <th>Nome do Cliente</th>";
$dadosXls .= " <th>Nome da Loja</th>";
$dadosXls .= " <th>Data da Nota</th>";
$dadosXls .= " <th>Valor da Nota</th>";
$dadosXls .= " <th>Status</th>";
$dadosXls .= " </tr>";


$query = $pdo->query("SELECT * from notas where data_entrega >= '$dataInicial' and data_entrega <= '$dataFinal' order by numero_nota asc");
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
		$dadosXls .= " <td>" . @utf8_decode($numero_nota) . "</td>";
		$dadosXls .= " <td>" . @utf8_decode($nome_cliente) . "</td>";
		$dadosXls .= " <td>" . @utf8_decode($nome_fornecedor) . "</td>";
		$dadosXls .= " <td>" . $data_entregaF . "</td>";
		$dadosXls .= " <td>" . 'R$ ' . $valorF . "</td>";
		$dadosXls .= " <td>" . @utf8_decode($status) . "</td>";
		$dadosXls .= " </tr>";

	}
}

$dadosXls .= " </table>";

$arquivo = "rel-notas.xls";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $arquivo . '"');
header('Cache-Control: max-age=0');

echo $dadosXls;
exit;
