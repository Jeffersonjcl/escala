<?php
@session_start();
if (empty($_SESSION['id']) or @$_SESSION['nivel'] == 'Policial') {
	exit('Acesso negado!');
}
