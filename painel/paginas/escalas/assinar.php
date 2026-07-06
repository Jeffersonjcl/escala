<?php
require_once(__DIR__ . '/../_guard.php');
require_once("../../../conexao.php");
@session_start();
header('Content-Type: application/json; charset=utf-8');

function responder($status, $message) {
	echo json_encode(['status' => $status, 'message' => $message]);
	exit();
}

$id = $_POST['id'];
$papel = $_POST['papel']; // 'escalante' ou 'comandante'
$id_usuario = @$_SESSION['id'];
$nivel_usuario = @$_SESSION['nivel'];

if (!$id_usuario) {
	responder('error', 'Sessão expirada, faça login novamente!');
}

if ($papel != 'escalante' and $papel != 'comandante') {
	responder('error', 'Papel de assinatura inválido!');
}

$chave_necessaria = 'assinar_' . $papel;

$tem_permissao = ($nivel_usuario == 'Administrador');
if (!$tem_permissao) {
	$query = $pdo->prepare("SELECT up.id FROM usuarios_permissoes up INNER JOIN acessos a ON a.id = up.permissao WHERE up.usuario = :usuario AND a.chave = :chave");
	$query->bindValue(":usuario", $id_usuario);
	$query->bindValue(":chave", $chave_necessaria);
	$query->execute();
	$tem_permissao = $query->fetch() ? true : false;
}

if (!$tem_permissao) {
	responder('error', 'Você não tem permissão para assinar como ' . $papel . '!');
}

$query = $pdo->prepare("SELECT status, assinado_escalante, assinado_comandante from escalas_diarias where id = :id");
$query->bindValue(":id", $id);
$query->execute();
$escala = $query->fetch(PDO::FETCH_ASSOC);

if (!$escala) {
	responder('error', 'Escala não encontrada!');
}

if ($escala['status'] != 'Publicada') {
	responder('error', 'Somente escalas Publicadas podem ser assinadas!');
}

if ($papel == 'escalante' and $escala['assinado_escalante']) {
	responder('error', 'Essa escala já foi assinada pelo Escalante!');
}

if ($papel == 'comandante' and $escala['assinado_comandante']) {
	responder('error', 'Essa escala já foi assinada pelo Comandante!');
}

if ($papel == 'escalante') {
	$pdo->prepare("UPDATE escalas_diarias SET assinado_escalante = 1, escalante_id = :uid, data_assinatura_escalante = NOW() WHERE id = :id")
		->execute([':uid' => $id_usuario, ':id' => $id]);
} else {
	$pdo->prepare("UPDATE escalas_diarias SET assinado_comandante = 1, comandante_id = :uid, data_assinatura_comandante = NOW() WHERE id = :id")
		->execute([':uid' => $id_usuario, ':id' => $id]);
}

responder('success', 'Assinatura registrada com sucesso!');
