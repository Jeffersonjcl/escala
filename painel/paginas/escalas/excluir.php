<?php
require_once("../../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

$id = $_POST['id'];

$query = $pdo->prepare("SELECT status from escalas_diarias where id = :id");
$query->bindValue(":id", $id);
$query->execute();
$escala = $query->fetch(PDO::FETCH_ASSOC);

if (!$escala) {
	echo json_encode(['status' => 'error', 'message' => 'Escala não encontrada!']);
	exit();
}

if ($escala['status'] == 'Publicada') {
	echo json_encode(['status' => 'error', 'message' => 'Não é possível excluir uma Escala já Publicada!']);
	exit();
}

$pdo->prepare("DELETE from escalas_diarias where id = :id")->execute([':id' => $id]);

echo json_encode(['status' => 'success', 'message' => 'Excluído com Sucesso']);
