<?php
//definir fuso horário timezone
date_default_timezone_set('America/Fortaleza');

/** @var string $servidor */
/** @var string $banco */
/** @var string $usuario */
/** @var string $senha */
/** @var PDO $pdo */


//dados conexão bd local
$servidor = 'localhost';
$banco = 'escala';
$usuario = 'root';
$senha = '';


try {

	$pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $e) {

	echo 'Erro ao conectar ao banco de dados!';
	//echo $e;
}


// Pegar a URL do sistema
$url_sistema = "https://$_SERVER[HTTP_HOST]/";
$url = explode("//", $url_sistema);
if ($url[1] == 'localhost/') {
	$url_sistema = "http://$_SERVER[HTTP_HOST]/projetos/escala/";
}


//variaveis globais
$nome_sistema = 'Represetante Comercial';
$email_sistema = 'jeffersonjcl@gmail.com';
$telefone_sistema = '(85) 99985-5584';
$endereco_sistema = 'Rua Seis, 8, Ap 404, Fortaleza-CE';
$instagram_sistema = 'jeffersonjcl';
$logo_sistema = 'logo.png';
$logo_rel = 'logo.jpg';
$icone_sistema = 'icone.png';
$api_whatsapp = 'Não';
$token = '';
$instancia = '';
$chave_pix = '';
$marca_dagua = 'Sim';
$impressao_automatica = 'Não';
$fonte_comprovante = '12';
$cnpj_sistema = '';
$cobranca_auto = 'Não';
$data_cobranca = '';
$logo_painel = 'logo_painel.png';
$ativo_sistema = 'Sim';
$entrar_automatico = 'Sim';
$mostrar_preloader = 'Sim';
$mensagem_auto = 'Sim';
$whatsapp_sistema = '';


$query = $pdo->query("SELECT * from config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas == 0) {
	$pdo->query("INSERT INTO config SET nome = '$nome_sistema', email = '$email_sistema', telefone = '$telefone_sistema', logo = 'logo.png', logo_rel = 'logo.jpg', logo_painel = 'logo_painel.png', icone = 'icone.png', api_whatsapp = 'Não', marca_dagua = 'Sim', impressao_automatica = 'Não', fonte_comprovante = '12', cobranca_auto = 'Não', ativo = 'Sim', mostrar_preloader = 'Sim', mensagem_auto = 'Sim'");
} else {
	$nome_sistema = $res[0]['nome'];
	$email_sistema = $res[0]['email'];
	$telefone_sistema = $res[0]['telefone'];
	$endereco_sistema = $res[0]['endereco'];
	$instagram_sistema = $res[0]['instagram'];
	$logo_sistema = $res[0]['logo'];
	$logo_rel = $res[0]['logo_rel'];
	$icone_sistema = $res[0]['icone'];
	$api_whatsapp = $res[0]['api_whatsapp'];
	$token = $res[0]['token'];
	$instancia = $res[0]['instancia'];
	$chave_pix = $res[0]['chave_pix'];
	$marca_dagua = $res[0]['marca_dagua'];
	$impressao_automatica = $res[0]['impressao_automatica'];
	$fonte_comprovante = $res[0]['fonte_comprovante'];
	$cnpj_sistema = $res[0]['cnpj'];
	$cobranca_auto = $res[0]['cobranca_auto'];
	$data_cobranca = $res[0]['data_cobranca'];
	$logo_painel = $res[0]['logo_painel'];
	$ativo_sistema = $res[0]['ativo'];
	$entrar_automatico = $res[0]['entrar_automatico'];
	$mostrar_preloader = $res[0]['mostrar_preloader'];
	$mensagem_auto = $res[0]['mensagem_auto'];
	

	$whatsapp_sistema = '55' . preg_replace('/[ ()-]+/', '', $telefone_sistema);


	if ($ativo_sistema != 'Sim' and $ativo_sistema != '') { ?>
		<style type="text/css">
			@media only screen and (max-width: 700px) {
				.imgsistema_mobile {
					width: 300px;
				}
			}
		</style>
		<div style="text-align: center; margin-top: 100px">
			<img src="<?php echo $url_sistema ?>img/bloqueio.png" class="imgsistema_mobile">
		</div>
<?php
		exit();
	}
}
?>