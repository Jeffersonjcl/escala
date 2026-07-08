<?php
@session_start();
require_once("../conexao.php");
require_once("verificar.php");

/** @var string $data_atual */
/** @var string $mes_atual */
/** @var string $ano_atual */
/** @var string $data_mes */
/** @var string $data_ano */
/** @var string $data_inicio_mes */
/** @var string $data_inicio_ano */
/** @var string $data_final_mes */
/** @var string $bissexto */
/** @var string $pagina */
/** @var string $pag_inicial */
/** @var string $nivel_usuario */
/** @var string $nome_usuario */
/** @var string $email_usuario */
/** @var string $telefone_usuario */
/** @var string $senha_usuario */
/** @var string $foto_usuario */
/** @var string $endereco_usuario */
/** @var string $id_usuario */
/** @var string $query */
/** @var string $res */
/** @var string $linhas */
/** @var string $mostrar_preloader */
/** @var string $nome_sistema */
/** @var string $url_sistema */
/** @var string $configuracoes */
/** @var string $home */
/** @var string $policiais */
/** @var string $funcoes */
/** @var string $escalas */
/** @var string $escalas_listagem */
/** @var string $usuarios */
/** @var string $grupos */
/** @var string $acessos */
/** @var string $menu_efetivo */
/** @var string $menu_escala */
/** @var string $menu_administracao */
/** @var string $escalas_pendentes */
/** @var string $saudacao */
/** @var string $dataFormatada */
/** @var string $diaMes */
/** @var string $diaSemana */
/** @var string $mes */
/** @var string $ano */
/** @var string $nomesDiasDaSemana */
/** @var string $nomeDosMeses */
/** @var string $hora */
/** @var string $url */
/** @var string $chave_pix */
/** @var string $mensagem_auto */
/** @var string $cobranca_auto */
/** @var string $marca_dagua */
/** @var string $api_whatsapp */
/** @var string $token */
/** @var string $mensagem_whatsapp */
/** @var string $instancia */
/** @var string $impressao_automatica */
/** @var string $fonte_comprovante */
/** @var string $entrar_automatico */


$mostrar_preloader = 'Sim';

$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_mes = $ano_atual . "-" . $mes_atual . "-01";
$data_ano = $ano_atual . "-01-01";

$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01";
$data_inicio_ano = $ano_atual . "-01-01";

if ($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11') {

    $data_final_mes = $ano_atual . '-' . $mes_atual . '-30';
} else if ($mes_atual == '02') {

    $bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));

    if ($bissexto == 1) {

        $data_final_mes = $ano_atual . '-' . $mes_atual . '-29';
    } else {
        $data_final_mes = $ano_atual . '-' . $mes_atual . '-28';
    }
} else {

    $data_final_mes = $ano_atual . '-' . $mes_atual . '-31';
}

$pag_inicial = 'home';

if (@$_SESSION['nivel'] != 'Administrador') {

    require_once("verificar_permissoes.php");
}

if (@$_GET['pagina'] != "") {

    $pagina = @$_GET['pagina'];
} else {
    $pagina = $pag_inicial;
}

$id_usuario = @$_SESSION['id'];
$query = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
    $nome_usuario = $res[0]['nome'];
    $email_usuario = $res[0]['email'];
    $telefone_usuario = $res[0]['telefone'];
    $senha_usuario = $res[0]['senha'];
    $nivel_usuario = $res[0]['nivel'];
    $foto_usuario = $res[0]['foto'];
    $endereco_usuario = $res[0]['endereco'];
} else {
    echo '<script>window.location="../"</script>';
    exit();
}
?>
<!DOCTYPE HTML>
<html lang="pt-BR" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>


    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Sistema de Escala Operacional - 1ºPel/1ªCia/1ºBPRAIO">
    <meta name="Author" content="1º BPRAIO">
    <meta name="Keywords" content="escala, policia, bpraio, plantao" />

    <!-- Ancora todos os caminhos relativos (css/js/ajax) em painel/, independente
         da URL "bonita" exibida pelo navegador (ex: painel/funcoes/) -->
    <base href="<?php echo $url_sistema ?>painel/">

    <title><?php echo $nome_sistema ?></title>

    <link rel="icon" href="../img/icone.png" type="image/x-icon" />
    <link href="../assets/css/icons.css" rel="stylesheet">
    <link id="style" href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/style-dark.css" rel="stylesheet">
    <link href="../assets/css/style-transparent.css" rel="stylesheet">
    <link href="../assets/css/skin-modes.css" rel="stylesheet" />
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="../assets/css/custom.css" rel="stylesheet" />
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>

    <!-- fontawesome-->
    <link rel="stylesheet" type="text/css" href="../fontawesome/css/all.min.css">

    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>


