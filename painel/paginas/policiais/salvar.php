<?php
require_once(__DIR__ . '/../_guard.php');
$tabela = 'policiais';
require_once("../../../conexao.php");

$id = $_POST['id'];
$nome_guerra = $_POST['nome_guerra'];
$nome_completo = $_POST['nome_completo'];
$matricula = $_POST['matricula'];
$telefone = $_POST['telefone'];
$grupo = $_POST['grupo'];
$turno_padrao = $_POST['turno_padrao'];
$funcao_id = $_POST['funcao_id'];
$disponivel = $_POST['disponivel'];
$motivo_indispo = @$_POST['motivo_indispo'];
$indispo_data_inicio = @$_POST['indispo_data_inicio'];
$indispo_dias = @$_POST['indispo_dias'];
$foto_atual = @$_POST['foto_atual'];
$criar_acesso = @$_POST['criar_acesso'];
$email_acesso = @$_POST['email_acesso'];

if ($disponivel == 1) {
	$motivo_indispo = null;
	$indispo_data_inicio = null;
	$indispo_dias = null;
} else {
	$indispo_data_inicio = ($indispo_data_inicio != "") ? $indispo_data_inicio : null;
	$indispo_dias = ($indispo_dias != "" && (int) $indispo_dias > 0) ? (int) $indispo_dias : null;
}

//SCRIPT PARA SUBIR FOTO NO SERVIDOR (mesmo padrão do editar-perfil.php)
$foto = $foto_atual ?: 'sem-foto.jpg';
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
$caminho = '../../images/perfil/' . $nome_img;
$imagem_temp = @$_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
	$ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {

		if ($foto != "sem-foto.jpg") {
			@unlink('../../images/perfil/' . $foto);
		}

		$foto = $nome_img;

		list($largura, $altura) = getimagesize($imagem_temp);
		if ($largura > 1400) {
			$image = imagecreatefromjpeg($imagem_temp);
			imagejpeg($image, $caminho, 20);
			imagedestroy($image);
		} else {
			move_uploaded_file($imagem_temp, $caminho);
		}
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

if ($id == "") {

	$query = $pdo->prepare("INSERT INTO $tabela SET nome_guerra = :nome_guerra, nome_completo = :nome_completo, matricula = :matricula, telefone = :telefone, grupo = :grupo, turno_padrao = :turno_padrao, funcao_id = :funcao_id, disponivel = :disponivel, motivo_indispo = :motivo_indispo, indispo_data_inicio = :indispo_data_inicio, indispo_dias = :indispo_dias, foto = :foto");
} else {

	$query = $pdo->prepare("UPDATE $tabela SET nome_guerra = :nome_guerra, nome_completo = :nome_completo, matricula = :matricula, telefone = :telefone, grupo = :grupo, turno_padrao = :turno_padrao, funcao_id = :funcao_id, disponivel = :disponivel, motivo_indispo = :motivo_indispo, indispo_data_inicio = :indispo_data_inicio, indispo_dias = :indispo_dias, foto = :foto where id = '$id'");
}

$query->bindValue(":nome_guerra", "$nome_guerra");
$query->bindValue(":nome_completo", "$nome_completo");
$query->bindValue(":matricula", "$matricula");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":grupo", "$grupo");
$query->bindValue(":turno_padrao", "$turno_padrao");
$query->bindValue(":funcao_id", "$funcao_id");
$query->bindValue(":disponivel", "$disponivel");
$query->bindValue(":motivo_indispo", $motivo_indispo);
$query->bindValue(":indispo_data_inicio", $indispo_data_inicio);
$query->bindValue(":indispo_dias", $indispo_dias, $indispo_dias === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
$query->bindValue(":foto", "$foto");
$query->execute();

$policial_id = $id != "" ? $id : $pdo->lastInsertId();

//criar acesso ao Painel do Policial (só no cadastro inicial)
if ($id == "" and $criar_acesso == 'Sim' and $email_acesso != "") {

	$email_acesso = strtolower($email_acesso);

	$query = $pdo->query("SELECT * from usuarios where email = '$email_acesso'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res) > 0) {
		echo 'Policial salvo, mas o Email de Acesso já está cadastrado para outro usuário!';
		exit();
	}

	$senha = '123';
	$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

	$query = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, email = :email, senha = '$senha', senha_crip = '$senha_crip', nivel = 'Policial', ativo = 'Sim', foto = 'sem-foto.jpg', telefone = :telefone, data = curDate(), id_ref = :id_ref");
	$query->bindValue(":nome", "$nome_guerra");
	$query->bindValue(":email", "$email_acesso");
	$query->bindValue(":telefone", "$telefone");
	$query->bindValue(":id_ref", $policial_id);
	$query->execute();

	if (@$api_whatsapp == 'Sim' and $telefone != "") {
		$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone);

		$mensagem = '*Você foi Cadastrado no Painel do Policial - ' . $nome_sistema . '* %0A%0A';
		$mensagem .= '🪪 Nome: *' . $nome_guerra . '* %0A';
		$mensagem .= '📧 Email: *' . $email_acesso . '* %0A';
		$mensagem .= '🔑 Senha: *' . $senha . '* %0A%0A';
		$mensagem .= '_Após acessar o sistema, troque sua senha!_ %0A%0A';
		$mensagem .= '📌 Acesso: *' . $url_sistema . 'painel_policial* %0A%0A';

		require('../../../apis/api_texto.php');
	}
}

echo 'Salvo com Sucesso';
