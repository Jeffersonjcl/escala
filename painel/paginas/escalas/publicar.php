<?php
require_once("../../../conexao.php");
require_once("_helpers.php");
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
	echo json_encode(['status' => 'error', 'message' => 'Essa escala já está Publicada!']);
	exit();
}

$validacao = validarContagemEquipes($pdo, $id);
if ($validacao !== true) {
	echo json_encode(['status' => 'error', 'message' => $validacao]);
	exit();
}

publicarEscala($pdo, $id, @$api_whatsapp, @$token, @$instancia, @$nome_sistema, @$url_sistema);

echo json_encode(['status' => 'success', 'message' => 'Escala Publicada com Sucesso!']);
