<?php
require_once("../../conexao.php");
require_once("../funcoes/extenso.php");

$id = $_GET['id'];

$query = $pdo->query("SELECT * from pagar where id = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($res) > 0) {
  $descricao = $res[0]['descricao'];
  $funcionario = $res[0]['funcionario'];
  $fornecedor = $res[0]['fornecedor'];
  $cliente = $res[0]['cliente'];
  $valor = $res[0]['valor'];
  $data_pgto = $res[0]['data_pgto'];


  $nome_pessoa = '';
  $telefone_pessoa = '';
  $pix_pessoa = '';
  $tipo_pessoa = 'Pessoa';

  if ($fornecedor != 0 || $funcionario != 0 || $cliente != 0) {
    if ($fornecedor != 0) {
      $tab = 'fornecedores';
      $id_pessoa = $fornecedor;
      $tipo_pessoa = 'Fornecedor';
    }

    if ($funcionario != 0) {
      $tab = 'usuarios';
      $id_pessoa = $funcionario;
      $tipo_pessoa = 'Funcionário';
    }

    if ($cliente != 0) {
      $tab = 'clientes';
      $id_pessoa = $cliente;
      $tipo_pessoa = 'Cliente';
    }

    //nome pessoa
    $query2 = $pdo->query("SELECT * FROM $tab where id = '$id_pessoa'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    $total_reg2 = @count($res2);
    if ($total_reg2 > 0) {
      $nome_pessoa = $res2[0]['nome'];
      $telefone_pessoa = $res2[0]['telefone'];
      $pix_pessoa = @$res2[0]['pix'];
    } else {
      $nome_pessoa = '';
      $telefone_pessoa = '';
      $pix_pessoa = '';
    }
  }


  $valorF = @number_format($valor, 2, ',', '.');
  $data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
  $valor_extenso = explode(".", $valor);
  $valor_extenso1 = $valor_extenso[0] . ' reais';
  if ($valor_extenso[1] > 0) {
    $valor_extenso2 = ' e ' . $valor_extenso[1] . ' centavos';
  } else {
    $valor_extenso2 = '';
  }
}

?>




<!DOCTYPE html>
<html>

<head>
  <title>Recibo de Conta</title>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">


  <style>
    @page {
      margin: 0px;

    }

    body {
      padding: 10px;
    }

    * {
      box-sizing: border-box;
    }

    .receipt-main {
      width: 95%;
      padding: 15px;
      font-size: 12px;
      border: 1px solid #000;
    }

    .receipt-title {
      text-align: center;
      text-transform: uppercase;
      font-size: 20px;
      font-weight: 600;
      margin: 0;
    }

    .receipt-label {
      font-weight: 600;
    }

    .text-large {
      font-size: 16px;
    }

    .receipt-section {
      margin-top: 10px;
    }

    .receipt-footer {
      text-align: center;
      background: #ff0000;
    }

    .receipt-signature {
      height: 80px;
      margin: 50px 0;
      padding: 0 50px;
      background: #fff;

      .receipt-line {
        margin-bottom: 10px;
        border-bottom: 1px solid #000;
      }

      p {
        text-align: center;
        margin: 0;
      }

      .direita {
        position: absolute;
        right: 30px;
      }


      .imagem {
        width: 150px;
        position: absolute;
        left: 15px;
        top: 20px;

      }
    }
  </style>

</head>

<body>



  <div class="receipt-main">

    <img class="imagem" src="<?php echo $url_sistema ?>img/logo.jpg" width="200px">



    <p class="receipt-title">Recibo de Pagamento</p>
    <br>

    <div class="receipt-section pull-left">
      <span class="receipt-label text-large">Número:</span>
      <span class="text-large"><?php echo date('Y/m') ?></span>

      <span class="text-large receipt-label direita">VALOR R$ <?php echo $valorF ?></span>

    </div>



    <div class="clearfix"></div>
    <br>

    <div class="receipt-section">
      <span><big>
          Eu, <b><?php echo $nome_pessoa ?></b> atesto que recebi da empresa <?php echo $nome_sistema ?> a quantia de R$ <b><?php echo $valorF ?> </b> (<?php echo valor_por_extenso($valor_extenso1) ?> <?php echo $valor_extenso2 ?>) na data <b><?php echo $data_pgtoF ?></b> correspondente a(ao) <?php echo $descricao ?>.

        </big></span>

    </div>

    <br><br>
    <div align="center">


      <br> <br>


      ______________________________________________________________________<br>
      (<b>ASSINATURA</b>)

    </div>

    <div align="center">
      <?php echo mb_strtoupper($nome_sistema) ?> - <?php echo $telefone_sistema ?><br>
      <?php echo mb_strtoupper('CNPJ ' . $cnpj_sistema) ?>

    </div>

  </div>




</body>

</html>