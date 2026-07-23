<?php
require_once(__DIR__ . '/../_guard.php');
require_once("../../../conexao.php");
require_once("_helpers.php");
@session_start();
header('Content-Type: application/json; charset=utf-8');

function responder($status, $message) {
	echo json_encode(['status' => $status, 'message' => $message]);
	exit();
}

$payload = json_decode($_POST['payload'], true);

$id = @$payload['id'];
$data_escala = $payload['data_escala'];
$grupo = @$payload['grupo'];
$equipes = $payload['equipes'];
$status_desejado = $payload['status'];

if ($data_escala == "") {
	responder('error', 'Informe a Data da Escala!');
}

if ($grupo == "" or !in_array($grupo, ['Alpha', 'Bravo'], true)) {
	responder('error', 'Informe o Grupo de Serviço da Escala!');
}

if (count($equipes) == 0) {
	responder('error', 'Adicione ao menos uma Equipe!');
}

$policiais_no_payload = [];
foreach ($equipes as $equipe) {
	if (!in_array(@$equipe['turno'], ['A', 'B'], true)) {
		responder('error', 'Toda equipe precisa ter um Turno válido (A ou B)!');
	}
	if (count($equipe['membros']) > 5) {
		responder('error', 'A equipe "' . $equipe['nome_equipe'] . '" excede o limite de 05 membros!');
	}
	foreach ($equipe['membros'] as $membro) {
		if (in_array($membro['policial_id'], $policiais_no_payload)) {
			responder('error', 'Um mesmo Policial não pode estar em duas equipes da mesma escala!');
		}
		$policiais_no_payload[] = $membro['policial_id'];
	}
}

//checar duplo-agendamento (policial já escalado em outra equipe na mesma data, em qualquer turno)
if (count($policiais_no_payload) > 0) {
	$placeholders = implode(',', array_fill(0, count($policiais_no_payload), '?'));
	$sql = "SELECT DISTINCT p.nome_guerra FROM escala_membros em
		INNER JOIN escala_equipes ee ON ee.id = em.equipe_id
		INNER JOIN escalas_diarias ed ON ed.id = ee.escala_id
		INNER JOIN policiais p ON p.id = em.policial_id
		WHERE ed.data_escala = ? AND ed.id != ? AND em.policial_id IN ($placeholders)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array_merge([$data_escala, $id ?: 0], $policiais_no_payload));
	$conflitos = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($conflitos) > 0) {
		$nomes = implode(', ', array_column($conflitos, 'nome_guerra'));
		responder('error', 'Policial(is) já escalado(s) em outra equipe na mesma data (outro turno): ' . $nomes);
	}
}

try {
	$pdo->beginTransaction();

	if ($id) {
		$query = $pdo->prepare("SELECT status from escalas_diarias where id = :id");
		$query->bindValue(":id", $id);
		$query->execute();
		$escala_atual = $query->fetch(PDO::FETCH_ASSOC);

		if (!$escala_atual) {
			throw new Exception('Escala não encontrada!');
		}
		if ($escala_atual['status'] == 'Publicada') {
			throw new Exception('Essa escala já foi publicada e não pode ser editada!');
		}

		$pdo->prepare("UPDATE escalas_diarias SET data_escala = :data_escala, grupo = :grupo WHERE id = :id")
			->execute([':data_escala' => $data_escala, ':grupo' => $grupo, ':id' => $id]);

		$pdo->prepare("DELETE FROM escala_equipes WHERE escala_id = :id")->execute([':id' => $id]);

		$escala_id = $id;
	} else {
		$id_usuario = @$_SESSION['id'];

		$query = $pdo->prepare("SELECT id from escalas_diarias where data_escala = :data_escala");
		$query->execute([':data_escala' => $data_escala]);
		if ($query->fetch()) {
			throw new Exception('Já existe uma Escala cadastrada para essa Data!');
		}

		$pdo->prepare("INSERT INTO escalas_diarias SET data_escala = :data_escala, grupo = :grupo, status = 'Rascunho', criado_por = :criado_por")
			->execute([':data_escala' => $data_escala, ':grupo' => $grupo, ':criado_por' => $id_usuario ?: null]);

		$escala_id = $pdo->lastInsertId();
	}

	foreach ($equipes as $equipe) {
		$pdo->prepare("INSERT INTO escala_equipes SET escala_id = :escala_id, turno = :turno, nome_equipe = :nome_equipe, viatura = :viatura, horario_inicio = :horario_inicio, horario_fim = :horario_fim, area_atuacao = :area_atuacao")
			->execute([
				':escala_id' => $escala_id,
				':turno' => $equipe['turno'],
				':nome_equipe' => $equipe['nome_equipe'],
				':viatura' => @$equipe['viatura'],
				':horario_inicio' => @$equipe['horario_inicio'] ?: null,
				':horario_fim' => @$equipe['horario_fim'] ?: null,
				':area_atuacao' => @$equipe['area_atuacao'] ?: null
			]);
		$equipe_id = $pdo->lastInsertId();

		foreach ($equipe['membros'] as $membro) {
			$pdo->prepare("INSERT INTO escala_membros SET equipe_id = :equipe_id, policial_id = :policial_id, funcao_na_escala_id = :funcao_id")
				->execute([':equipe_id' => $equipe_id, ':policial_id' => $membro['policial_id'], ':funcao_id' => $membro['funcao_na_escala_id']]);
		}
	}

	$pdo->commit();
} catch (Exception $e) {
	$pdo->rollBack();
	responder('error', $e->getMessage());
}

if ($status_desejado == 'Publicada') {
	$validacao = validarContagemEquipes($pdo, $escala_id);
	if ($validacao !== true) {
		responder('error', 'Escala salva como Rascunho, mas não pôde ser publicada: ' . $validacao);
	}
	publicarEscala($pdo, $escala_id, @$api_whatsapp, @$token, @$instancia, @$nome_sistema, @$url_sistema);
	responder('success', 'Escala salva e Publicada com Sucesso!');
}

responder('success', 'Escala salva como Rascunho com Sucesso!');
