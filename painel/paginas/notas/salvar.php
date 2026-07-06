<?php
@session_start();
require_once("../../../conexao.php");
$tabela = 'notas';

$id_usuario = @$_SESSION['id'];

$id = @$_POST['id'];
$cliente = @$_POST['cliente'];
$fornecedor = @$_POST['fornecedor'];
$data_entrega = @$_POST['data_entrega'];
$valor = @$_POST['valor'];
$valor = @str_replace('.', '', $valor);
$valor = @str_replace(',', '.', $valor);
$obs = @$_POST['obs'];

$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorContador = @$res[0]['contador'];
$valorAnoAtual = @$res[0]['ano_atual'];

//Inicio contador e o ano passando valores iniciais
@$_SESSION['contador'] = $valorContador;
@$_SESSION['ano_atual'] = $valorAnoAtual;


//Consulta se ja existe Nota no db
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	$nota = $res[0]['nota'];
} else {
	$nota = 'sem-foto.png';
}


//Script para subir Nota para DB
$nome_img = date('d-m-Y_H:i:s') . '-' . @$_FILES['arquivo']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
$caminho = '../../images/notas/' . $nome_img;

$imagem_temp = @$_FILES['arquivo']['tmp_name'];

if (@$_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {

	$tamanhoMax = 2 * 1024 * 1024; // 2MB em bytes

	if (@$_FILES['arquivo']['size'] > $tamanhoMax) {
		echo "Ops! O tamanho do Arquivo não pode ser superior a 2 MB.";
		exit;
	}

	if (@$_FILES['arquivo']['name'] != "") {
		$ext = pathinfo($nome_img, PATHINFO_EXTENSION);
		if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'xlsx' or $ext == 'xlsm' or $ext == 'xls' or $ext == 'xml' or $ext == 'pdf' or $ext == 'rar' or $ext == 'zip' or $ext == 'doc' or $ext == 'docx' or $ext == 'txt' or $ext == 'webp') {

			if (@$_FILES['arquivo']['name'] != "") {

				//EXCLUO A FOTO ANTERIOR
				if ($nota != "sem-foto.png") {
					@unlink('../../images/notas/' . $nota);
				}

				$nota = $nome_img;
			}

			move_uploaded_file($imagem_temp, $caminho);
		} else {
			echo 'Extensão de Arquivo não permitida!';
			exit();
		}
	}

} else {
	echo "Erro ao Realizar Upload: " . $_FILES['arquivo']['error'];
	exit;
}


if ($id == '') {

	//Gera numero de Documento
	function gerarNumeracao() {

		$anoCorrente = date("Y");

		if (@$_SESSION['ano_atual'] != $anoCorrente) {
			@$_SESSION['ano_atual'] = $anoCorrente;
			@$_SESSION['contador'] = 0;
		}

		@$_SESSION['contador']++;
		return sprintf("%04d/%d", @$_SESSION['contador'], @$_SESSION['ano_atual']);
	}

	//Gera Novo Número
	$numero_nota = gerarNumeracao();

	//Novos Valores para o DB
	$novoContador = @$_SESSION['contador'];
	$novoAnoAtual = @$_SESSION['ano_atual'];
	
}


//Salva dados no DB
if ($id == "") {

	$query = $pdo->prepare("INSERT INTO $tabela SET numero_nota = '$numero_nota', cliente = '$cliente', funcionario = '$id_usuario', fornecedor = :fornecedor, valor = :valor, data = curDate(), hora = curTime(), data_entrega = '$data_entrega', nota = '$nota', status = 'Pendente', obs = :obs");
	$pdo->query("UPDATE config SET contador = '$novoContador', ano_atual = '$novoAnoAtual'");
} else {

	$query = $pdo->prepare("UPDATE $tabela SET cliente = '$cliente', funcionario = '$id_usuario', fornecedor = :fornecedor, valor = :valor, data_entrega = '$data_entrega', nota = '$nota', obs = :obs where id = '$id'");
}

$query->bindValue(":fornecedor", "$fornecedor");
$query->bindValue(":valor", "$valor");
$query->bindValue(":obs", "$obs");
$query->execute();


echo "Salvo com Sucesso";


$data_entregaF = implode('/', array_reverse(explode('-', $data_entrega)));
$mensagem = '';


//Confirma envio via Whats da nota para representante
if ($id == '' && $api_whatsapp == 'Sim') {

	$query = $pdo->query("SELECT * from clientes where id = '$cliente' ");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);

	$nome_cliente = $res[0]['nome'];
	$telefone = $res[0]['telefone'];

	$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone);

	if ($id == "") {

		$mensagem .= '*Novo Orçamento* %0A%0A';
		$mensagem .= 'Empresa: *' . $nome_sistema . '* %0A';
		$mensagem .= 'Cliente: *' . $nome_cliente . '* %0A%0A';
		$mensagem .= 'Previsão do Orçamento: *' . $data_entregaF . '* %0A%0A';
		$mensagem .= '*Acompanhe o status do seu Orçamento* %0A%0A';
		$mensagem .= '_Use seu telefone e sua senha para acesso o painel, após acessar, troque a senha para uma de sua preferência!_ %0A%0A';
		$mensagem .= 'Login: *' . $telefone . '* %0A';
		$mensagem .= 'Senha: *' . '123' . '* %0A';
		$mensagem .= 'Acessar Painel: *' . $url_sistema . '* %0A%0A';

		require('../../../apis/api_texto.php');
	}
}
