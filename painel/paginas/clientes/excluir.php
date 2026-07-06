<?php
$tabela = 'clientes';
require_once("../../../conexao.php");

$id = $_POST['id'];


$query = $pdo->query("SELECT * FROM notas where cliente = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($res) > 0) {
	echo 'Você não pode excluir este cliente, existem Notas associados a ele(a), primeiro exclua essas Notas!';
	exit();
}


$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
$pdo->query("DELETE FROM usuarios WHERE id_ref = '$id' and nivel = 'Cliente' ");
echo 'Excluído com Sucesso';