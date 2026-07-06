<?php
@session_start();
require_once("../../../conexao.php");
$pagina = 'notas';

$id_client_logado = $_SESSION['id_ref'];
$id_usuario = $_SESSION['id'];

$dataInicial = @$_POST['p1'];
$dataFinal = @$_POST['p2'];
$status = '%' . @$_POST['p3'] . '%';


if ($dataInicial == "") {
	$dataInicial = date('Y-m-01');
}

if ($dataFinal == "") {
	$dataFinal = date('Y-m-t');
}


//PEGAR O TOTAL ORÇAMENTOS PENDENTES
$query = $pdo->query("SELECT * from $pagina where status = 'Pendente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_notas = @count($res);


$query = $pdo->query("SELECT * from $pagina WHERE data_entrega >= '$dataInicial' and data_entrega <= '$dataFinal' and status LIKE '$status' and cliente = '$id_client_logado' order by id desc ");


echo <<<HTML
<small>
HTML;

$total_aprovado = 0;
$total_pendentes = 0;
$total_aprovadoF = 0;
$total_pendentesF = 0;

$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	echo <<<HTML
	<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead>
		<tr>
			<th>Nº Controle</th>
			<th>Nome da Loja</th>
			<th>Data da Nota</th>
			<th>Valor da Nota</th>
			<th>Nota</th>
			<th>Status</th>
			<th>Ações</th>
		</tr> 
	</thead> 

HTML;

	for ($i = 0; $i < $total_reg; $i++) {
		foreach ($res[$i] as $key => $value) {
		}
		$id = @$res[$i]['id'];
		$numero_nota = @$res[$i]['numero_nota'];
		$cliente = @$res[$i]['cliente'];
		$funcionario = @$res[$i]['funcionario'];
		$fornecedor = @$res[$i]['fornecedor'];
		$data = @$res[$i]['data'];
		$hora = @$res[$i]['hora'];
		$data_entrega = @$res[$i]['data_entrega'];
		$valor = @$res[$i]['valor'];
		$nota = @$res[$i]['nota'];
		$status = @$res[$i]['status'];
		$obs = @$res[$i]['obs'];

		//formatar os valores
		$dataF = implode('/', array_reverse(@explode('-', $data)));
		$data_entregaF = implode('/', array_reverse(@explode('-', $data_entrega)));
		$horaF = date('H:i:s', @strtotime($hora));
		$valorF = @number_format($valor, 2, ',', '.');

		//extensão do arquivo
		$ext = pathinfo($nota, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png';
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';
		} else if ($ext == 'doc' || $ext == 'docx') {
			$tumb_arquivo = 'word.png';
		} else if ($ext == 'xlsx' || $ext == 'xlsm' || $ext == 'xls') {
			$tumb_arquivo = 'excel.png';
		} else if ($ext == 'txt') {
			$tumb_arquivo = 'txt.png';
		} else if ($ext == 'xml') {
			$tumb_arquivo = 'xml.png';
		} else {
			$tumb_arquivo = $nota;
		}

		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_usu_lanc = $res2[0]['nome'];
		} else {
			$nome_usu_lanc = 'Sem Usuário';
		}

		$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$fornecedor'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_fornecedor = $res2[0]['nome'];
		} else {
			$nome_fornecedor = 'Loja Não Cadastrada';
		}

		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($res2) > 0) {
			$nome_cliente = $res2[0]['nome'];
			$tel_cliente = $res2[0]['telefone'];
		} else {
			$nome_cliente = "Sem Registro";
			$tel_cliente = "Sem Registro";
		}


		if ($status == 'Aprovada') {
			$hoverTabela = 'table-light';
			$cor_status = '#25958a';
			$ocultarAprovar = 'ocultar';
			$ocultarClienteEditar = 'ocultar';
			$total_aprovado += $valor;

		} else if ($status == 'Pendente') {
			$hoverTabela = 'table-danger';
			$cor_status = '#dc3545';
			$ocultarAprovar = '';
			$ocultarClienteEditar = '';
			$total_pendentes += $valor;

		} else if ($status == 'Faturada') {
			$hoverTabela = 'table-success';
			$cor_status = '#198754';
			$ocultarClienteEditar = 'ocultar';
		}


		$total_aprovadoF = number_format($total_aprovado, 2, ',', '.');
		$total_pendentesF = number_format($total_pendentes, 2, ',', '.');

		if ($tel_cliente == "Sem Registro") {
			$ocultar_whats = 'ocultar';
		} else {
			$ocultar_whats = '';
		}

		if ($obs == '') {
			$obs = 'Sem Observações';
		}

		$tel_sistemaF = '55' . preg_replace('/[ ()-]+/', '', $telefone_sistema);


		echo <<<HTML


		<tr class="{$hoverTabela}">

		<td>{$numero_nota}</td>
		<td>{$nome_fornecedor}</td>
		<td>{$data_entregaF}</td>
		<td style="color: {$cor_status}; font-weight:bold">R$ {$valorF}</td>
		<td><a href="../painel/images/notas/{$nota}" target="_blank"><img class="hovv" src="../painel/images/notas/{$tumb_arquivo}" width="25px" height="25px"></a></td>
		<td><div style="color:#FFF; background:{$cor_status}; padding:4px; width:100%; text-align: center; font-size: 12px;">{$status}</div></a>

		<td>

			<a class="btn btn-info btn-sm {$ocultarClienteEditar}" href="#" onclick="editar('{$id}','{$numero_nota}','{$data_entrega}','{$valorF}','{$fornecedor}','{$cliente}','{$obs}','{$nota}')" title="Editar Dados"><i class="fa fa-edit"></i></a>
			
			<a class="btn btn-warning btn-sm" href="#" onclick="mostrar('{$id}','{$numero_nota}','{$data_entregaF}','{$valorF}','{$nome_fornecedor}','{$nome_cliente}','{$nome_usu_lanc}','{$dataF}','{$horaF}','{$obs}','{$nota}')" title="Mostrar Dados"><i class="fa fa-info-circle"></i></a>
			
			<!-- <a class="btn btn-dark btn-sm" href="#" onclick="arquivo('{$id}', '{$nome_cliente}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-lines"></i></a> -->

			<!-- <form class="btn btn-danger btn-sm" method="POST" action="rel/orcamento_class.php" target="_blank" style="display:inline-block">
				<input type="hidden" name="id" value="{$id}">
				<button title="PDF do Orçamento" style="background:transparent; border:none; margin:0; padding:0"><i class="fa fa-file-pdf-o " style="color:white"></i></button>
			</form> -->

			<!-- <div class="dropdown" style="display: inline-block;">                      
				<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-print"></i></a>
				<div  class="dropdown-menu tx-13">
					<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
						<form class="btn btn-dark btn-sm" method="POST" action="rel/imp_orcamento.php" target="_blank" >
							<input type="hidden" name="id" value="{$id}">
							<input type="hidden" name="duas_vias_os" value="Não">
							<button title="Impressão da OS 80mm" style="background:transparent; border:none; margin:0; padding:0; color:#FFF">
								Uma Via
							</button>							
						</form>

						<form class="btn btn-dark btn-sm" method="POST" action="rel/imp_orcamento.php" target="_blank" >
							<input type="hidden" name="id" value="{$id}">
							<input type="hidden" name="duas_vias_os" value="Sim">
							<button title="Impressão da OS 80mm" style="background:transparent; border:none; margin:0; padding:0; color:#FFF">
								Duas Via
							</button>							
						</form>
					</div>
				</div>
			</div> -->

			<a class="btn btn-success btn-sm" class="{$ocultar_whats}" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_sistemaF}" title="Whatsapp" target="_blank"><i class="bi bi-whatsapp"></i></a>

			<!-- excluir -->
			<!-- <div class="dropdown" style="display: inline-block;">                      
				<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"title="Excluir Orçamento"><i class="fa fa-trash-can"></i></a>
				<div  class="dropdown-menu tx-13">
					<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
						<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
					</div>
				</div>
			</div> -->

		</td>  
		</tr> 
			

	HTML;
	}

	echo <<<HTML

	</tbody> 

	<small><div align="center" id="mensagem-excluir"></div></small>

	</table>

	<br>

	<div align="right"><span>Valores Pendentes de Aprovação: <span class="text-danger">{$total_pendentesF}</span></span> <span style="margin-left: 25px">Valores Aprovados: <span class="verde">{$total_aprovadoF}</span></span></div>

	</small>

