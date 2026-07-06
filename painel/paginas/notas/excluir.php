<?php 
@session_start();
require_once("../../../conexao.php");
$tabela = 'notas';

$nivel = @$_SESSION['nivel'];
$id = $_POST['id'];


$query = $pdo->query("SELECT * FROM $tabela where id = '$id' and status = 'Aprovada'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	echo 'Você Não Tem Permissão Para Excluir Uma Nota Aprovada!';
	exit();
}


$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
$nota = $res[0]['nota'];
if($nota != "sem-foto.png"){
	@unlink('../../images/notas/'.$nota);
}

$pdo->query("DELETE FROM receber where id_ref = '$id'");

$pdo->query("DELETE FROM pagar where id_ref = '$id'");

$pdo->query("DELETE FROM $tabela where id = '$id'");

echo 'Excluído com Sucesso';

?>