<?php
require_once(__DIR__ . '/../_guard.php');
require_once("../../../conexao.php");
$tabela = 'policiais';

$id = $_POST['id'];

$query2 = $pdo->query("SELECT * FROM escala_membros where policial_id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if (@count($res2) > 0) {
	echo 'Não é possível excluir, esse Policial já está vinculado a uma ou mais Escalas!';
	exit();
}

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($res) > 0) {
	$foto = $res[0]['foto'];
	if ($foto != "sem-foto.jpg" and $foto != "") {
		@unlink('../../images/perfil/' . $foto);
	}
}

$pdo->query("DELETE from usuarios where nivel = 'Policial' and id_ref = '$id'");
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
