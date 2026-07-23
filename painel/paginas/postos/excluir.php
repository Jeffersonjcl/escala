<?php
require_once(__DIR__ . '/../_guard.php');
require_once("../../../conexao.php");
$tabela = 'postos';

$id = $_POST['id'];

$query2 = $pdo->query("SELECT * FROM policiais where posto_id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);
if ($total_reg2 > 0) {
	echo 'Não é possível excluir, existem policiais cadastrados com esse Posto/Graduação!';
	exit();
}

$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