</head>

<body class="ltr main-body app sidebar-mini">

    <?php if ($mostrar_preloader == 'Sim') { ?>
        <!-- GLOBAL-LOADER -->
        <div id="global-loader">
            <img src="../img/loader.gif" class="loader-img loader_mobile" alt="">
        </div>

    <?php } ?>


    <!-- Page -->
    <div class="page">
        <div>
            <!-- APP-HEADER1 -->
            <div class="main-header side-header sticky nav nav-item">
                <div class=" main-container container-fluid">
                    <div class="main-header-left ">
                        <div class="responsive-logo">
                            <a href="index" class="header-logo">
                                <img src="../img/logo_painel.png" class="mobile-logo logo-1" alt="logo" style="width:40% !important; margin-left: -120px !important">
                                <img src="../img/logo_painel.png" class="mobile-logo dark-logo-1" alt="logo" style="width:40% !important; margin-left: 60px !important">
                            </a>
                        </div>
                        <div class="app-sidebar__toggle" data-bs-toggle="sidebar">
                            <a class="open-toggle" href="javascript:void(0);"><i class="header-icon fe fe-align-left"></i></a>
                            <a class="close-toggle" href="javascript:void(0);"><i class="header-icon fe fe-x"></i></a>
                        </div>
                        <div class="logo-horizontal">
                            <a href="index" class="header-logo">
                                <img src="../img/logo_painel.png" class="mobile-logo logo-1" alt="logo">
                                <img src="../img/logo_painel.png" class="mobile-logo dark-logo-1" alt="logo">
                            </a>
                        </div>
                        <div class="main-header-center ms-4 d-sm-none d-md-none d-lg-block form-group">

                        </div>
                    </div>


                    <!-- Adiiconar data -->
                    <div class="ocultar_mobile">
                        <?php
                        $hora = date('H');

                        $diaMes = date('d');
                        $diaSemana = date('w');
                        $mes = date('n') - 1;
                        $ano = date('Y');

                        $nomesDiasDaSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

                        $nomeDosMeses = ['Janeiro', 'Fevereiro', 'março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

                        $dataFormatada = $nomesDiasDaSemana[$diaSemana] . ', ' . $diaMes . ' de ' . $nomeDosMeses[$mes] . ' de ' . $ano;

                        if ($hora < 12 && $hora >= 6)
                            $saudacao = "☀️ Bom Dia";

                        if ($hora >= 12 && $hora < 18)
                            $saudacao = "☕ Boa Tarde ";

                        if ($hora >= 18 && $hora <= 23)
                            $saudacao = "🌙 Boa Noite";
                        if ($hora < 6 && $hora >= 0)
                            $saudacao = "💤🛌🏼 Boa Madrugada";

                        ?>

                        <div style="font-size: 15px; color: white">
                            <?php echo $saudacao . ' <b>' . $nome_usuario ?>
                        </div>

                    </div>

                    <!-- Fim da data -->

                    <div class="main-header-right">


                        <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            <div class="" id="navbarSupportedContent-4">

                                <ul class="nav nav-item header-icons navbar-nav-right ms-auto">


                                    <li class="dropdown nav-item" style="opacity: 0">
                                        ------------
                                    </li>

                                    <li class="dropdown nav-item">
                                        <a class="new nav-link theme-layout nav-link-bg layout-setting">
                                            <span class="dark-layout">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24">
                                                    <path d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z" />
                                                </svg></span>
                                            <span class="light-layout">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24">
                                                    <path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z" />
                                                </svg>
                                            </span>
                                        </a>
                                    </li>

                                    <li class="dropdown nav-item  main-header-message <?php echo @$escalas_listagem ?>">

                                        <?php
                                        //escalas publicadas pendentes de assinatura
                                        $query = $pdo->query("SELECT * from escalas_diarias where status = 'Publicada' and (assinado_escalante = 0 or assinado_comandante = 0) order by data_escala asc");
                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                        $escalas_pendentes = @count($res);
                                        ?>

                                        <a class="new nav-link " data-bs-toggle="dropdown" href="escalas_listagem">
                                            <small><i class="fa fa-signature" style="color: white"></i></small>
                                            <span class="badge  header-badge" style="background:red"><?php echo $escalas_pendentes ?></span>
                                        </a>

                                        <div class="dropdown-menu">
                                            <div class="menu-header-content text-start border-bottom">
                                                <div class="d-flex">
                                                    <h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Escalas Pendentes de Assinatura</h6>
                                                </div>
                                                <p class="dropdown-title-text subtext mb-0 op-6 pb-0 tx-12 "><?php echo $escalas_pendentes ?> Escala(s) aguardando assinatura</p>
                                            </div>

                                            <div class="main-message-list chat-scroll">
                                                <?php
                                                if ($escalas_pendentes > 0) {
                                                    foreach ($res as $e) {
                                                        $data_vencF = implode('/', array_reverse(explode('-', $e['data_escala'])));
                                                ?>
                                                        <a href="escalas_listagem" class="dropdown-item d-flex border-bottom">
                                                            <div class="wd-90p">
                                                                <div class="d-flex">
                                                                    <h5 class="mb-0 name" style="color:red">Turno <?php echo $e['turno'] ?></h5>
                                                                </div>
                                                                <p class="mb-0 desc"><?php echo $e['assinado_escalante'] ? 'Falta assinatura do Comandante' : 'Falta assinatura do Escalante' ?></p>
                                                                <p class="time mb-0 text-start float-start ms-2"><?php echo $data_vencF ?></p>
                                                            </div>
                                                        </a>
                                                <?php }
                                                }
                                                ?>
                                            </div>
                                            <div class="text-center dropdown-footer">
                                                <a class="btn btn-success btn-sm btn-block text-center" href="escalas_listagem">Ver Todas</a>
                                            </div>
                                        </div>
                                    </li>



                                    <li class="nav-item full-screen fullscreen-button">
                                        <a class="new nav-link full-screen-link" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24">
                                                <path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z" />
                                            </svg></a>
                                    </li>

                                    <li class="dropdown main-profile-menu nav nav-item nav-link ps-lg-2">
                                        <a class="new nav-link profile-user d-flex" href="#" data-bs-toggle="dropdown"><img src="images/perfil/<?php echo $foto_usuario ?>"></a>
                                        <div class="dropdown-menu">
                                            <div class="menu-header-content p-3 border-bottom">
                                                <div class="d-flex wd-100p">
                                                    <div class="main-img-user"><img src="images/perfil/<?php echo $foto_usuario ?>"></div>
                                                    <div class="ms-3 my-auto">
                                                        <h6 class="tx-15 font-weight-semibold mb-0"><?php echo $nome_usuario ?></h6><span class="dropdown-title-text subtext op-6  tx-12"><?php echo $nivel_usuario ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="dropdown-item" href="" data-bs-target="#modalPerfil" data-bs-toggle="modal"><i class="fa fa-user"></i>Perfil</a>

                                            <span class="<?php echo $configuracoes ?>"><a class="dropdown-item " href="" data-bs-target="#modalConfig" data-bs-toggle="modal">

                                                    <i class="fa fa-cogs "></i>


                                                    Configurações</a>
                                            </span>
                                            <a class="dropdown-item" href="logout"><i class="fa fa-arrow-left"></i> Sair</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--PAINEL LATERAL-->
            <div class="sticky">
                <aside class="app-sidebar">
                    <div class="main-sidebar-header active">
                        <a class="header-logo active" href="index">
                            <img src="../img/logo_painel.png" class="main-logo  desktop-logo" alt="logo" style="width: 100%;">
                            <img src="../img/logo_painel.png" class="main-logo  desktop-dark" alt="logo" style="width: 100%;">
                            <img src="../img/icone.png" class="main-logo  mobile-logo" alt="logo">
                            <img src="../img/icone.png" class="main-logo  mobile-dark" alt="logo">
                        </a>
                    </div>
                    <div class="main-sidemenu">
                        <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                            </svg></div>
                        <ul class="side-menu">

                            <li class="slide <?php echo @$home ?>">
                                <a class="side-menu__item" href="index">
                                    <i class="fa fa-home text-white"></i>
                                    <span class="side-menu__label" style="margin-left: 15px">Dashboard</span></a>
                            </li>


                            <li class="slide <?php echo @$menu_efetivo ?>">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="fa fa-users text-white mt-1"></i>
                                    <span class="side-menu__label" style="margin-left: 15px">Efetivo</span><i class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">

                                    <li class="<?php echo @$policiais ?>"><a class="slide-item" href="policiais"> Policiais</a></li>

                                    <li class="<?php echo @$funcoes ?>"><a class="slide-item" href="funcoes"> Funções</a></li>

                                </ul>
                            </li>


                            <li class="slide <?php echo @$menu_escala ?>">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="fa fa-calendar-days text-white mt-1"></i>
                                    <span class="side-menu__label" style="margin-left: 15px">Escala</span><i class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">

                                    <li class="<?php echo @$escalas ?>"><a class="slide-item" href="escalas"> Criar Escala</a></li>

                                    <li class="<?php echo @$escalas_listagem ?>"><a class="slide-item" href="escalas_listagem"> Consulta Mensal</a></li>

                                </ul>
                            </li>


                            <li class="slide <?php echo @$menu_administracao ?>">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="fa fa-gear text-white mt-1"></i>
                                    <span class="side-menu__label" style="margin-left: 15px">Administração</span><i class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">

                                    <li class="<?php echo @$usuarios ?>"><a class="slide-item" href="usuarios"> Usuários</a></li>

                                    <li class="<?php echo @$grupos ?>"><a class="slide-item" href="grupos"> Grupos de Acessos</a></li>

                                    <li class="<?php echo @$acessos ?>"><a class="slide-item" href="acessos"> Acessos</a></li>

                                </ul>
                            </li>

                        </ul>

                        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                            </svg></div>
                    </div>
                </aside>
            </div>
        </div>

        <!-- MAIN-CONTENT -->
        <div class="main-content app-content">

            <!-- container -->
            <div class="main-container container-fluid">

                <?php
                echo "<script>localStorage.setItem('pagina', '$pagina')</script>";
                require_once('paginas/' . $pagina . '.php');
                ?>

            </div>
            <!-- Container closed -->
        </div>
        <!-- MAIN-CONTENT CLOSED -->


        <!-- FOOTER -->
        <div class="main-footer">
            <div class="container-fluid pt-0 ht-100p">
                Copyright © <?php echo date('Y'); ?> <a href="https://wa.me/5585999855584" target="_blank" class="text-primary">FamilyTecnoArt</a>. Todos os direitos reservados
            </div>
        </div>
        <!-- FOOTER END -->

    </div>
    <!-- End Page -->

    <!-- BUYNOW-MODAL -->



    <a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>


    <!-- GRAFICOS -->
    <script src="../assets/plugins/chart.js/Chart.bundle.min.js"></script>
    <script src="../assets/js/apexcharts.js"></script>

    <!--INTERNAL  INDEX JS -->
    <script src="../assets/js/index.js"></script>

    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/plugins/moment/moment.js"></script>
    <script src="../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../assets/plugins/perfect-scrollbar/p-scroll.js"></script>
    <script src="../assets/js/eva-icons.min.js"></script>
    <script src="../assets/plugins/side-menu/sidemenu.js"></script>
    <script src="../assets/js/sticky.js"></script>
    <script src="../assets/plugins/sidebar/sidebar.js"></script>
    <script src="../assets/plugins/sidebar/sidebar-custom.js"></script>


    <!-- INTERNAL DATA TABLES -->
    <script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="../assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="../assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
    <script src="../assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
    <script src="../assets/plugins/datatable/js/jszip.min.js"></script>
    <script src="../assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
    <script src="../assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
    <script src="../assets/plugins/datatable/js/buttons.html5.min.js"></script>
    <script src="../assets/plugins/datatable/js/buttons.print.min.js"></script>
    <script src="../assets/plugins/datatable/js/buttons.colVis.min.js"></script>
    <script src="../assets/plugins/datatable/dataTables.responsive.min.js"></script>
    <script src="../assets/plugins/datatable/responsive.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- POPOVER JS -->
    <script src="../assets/js/popover.js"></script>

    <script src="../assets/js/themecolor.js"></script>
    <script src="../assets/js/custom.js"></script>

    <!--INTERNAL  INDEX JS -->
    <script src="../assets/js/index.js"></script>


</body>

</html>


<!-- Modal Perfil -->
<div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Alterar Dados</h4>
                <button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
            </div>

            <form id="form-perfil">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Nome</label>
                            <input type="text" class="form-control" id="nome_perfil" name="nome" placeholder="Seu Nome" value="<?php echo @$nome_usuario ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email_perfil" name="email" placeholder="Seu Nome" value="<?php echo @$email_usuario ?>" required>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>Telefone</label>
                            <input type="text" class="form-control" id="telefone_perfil" name="telefone" placeholder="Seu Telefone" value="<?php echo @$telefone_usuario ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Senha</label>
                            <input type="password" class="form-control" id="senha_perfil" name="senha" placeholder="Senha" value="<?php echo @$senha_usuario ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Confirmar Senha</label>
                            <input type="password" class="form-control" id="conf_senha_perfil" name="conf_senha" placeholder="Confirmar Senha" value="" required>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label>Endereço</label>
                            <input type="text" class="form-control" id="endereco_perfil" name="endereco" placeholder="Seu Endereço" value="<?php echo @$endereco_usuario ?>">
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-8 mb-3">
                            <label>Foto</label>
                            <input type="file" class="form-control" id="foto_perfil" name="foto" value="<?php echo @$foto_usuario ?>" onchange="carregarImgPerfil()">
                        </div>

                        <div class="col-md-4 mb-3">
                            <img src="images/perfil/<?php echo $foto_usuario ?>" width="80px" id="target-usu">

                        </div>

                    </div>


                    <input type="hidden" name="id_usuario" value="<?php echo @$id_usuario ?>">


                    <br>
                    <small>
                        <div id="msg-perfil" align="center"></div>
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar<i class="fa-solid fa-check ms-2"></i></button>
                </div>

            </form>
        </div>
    </div>
</div>





<!-- Modal Config -->
<div class="modal fade" id="modalConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Alterar Configurações</h4>
                <button id="btn-fechar-confug" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
            </div>
            <form id="form-config">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4">
                            <label>Nome da Empresa</label>
                            <input type="text" class="form-control" id="nome_sistema" name="nome_sistema" placeholder="Nome de Empresa" value="<?php echo @$nome_sistema ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label>Email Sistema</label>
                            <input type="email" class="form-control" id="email_sistema" name="email_sistema" placeholder="Email do Sistema" value="<?php echo @$email_sistema ?>">
                        </div>

                        <div class="col-md-4">
                            <label>Telefone Sistema</label>
                            <input type="text" class="form-control" id="telefone_sistema" name="telefone_sistema" placeholder="Telefone do Sistema" value="<?php echo @$telefone_sistema ?>" required>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-4">
                            <label>CNPJ</label>
                            <input type="text" class="form-control" id="cnpj_sistema" name="cnpj_sistema" placeholder="CNPJ do Sistema" value="<?php echo @$cnpj_sistema ?>">
                        </div>

                        <div class="col-md-4">
                            <label>Chave Pix</label>
                            <input type="text" class="form-control" name="chave_pix" placeholder="CNPJ xxxxxxxxxxxx" value="<?php echo @$chave_pix ?>">
                        </div>

                        <div class="col-md-4">
                            <label>Instagram</label>
                            <input type="text" class="form-control" id="instagram_sistema" name="instagram_sistema" placeholder="Link do Instagram" value="<?php echo @$instagram_sistema ?>">
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-12">
                            <label>Endereço <small>(Rua Número Bairro e Cidade)</small></label>
                            <input type="text" class="form-control" id="endereco_sistema" name="endereco_sistema" placeholder="Rua X..." value="<?php echo @$endereco_sistema ?>">
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-3">
                            <label>Mensagem Automática</label>
                            <select class="form-select" name="mensagem_auto">
                                <option <?php if (@$mensagem_auto == 'Sim') { ?> selected <?php } ?> value="Sim">Sim</option>
                                <option <?php if (@$mensagem_auto == 'Não') { ?> selected <?php } ?> value="Não">Não</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Cobrança Automática</label>
                            <select class="form-select" name="cobranca_auto">
                                <option <?php if (@$cobranca_auto == 'Sim') { ?> selected <?php } ?> value="Sim">Sim</option>
                                <option <?php if (@$cobranca_auto == 'Não') { ?> selected <?php } ?> value="Não">Não</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Marca D'agua Relatório</label>
                            <select class="form-select" name="marca_dagua">
                                <option <?php if (@$marca_dagua == 'Sim') { ?> selected <?php } ?> value="Sim">Sim</option>
                                <option <?php if (@$marca_dagua == 'Não') { ?> selected <?php } ?> value="Não">Não</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Api Whatsapp</label>
                            <select class="form-select" name="api_whatsapp">
                                <option <?php if (@$api_whatsapp == 'Não') { ?> selected <?php } ?> value="Não">Não</option>
                                <option <?php if (@$api_whatsapp == 'Sim') { ?> selected <?php } ?> value="Sim">Sim</option>
                            </select>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <label>Token Api Whatsapp</label>
                            <input type="text" class="form-control" name="token" placeholder="Token da API" value="<?php echo @$token ?>">
                        </div>

                        <div class="col-md-6">
                            <label>Instância Api Whats</label>
                            <input type="text" class="form-control" name="instancia" placeholder="Instância Whatsapp" value="<?php echo @$instancia ?>">
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-3">
                            <label>Impressão Automática</label>
                            <select class="form-select" name="impressao_automatica">
                                <option <?php if (@$impressao_automatica == 'Sim') { ?> selected <?php } ?> value="Sim">Sim</option>
                                <option <?php if (@$impressao_automatica == 'Não') { ?> selected <?php } ?> value="Não">Não</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Fonte Comprovante</label>
                            <input type="number" class="form-control" name="fonte_comprovante" placeholder="Tamanho da Fonte" value="<?php echo @$fonte_comprovante ?>">
                        </div>

                        <div class="col-md-3">
                            <label>Entrar Automáticamente</label>
                            <select name="entrar_automatico" class="form-select">
                                <option value="Sim" <?php if ($entrar_automatico == 'Sim') { ?> selected <?php } ?>>Sim</option>
                                <option value="Não" <?php if ($entrar_automatico == 'Não') { ?> selected <?php } ?>>Não</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Mostrar PreLoader</label>
                            <select name="mostrar_preloader" class="form-select">
                                <option value="Sim" <?php if ($mostrar_preloader == 'Sim') { ?> selected <?php } ?>>Sim</option>
                                <option value="Não" <?php if ($mostrar_preloader == 'Não') { ?> selected <?php } ?>>Não</option>
                            </select>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Logo (Escura) (*png)</label>
                                <input class="form-control" type="file" name="foto-logo" onChange="carregarImgLogo();" id="foto-logo">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div id="divImg">
                                <img src="../img/<?php echo $logo_sistema ?>" width="80px" id="target-logo" style="background:#f5f5f5; margin-top: 30px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Logo Relatório (Escura) (*Jpg)</label>
                                <input class="form-control" type="file" name="foto-logo-rel" onChange="carregarImgLogoRel();" id="foto-logo-rel">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div id="divImg">
                                <img src="../img/<?php echo @$logo_rel ?>" width="80px" id="target-logo-rel" style="background:#f5f5f5; margin-top: 30px;">
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Logo Painel (Clara) (*Png)</label>
                                <input class="form-control" type="file" name="foto-logo-painel" onChange="carregarImgLogoPainel();" id="foto-logo-painel">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div id="divImg">
                                <img src="../img/<?php echo @$logo_painel ?>" width="70px" id="target-logo-painel" style="background:#f5f5f5; margin-top: 30px;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ícone (*Png)</label>
                                <input class="form-control" type="file" name="foto-icone" onChange="carregarImgIcone();" id="foto-icone">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div id="divImg">
                                <img src="../img/<?php echo $icone_sistema ?>" width="50px" id="target-icone" style="background:#f5f5f5;">
                            </div>
                        </div>

                    </div>

                    <br>
                    <small>
                        <div id="msg-config" align="center"></div>
                    </small>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar<i class="fa-solid fa-check ms-2"></i></button>
                </div>

            </form>
        </div>
    </div>
</div>





<!-- SweetAlert JS -->
<script src="js/sweetalert2.all.min.js"></script>
<script src="js/sweetalert1.min.css"></script>
<script src="js/alertas.js"></script>

<!-- Alertas -->
<script src="../assets/plugins/sweet-alert/sweetalert.min.js"></script>
<script src="../assets/plugins/sweet-alert/jquery.sweet-alert.js"></script>

<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<script type="text/javascript">
    function carregarImgPerfil() {
        var target = document.getElementById('target-usu');
        var file = document.querySelector("#foto_perfil").files[0];

        var reader = new FileReader();

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>






<script type="text/javascript">
    $("#form-perfil").submit(function() {

        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "editar-perfil",
            type: 'POST',
            data: formData,

            success: function(mensagem) {
                $('#msg-perfil').text('');
                $('#msg-perfil').removeClass()
                if (mensagem.trim() == "Editado com Sucesso") {

                    $('#btn-fechar-perfil').click();
                    location.reload();


                } else {

                    $('#msg-perfil').addClass('text-danger')
                    $('#msg-perfil').text(mensagem)
                }


            },

            cache: false,
            contentType: false,
            processData: false,

        });

    });
