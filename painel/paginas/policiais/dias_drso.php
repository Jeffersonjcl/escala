<?php
require_once(__DIR__ . '/../_guard.php');
require_once("../../../conexao.php");

header('Content-Type: application/json; charset=utf-8');

$policial_id = @$_POST['policial_id'] ?: 0;

$query = $pdo->prepare("SELECT dia FROM policiais_drso_dias WHERE policial_id = :policial_id AND ano = :ano AND mes = :mes ORDER BY dia ASC");
$query->execute([
	':policial_id' => $policial_id,
	':ano' => date('Y'),
	':mes' => date('n'),
]);

echo json_encode(array_column($query->fetchAll(PDO::FETCH_ASSOC), 'dia'));
