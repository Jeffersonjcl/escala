<?php
require_once("conexao.php");

if (!isset($_REQUEST['email']) || !isset($_REQUEST['token'])) {
	header('location: ' . $url_sistema);
	exit;
}

$statement = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND token=?");
$statement->execute([$_REQUEST['email'], $_REQUEST['token']]);
$result = $statement->fetchAll();
$tot = $statement->rowCount();
if ($tot == 0) {

	header('location: ' . $url_sistema);
	exit;
}

$_SESSION['temp_reset_email'] = $_REQUEST['email'];
$_SESSION['temp_reset_token'] = $_REQUEST['token'];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
	<!-- META DATA -->
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="Fluxo Comunicação Inteligente">
	<meta name="Author" content="Jefferson Lima">
	<meta name="Keywords" content="fluxo, comunicacao, inteligente, marketing, whatsapp" />

	<!-- TITLE -->
	<title><?php echo $nome_sistema ?></title>


	<link rel="icon" href="img/icone.png" type="image/x-icon" />
	<link href="assets/css/icons.css" rel="stylesheet">
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/custom.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">
	<link href="assets/css/skin-modes.css" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet">

</head>

<!-- GLOBAL-LOADER -->
<!-- <div id="global-loader">
	<img src="img/loader.gif" class="loader-img loader loader_mobile" alt="">
</div> -->
<!-- /GLOBAL-LOADER -->

<body class="ltr error-page1 bg-primary-login" id="pagina">


	<div class="square-box">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
	</div>

	<div class="page">
		<div class="page-single">
			<div class="container">
				<div class="row">
					<div class="col-xl-5 col-lg-6 col-md-8 col-sm-8 col-xs-10 card-sigin-main mx-auto my-auto py-4 justify-content-center">
						<div class="card-sigin">
							<!-- Demo content-->
							<div class="main-card-signin d-md-flex">
								<div class="wd-100p">
									<div class="d-flex mb-4 justify-content-center"><img src="img/logo.png" class="sign-favicon" alt="logo" width="90%" oncontextmenu="return false" style="pointer-events: none; border-radius: 10px;"></div>
									<div class="">
										<div class="main-signup-header">

											<div class="panel panel-primary">

												<div class="panel-body tabs-menu-body border-0 p-3">

													<?php
													if (isset($_SESSION['msg'])) {

														echo '<div class="alert alert-danger mg-b-0 mb-3 alert-dismissible fade show" role="alert">
														<strong><span class="alert-inner--icon"><i class="fe fe-slash"></i></span></strong> ' . $_SESSION['msg'] . '!
														<button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"><span aria-hidden="true">&times;</span></button>
														</div>';

														unset($_SESSION['msg']);
													}
													?>

													<form method="post" id="form-recuperar">

														<div class="form-group">

															<label class="control-label">Nova Senha</label>
															<input placeholder="Digite uma nova senha" class="form-control" type="password" name="senha" id="senha" required>

														</div>

														<div class="form-group">

															<label class="control-label">Repita Senha</label>
															<input placeholder="Repetir Senha" class="form-control" type="password" name="re_senha" id="re_senha" required>

														</div>

														<input type="hidden" name="token" id="token" value="">

														<input type="hidden" name="email" id="email" value="<?php echo $_REQUEST['email'] ?>">

														<button type="submit" class="btn btn-lg btn-primary btn-block">Alterar Senha</button>

													</form>
												</div>

											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>

</html>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<script type="text/javascript">
	$("#form-recuperar").submit(function() {

		$('#mensagem-recuperar').text("Alterando...")

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "alterar-senha.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem-recuperar').text('');
				$('#mensagem-recuperar').removeClass()
				if (mensagem.trim() == "Senha alterada com Sucesso") {
					//$('#btn-fechar-rec').click();					
					$('#senha').val('');
					$('#re_senha').val('');
					alert('Sua Senha foi alterada com Sucesso!!');
					window.location = "index.php";

				} else {

					$('#mensagem-recuperar').addClass('text-danger')
					$('#mensagem-recuperar').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>