<?php

function notificarEscalaPublicada($pdo, $escala_id, $token, $instancia, $nome_sistema, $url_sistema) {

	$query = $pdo->prepare("SELECT ed.data_escala, ed.turno, ee.nome_equipe, p.nome_guerra, p.telefone, f.nome funcao_nome
		FROM escala_membros em
		INNER JOIN escala_equipes ee ON ee.id = em.equipe_id
		INNER JOIN escalas_diarias ed ON ed.id = ee.escala_id
		INNER JOIN policiais p ON p.id = em.policial_id
		INNER JOIN funcoes f ON f.id = em.funcao_na_escala_id
		WHERE ee.escala_id = :escala_id AND p.telefone IS NOT NULL AND p.telefone != ''");
	$query->bindValue(":escala_id", $escala_id);
	$query->execute();
	$membros = $query->fetchAll(PDO::FETCH_ASSOC);

	foreach ($membros as $membro) {
		$data_escalaF = implode('/', array_reverse(explode('-', $membro['data_escala'])));

		$mensagem = '*Você foi Escalado - ' . $nome_sistema . '* %0A%0A';
		$mensagem .= '🗓 Data: *' . $data_escalaF . '* %0A';
		$mensagem .= '🕐 Turno: *' . $membro['turno'] . '* %0A';
		$mensagem .= '🚔 Equipe: *' . $membro['nome_equipe'] . '* %0A';
		$mensagem .= '🎖 Função: *' . $membro['funcao_nome'] . '* %0A%0A';
		$mensagem .= '_Verifique os detalhes no Painel do Policial._ %0A';
		$mensagem .= '📌 Acesso: *' . $url_sistema . 'painel_policial* %0A';

		$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $membro['telefone']);

		require(__DIR__ . '/api_texto.php');
	}
}
