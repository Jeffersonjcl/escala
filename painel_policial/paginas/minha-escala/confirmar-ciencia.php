<?php
require_once("../../verificar.php");
require_once("../../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

$em_id = $_POST['em_id'];
$policial_id = @$_SESSION['id_ref'];

$query = $pdo->prepare("SELECT id from escala_membros where id = :em_id and policial_id = :policial_id");
$query->bindValue(":em_id", $em_id);
$query->bindValue(":policial_id", $policial_id);
$query->execute();

if (!$query->fetch()) {
	echo json_encode(['status' => 'error', 'message' => 'Registro não encontrado ou não pertence a você!']);
	exit();
}

$pdo->prepare("UPDATE escala_membros SET ciente = 1, data_ciencia = NOW() WHERE id = :em_id")
	->execute([':em_id' => $em_id]);

echo json_encode(['status' => 'success', 'message' => 'Ciência confirmada com sucesso!']);
