<?php 
$tabela = 'caixa';
require_once("../../../conexao.php");

$id_usuario = $_POST['id_usuario'];
$caixa = $_POST['caixa'];
$valor = $_POST['valor'];



$query = $pdo->prepare("INSERT into $tabela SET empresa = '$id_empresa', data_ab = curDate(), hora_ab = curTime(), valor_ab = :valor, caixa = '$caixa', operador = '$id_usuario', status = 'Aberto' "); 	

$query->bindValue(":valor", "$valor");
$query->execute();

$pdo->query("UPDATE caixas set status = 'Aberto', usuario = '$id_usuario' where id = '$caixa'"); 

echo 'Salvo com Sucesso';
 ?>