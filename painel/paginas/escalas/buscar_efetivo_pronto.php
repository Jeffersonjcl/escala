<?php
require_once(__DIR__ . '/../_guard.php');
require_once("../../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

$data_escala = $_POST['data_escala'];
$turno = $_POST['turno'];
$id_escala = @$_POST['id_escala'] ?: 0;

$query = $pdo->prepare("SELECT p.*, f.nome funcao_nome FROM policiais p
	INNER JOIN funcoes f ON f.id = p.funcao_id
	WHERE p.disponivel = 1 AND p.turno_padrao = :turno
	AND p.id NOT IN (
		SELECT em.policial_id FROM escala_membros em
		INNER JOIN escala_equipes ee ON ee.id = em.equipe_id
		INNER JOIN escalas_diarias ed ON ed.id = ee.escala_id
		WHERE ed.data_escala = :data_escala AND ed.turno = :turno2 AND ed.id != :id_escala
	)
	ORDER BY p.nome_guerra ASC");
$query->bindValue(":turno", $turno);
$query->bindValue(":turno2", $turno);
$query->bindValue(":data_escala", $data_escala);
$query->bindValue(":id_escala", $id_escala);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($res);
