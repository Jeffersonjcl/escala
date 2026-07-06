<?php

function validarContagemEquipes($pdo, $escala_id) {
	$query = $pdo->prepare("SELECT ee.id, ee.nome_equipe, COUNT(em.id) total
		FROM escala_equipes ee
		LEFT JOIN escala_membros em ON em.equipe_id = ee.id
		WHERE ee.escala_id = :escala_id
		GROUP BY ee.id");
	$query->bindValue(":escala_id", $escala_id);
	$query->execute();
	$equipes = $query->fetchAll(PDO::FETCH_ASSOC);

	if (count($equipes) == 0) {
		return 'Escala não possui nenhuma equipe cadastrada!';
	}

	foreach ($equipes as $equipe) {
		if ($equipe['total'] < 4 or $equipe['total'] > 5) {
			return 'A equipe "' . $equipe['nome_equipe'] . '" precisa ter entre 4 e 5 membros para publicar a escala (atual: ' . $equipe['total'] . ').';
		}
	}

	return true;
}

function publicarEscala($pdo, $escala_id, $api_whatsapp, $token, $instancia, $nome_sistema, $url_sistema) {
	$pdo->prepare("UPDATE escalas_diarias SET status = 'Publicada' WHERE id = :id")
		->execute([':id' => $escala_id]);

	if (@$api_whatsapp == 'Sim') {
		require_once(__DIR__ . '/../../../apis/notificar_escala.php');
		notificarEscalaPublicada($pdo, $escala_id, $token, $instancia, $nome_sistema, $url_sistema);
	}
}
