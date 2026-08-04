<?php
require_once("../conexao.php");
@session_start();
$id_usuario = $_SESSION['id'];

$home = 'ocultar';
$configuracoes = 'ocultar';

//grupo administração
$usuarios = 'ocultar';
$grupos = 'ocultar';
$acessos = 'ocultar';

//grupo efetivo
$policiais = 'ocultar';
$funcoes = 'ocultar';
$postos = 'ocultar';

//grupo escala
$escalas = 'ocultar';
$escalas_listagem = 'ocultar';
$assinar_escalante = 'ocultar';
$assinar_comandante = 'ocultar';


$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	for ($i = 0; $i < $total_reg; $i++) {
		$permissao = $res[$i]['permissao'];

		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$chave = $res2[0]['chave'];

		if ($chave == 'home') {
			$home = '';
		}

		if ($chave == 'configuracoes') {
			$configuracoes = '';
		}

		if ($chave == 'usuarios') {
			$usuarios = '';
		}

		if ($chave == 'grupos') {
			$grupos = '';
		}

		if ($chave == 'acessos') {
			$acessos = '';
		}

		if ($chave == 'policiais') {
			$policiais = '';
		}

		if ($chave == 'funcoes') {
			$funcoes = '';
		}

		if ($chave == 'postos') {
			$postos = '';
		}

		if ($chave == 'escalas') {
			$escalas = '';
		}

		if ($chave == 'escalas_listagem') {
			$escalas_listagem = '';
		}

		if ($chave == 'assinar_escalante') {
			$assinar_escalante = '';
		}

		if ($chave == 'assinar_comandante') {
			$assinar_comandante = '';
		}
	}
}


$pag_inicial = '';
if ($home != 'ocultar') {
	$pag_inicial = 'home';
} else {
	$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' order by id asc limit 1");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if ($total_reg > 0) {
		$permissao = $res[0]['permissao'];
		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$pag_inicial = $res2[0]['chave'];
	} else {
		echo 'Você não tem permissão para acessar nenhuma página, acione o administrador!';
		exit();
	}
}


if ($usuarios == 'ocultar' and $grupos == 'ocultar' and $acessos == 'ocultar') {
	$menu_administracao = 'ocultar';
} else {
	$menu_administracao = '';
}

if ($policiais == 'ocultar' and $funcoes == 'ocultar' and $postos == 'ocultar') {
	$menu_efetivo = 'ocultar';
} else {
	$menu_efetivo = '';
}

if ($escalas == 'ocultar' and $escalas_listagem == 'ocultar') {
	$menu_escala = 'ocultar';
} else {
	$menu_escala = '';
}
