<?php
@session_start();
require_once("../../../conexao.php");

$tabela = 'notas';

$id_usuario = $_SESSION['id'];

$id = $_POST['id'];

$query = $pdo->query("SELECT * from $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$numero_nota = @$res[0]['numero_nota'];
$cliente = @$res[0]['cliente'];
$funcionario = @$res[0]['funcionario'];
$fornecedor = @$res[0]['fornecedor'];
$data = @$res[0]['data'];
$hora = @$res[0]['hora'];
$data_entrega = @$res[0]['data_entrega'];
$valor = @$res[0]['valor'];
$nota = @$res[0]['nota'];
$obs = @$res[0]['obs'];

$subtotal = str_replace(',', '.', $valor);

$query = $pdo->query("SELECT * FROM fornecedores where id = '$fornecedor'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$porc_comissao = $res[0]['comissao'];

// Calcula o Valor Devido a Loja
$porc_pagamento = 100.00 - $porc_comissao;
$valor_pagamento = $valor * ($porc_pagamento / 100);

//Consulta se ja existe o Recebimento se existir atualiza o Recebimento
$query = $pdo->query("SELECT * from receber where pago = 'Não' and id_ref = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(count($res) > 0) {

    $pdo->query("UPDATE receber SET descricao = 'Nota Nº $numero_nota', valor = '$subtotal', data_venc = '$data_entrega', data_lanc = curDate(), data_pgto = '',  usuario_lanc = '$id_usuario', usuario_pgto = '$id_usuario', arquivo = '$nota', pago = 'Não', cliente = '$cliente', referencia = 'Nota', hora = curTime(), saida = '', fornecedor = '$fornecedor' WHERE id_ref = '$id'");

} else {

    $pdo->query("INSERT INTO receber SET descricao = 'Nota Nº $numero_nota', valor = '$subtotal', data_venc = '$data_entrega', data_lanc = curDate(), data_pgto = '',  usuario_lanc = '$id_usuario', usuario_pgto = '$id_usuario', arquivo = '$nota', pago = 'Não', cliente = '$cliente', referencia = 'Nota', hora = curTime(), saida = '', fornecedor = '$fornecedor', id_ref = '$id'");

}

//Consulta se ja existe Pagamento se existir atualiza o Pagamento
$query = $pdo->query("SELECT * from pagar where pago = 'Não' and id_ref = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(count($res) > 0) {

    $pdo->query("UPDATE pagar SET descricao = 'Nota Nº $numero_nota', valor = '$valor_pagamento', data_venc = '$data_entrega', data_lanc = curDate(), data_pgto = '',  usuario_lanc = '$id_usuario', usuario_pgto = '$id_usuario', arquivo = '$nota', pago = 'Não', cliente = '', funcionario = '', referencia = 'Nota', saida = '', fornecedor = '$fornecedor' WHERE id_ref = '$id'");

} else {

    $pdo->query("INSERT INTO pagar SET descricao = 'Nota Nº $numero_nota', valor = '$valor_pagamento', data_venc = '$data_entrega', data_lanc = curDate(), data_pgto = '',  usuario_lanc = '$id_usuario', usuario_pgto = '$id_usuario', arquivo = '$nota', pago = 'Não', cliente = '', funcionario = '', referencia = 'Nota', saida = '', fornecedor = '$fornecedor', id_ref = '$id'");

}

//Mudar status da Nota
$pdo->query("UPDATE $tabela SET status = 'Aprovada' where id = '$id'");

echo 'Aprovado com Sucesso';