<?php 
@session_start();
require_once("../../../conexao.php");
$tabela = 'notas';

$id = $_POST['id'];

$pdo->query("DELETE FROM $tabela where id = '$id'");

echo 'Excluído com Sucesso';

?>