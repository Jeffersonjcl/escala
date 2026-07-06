<?php
require_once("conexao.php");

$senha = $_POST['senha'];
$re_senha = $_POST['re_senha'];
$token = $_POST['token'];
$email = $_POST['email'];

$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

$_SESSION['temp_reset_email'] = $_REQUEST['email'];
$_SESSION['temp_reset_token'] = $_REQUEST['token'];

if ($senha != $re_senha) {
     echo 'Ops! As senhas Não São Iguais!';
     exit();
}

$senha = password_hash($senha, PASSWORD_DEFAULT);

$query = $pdo->prepare("UPDATE usuarios SET senha_crip = :senha_crip, token = :token WHERE email = '$email'");

$query->bindValue(":senha_crip", "$senha_crip");
$query->bindValue(":token", "$token");
$query->execute();

echo 'Senha alterada com Sucesso';
