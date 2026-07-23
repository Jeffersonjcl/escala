<?php
require_once(__DIR__ . '/../_guard.php');
require_once("../../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

$data_escala = $_POST['data_escala'];
$turno = $_POST['turno'];
$grupo = $_POST['grupo'];
$id_escala = @$_POST['id_escala'] ?: 0;

$condicao_drso = "(p.drso = 1 AND EXISTS (
	SELECT 1 FROM policiais_drso_dias d
	WHERE d.policial_id = p.id AND d.ano = YEAR(:data_escala2) AND d.mes = MONTH(:data_escala3) AND d.dia = DAY(:data_escala4)
))";

// mesmo grupo = disponível nos dois turnos (turno_padrao vira só informativo);
// sem grupo (livre) continua restrito ao turno_padrao cadastrado
$condicao_grupo_direto = "p.grupo = :grupo";
$condicao_grupo_livre = "(p.grupo IS NULL AND p.turno_padrao = :turno)";

$query = $pdo->prepare("SELECT p.*, f.nome funcao_nome,
		CASE WHEN NOT ($condicao_grupo_direto) AND NOT ($condicao_grupo_livre) AND p.drso = 1 THEN 1 ELSE 0 END AS via_drso
	FROM policiais p
	INNER JOIN funcoes f ON f.id = p.funcao_id
	WHERE p.disponivel = 1
	AND ($condicao_grupo_direto OR $condicao_grupo_livre OR $condicao_drso)
	AND p.id NOT IN (
		SELECT em.policial_id FROM escala_membros em
		INNER JOIN escala_equipes ee ON ee.id = em.equipe_id
		INNER JOIN escalas_diarias ed ON ed.id = ee.escala_id
		WHERE ed.data_escala = :data_escala AND ee.turno = :turno2 AND ed.id != :id_escala
	)
	ORDER BY p.nome_guerra ASC");
$query->bindValue(":turno", $turno);
$query->bindValue(":turno2", $turno);
$query->bindValue(":grupo", $grupo);
$query->bindValue(":data_escala", $data_escala);
$query->bindValue(":data_escala2", $data_escala);
$query->bindValue(":data_escala3", $data_escala);
$query->bindValue(":data_escala4", $data_escala);
$query->bindValue(":id_escala", $id_escala);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($res);
