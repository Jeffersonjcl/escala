<?php
@session_start();
require_once("../../../conexao.php");

$tabela = 'notas';

$id_usuario = $_SESSION['id'];

$id = $_POST['id'];

$query = $pdo->query("SELECT * from $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$numero_nota = @$res[0]['numero_nota'];
$cliente = @$res[0]['cliente'];
$funcionario = @$res[0]['funcionario'];
$fornecedor = @$res[0]['fornecedor'];
$data = @$res[0]['data'];
$hora = @$res[0]['hora'];
$data_entrega = @$res[0]['data_entrega'];
$valor = @$res[0]['valor'];
$nota = @$res[0]['nota'];
$obs = @$res[0]['obs'];


//Mudar status da Nota
$pdo->query("UPDATE $tabela SET status = 'Pendente' where id = '$id'");


echo 'Aprovado com Sucesso';