</script>






<script type="text/javascript">
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    $("#form-config").submit(function() {

        var formData = new FormData(this);

        $.ajax({
            url: "editar-config",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#msg-config').text('');
                $('#msg-config').removeClass()
                if (mensagem.trim() == "Editado com Sucesso") {

                    $('#btn-fechar-config').click();
                    location.reload();


                } else {

                    $('#msg-config').addClass('text-danger')
                    $('#msg-config').text(mensagem)
                }


            },

            cache: false,
            contentType: false,
            processData: false,

        });

    });
</script>




<script type="text/javascript">
    function carregarImgLogo() {
        var target = document.getElementById('target-logo');
        var file = document.querySelector("#foto-logo").files[0];

        var reader = new FileReader();

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
    function carregarImgLogoRel() {
        var target = document.getElementById('target-logo-rel');
        var file = document.querySelector("#foto-logo-rel").files[0];

        var reader = new FileReader();

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
    function carregarImgIcone() {
        var target = document.getElementById('target-icone');
        var file = document.querySelector("#foto-icone").files[0];

        var reader = new FileReader();

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>

<script type="text/javascript">
    function carregarImgLogoPainel() {
        var target = document.getElementById('target-logo-painel');
        var file = document.querySelector("#foto-logo-painel").files[0];

        var reader = new FileReader();

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>



<script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">
    bkLib.onDomLoaded(nicEditors.allTextAreas);
</script>

<script type="text/javascript">
    // A tag <base> (ver topo do arquivo) resolve href="#" para a URL base (painel/),
    // e não para a página atual. Sem isso, clicar em botões de ação (editar/excluir)
    // que usam href="#" + onclick faz o navegador recarregar painel/ (index.php)
    // em vez de apenas executar o onclick.
    $(document).on('click', 'a[href="#"]', function(e) {
        e.preventDefault();
    });
</script>