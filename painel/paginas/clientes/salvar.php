<?php
$tabela = 'clientes';
require_once("../../../conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$data_nasc = $_POST['data_nasc'];
$cpf = $_POST['cpf'];
$tipo_pessoa = $_POST['tipo_pessoa'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];
$profissao = $_POST['profissao'];
$nacionalidade = $_POST['nacionalidade'];
$estado_civil = $_POST['estado_civil'];

$nome = mb_convert_case($nome, MB_CASE_TITLE, 'UTF-8');
$email = strtolower($email);
$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

//validacao email
if ($email != "") {
	$query = $pdo->query("SELECT * from $tabela where email = '$email'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id_reg = @$res[0]['id'];
	if (@count($res) > 0 and $id != $id_reg) {
		echo 'E-mail já Cadastrado!';
		exit();
	}
}

//validacao cpf
if ($cpf != "") {
	$query = $pdo->query("SELECT * from $tabela where cpf = '$cpf'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id_reg = @$res[0]['id'];
	if (@count($res) > 0 and $id != $id_reg) {
		echo 'CPF ou CNPJ já Cadastrado!';
		exit();
	}
}

//validacao telefone
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if (@count($res) > 0 and $id != $id_reg) {
	echo 'Telefone já Cadastrado!';
	exit();
}


if ($id == "") {
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, cpf = :cpf, tipo_pessoa = :tipo_pessoa, data_nasc = '$data_nasc', numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep, complemento = :complemento, profissao = :profissao, estado_civil = :estado_civil, nacionalidade = :nacionalidade, senha = '$senha_crip', data_cad = curDate()");
} else {
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, cpf = :cpf, tipo_pessoa = :tipo_pessoa, data_nasc = '$data_nasc', numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep, complemento = :complemento, profissao = :profissao, estado_civil = :estado_civil, nacionalidade = :nacionalidade, senha = '$senha_crip' where id = '$id'");
}


$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":tipo_pessoa", "$tipo_pessoa");
$query->bindValue(":numero", "$numero");
$query->bindValue(":bairro", "$bairro");
$query->bindValue(":cidade", "$cidade");
$query->bindValue(":estado", "$estado");
$query->bindValue(":cep", "$cep");
$query->bindValue(":complemento", "$complemento");
$query->bindValue(":profissao", "$profissao");
$query->bindValue(":estado_civil", "$estado_civil");
$query->bindValue(":nacionalidade", "$nacionalidade");
$query->execute();


if ($id == "") {
	$ult_id = $pdo->lastInsertId();
	$query = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, senha = '$senha', senha_crip = '$senha_crip', nivel = 'Cliente', ativo = 'Sim', foto = 'sem-foto.jpg', telefone = :telefone, data = curDate(), endereco = :endereco, id_ref = '$ult_id'");
} else {
	$ult_id = $id;
	$query = $pdo->prepare("UPDATE usuarios SET nome = :nome,  telefone = :telefone, endereco = :endereco where id_ref = '$ult_id' and nivel = 'Cliente' ");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->execute();

echo 'Salvo com Sucesso';

//api whats
if ($api_whatsapp == 'Sim' and $id == "") {
	$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone);

	$mensagem = 'Você foi Cadastrado no Sistema da *' . $nome_sistema . '* %0A%0A';
	$mensagem .= '🪪 Nome: *' . $nome . '* %0A';
	$mensagem .= '_Para envio de suas 📄 Notas, use seu o numero de seu telefone e sua senha temporária para acesso o painel, após acessar, troque a senha para uma de sua preferência!_ %0A%0A';
	$mensagem .= '📱 Login: *' . $telefone . '* %0A';
	$mensagem .= '🔑 Senha: *' . '123' . '* %0A';
	$mensagem .= '📌 Url Painel: *' . $url_sistema . '* %0A%0A';

	if ($instagram_sistema != '') {

		$mensagem .= '📷 _Siga nosso Instagram:_ *https://www.instagram.com/' . $instagram_sistema . '* %0A%0A';
	}

	require('../../../apis/api_texto.php');
}