HTML;
} else {

	echo 'Não encontrei Nenhuma Nota Lançada Para Esta Data ou Período!';
}

?>


<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true,
		});
		$('#tabela_filter label input').focus();
		$('#total_itens').text('<?= $total_notas ?>');
	});



	function editar(id, numero_nota, data_entrega, valor, fornecedor, cliente, obs, nota) {

		$('#titulo_inserir').text('Editar Registro Controle Nº ' + numero_nota)

		if (cliente == 0) {
			cliente = "";
		}

		$('#id').val(id);
		$('#data_entrega').val(data_entrega);
		$('#valor').val(valor);
		$('#fornecedor').val(fornecedor).change();
		$('#cliente').val(cliente).change();
		$('#obs').val(obs);

		$('#arquivo').val('');

		$('#modalForm').modal('show');
		$('#mensagem').text('');

		resultado = nota.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target').attr('src', "../painel/images/pdf.png");
			return;
		} else if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target').attr('src', "../painel/images/rar.png");
			return;
		} else if (resultado[1] === 'doc' || resultado[1] === 'docx') {
			$('#target').attr('src', "../painel/images/word.png");
			return;
		} else if (resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls') {
			$('#target').attr('src', "../painel/images/excel.png");
			return;
		} else if (resultado[1] === 'txt') {
			$('#target').attr('src', "../painel/images/txt.png");
			return;
		} else if (resultado[1] === 'xml') {
			$('#target').attr('src', "../painel/images/xml.png");
			return;	
		} else {
			$('#target').attr('src', '../painel/images/notas/' + nota);
			return;
		}

	}



	function mostrar(id, numero_nota, data_entrega, valor, fornecedor, cliente, usu_cad, data_cad, hora_cad, obs, nota) {

		$('#id_dados').text('Dados da Nota');
		$('#numero_nota_dados').text(numero_nota);
		$('#nome_cliente_dados').text(cliente);
		$('#data_entrega_dados').text(data_entrega);
		$('#valor_dados').text('R$ ' + valor);
		$('#fornecedor_dados').text(fornecedor);
		$('#usu_cad_dados').text(usu_cad);
		$('#data_cad_dados').text(data_cad);
		$('#hora_cad_dados').text(hora_cad);
		$('#obs_dados').text(obs);

		$('#link_notas').attr('href', '../painel/images/notas/' + nota);

		$('#modalDados').modal('show');

		resultado = nota.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#nota_dados').attr('src', "../painel/images/pdf.png");
			return;
		} else if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#nota_dados').attr('src', "../painel/images/rar.png");
			return;
		} else if (resultado[1] === 'doc' || resultado[1] === 'docx') {
			$('#nota_dados').attr('src', "../painel/images/word.png");
			return;
		} else if (resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls') {
			$('#nota_dados').attr('src', "../painel/images/excel.png");
			return;
		} else if (resultado[1] === 'txt') {
			$('#nota_dados').attr('src', "../painel/images/txt.png");
			return;
		} else if (resultado[1] === 'xml') {
			$('#nota_dados').attr('src', "../painel/images/xml.png");
			return;
		} else {
			$('#nota_dados').attr('src', '../painel/images/notas/' + nota);
			return;
		}

	}


	function limparCampos() {

		$('#id').val('');
		$('#data_entrega').val('');
		$('#valor').val('');
		$('#fornecedor').val('').change();
		$('#cliente').val('').change();
		$('#obs').val('');

		$('#arquivo').val('');
		$('#target').attr('src', '../painel/images/notas/sem-foto.png');

	}



	function arquivo(id, nome) {

		$('#id-arquivo').val(id);
		$('#nome-arquivo').text(nome);
		$('#modalArquivos').modal('show');
		$('#mensagem-arquivo').text('');
		listarArquivos();

	}


	function aprovar(id) {

		$.ajax({
			url: 'paginas/' + pag + "/aprovar.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "html",
			success: function(mensagem) {

				if (mensagem.trim() == "Aprovado com Sucesso") {

					aprovado();
					setTimeout(() => {

						listar();

					}, 700);

				} else {

					naoAprovado();

				}
			}
		});
	}


	function abrirEditar(id) {

		$.ajax({
			url: 'paginas/' + pag + "/abrir-editar.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "html",
			success: function(mensagem) {

				if (mensagem.trim() == "Aprovado com Sucesso") {

					abertoEditar();
					setTimeout(() => {

						listar();

					}, 700);

				} else {

					naoabertoEditar()

				}
			}
		});
	}
</script>