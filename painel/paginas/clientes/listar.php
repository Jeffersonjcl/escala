<?php
$tabela = 'clientes';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT * from $tabela order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	echo <<<HTML
<small>
	<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead id="color_Head_Tabela"> 
	<tr>
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Nome</th>
	<th>Telefone</th>
	<th>Email</th>
	<th>Pessoa</th>
	<th>Cadastrado Em</th>
	<th>Ações</th>
	</tr>
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $linhas; $i++) {
		$id = $res[$i]['id'];
		$nome = $res[$i]['nome'];
		$telefone = $res[$i]['telefone'];
		$data_nasc = $res[$i]['data_nasc'];
		$email = $res[$i]['email'];
		$tipo_pessoa = $res[$i]['tipo_pessoa'];
		$cpf = $res[$i]['cpf'];

		$cep = $res[$i]['cep'];
		$endereco = $res[$i]['endereco'];
		$numero = $res[$i]['numero'];
		$complemento = $res[$i]['complemento'];
		$bairro = $res[$i]['bairro'];
		$cidade = $res[$i]['cidade'];
		$estado = $res[$i]['estado'];
		$profissao = $res[$i]['profissao'];
		$nacionalidade = $res[$i]['nacionalidade'];
		$estado_civil = $res[$i]['estado_civil'];
		$data_cad = $res[$i]['data_cad'];

		$data_nascF = implode('/', array_reverse(@explode('-', $data_nasc)));
		$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));

		$tel_whatsF = '55' . preg_replace('/[ ()-]+/', '', $telefone);

		//verificar débitos cliente
		$query2 = $pdo->query("SELECT * from receber where cliente = '$id' and data_venc < curDate() and pago != 'Sim'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$linhas2 = @count($res2);
		if ($linhas2 > 0) {
			$debito2 = 'table-danger';
			$debito = 'text-danger';
		} else {
			$debito2 = '';
			$debito = '';
		}

		echo <<<HTML
		<tr class="{$debito2}">
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td class="{$debito}"> {$nome}</td>
		<td>{$telefone}</td>
		<td>{$email}</td>
		<td><span class="badge bg-primary me-1 my-1 p-1" style="color:#FFF; width: 7em; font-size: 10px;"><big>{$tipo_pessoa}</big></span></td>
		<td>{$data_cadF}</td>
		<td>

		<a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$tipo_pessoa}','{$data_nasc}','{$numero}','{$bairro}','{$cidade}','{$estado}','{$cep}','{$complemento}','{$profissao}','{$estado_civil}','{$nacionalidade}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

		<a class="btn btn-warning btn-sm" href="#" onclick="mostrar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}', '{$data_cadF}','{$cpf}','{$tipo_pessoa}','{$data_nascF}','{$numero}','{$bairro}','{$cidade}','{$estado}','{$cep}','{$complemento}','{$profissao}','{$estado_civil}','{$nacionalidade}')" title="Mostrar Dados"><i class="fa fa-info-circle"></i></a>

		<a class="btn btn-success btn-sm" href="#" onclick="mostrarContas('{$nome}','{$id}')" title="Mostrar Contas"><i class="fa fa-hand-holding-dollar"></i></a>

		<a class="btn btn-secondary btn-sm" href="#" onclick="arquivo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o "></i></a>

		<a class="btn btn-success btn-sm" class="" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" title="Whatsapp" target="_blank"><i class="bi bi-whatsapp"></i></i></a>

		<div class="dropdown" style="display: inline-block;">                      
			<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown" title="Excluir Cliente"><i class="fa fa-trash-can"></i> </a>
			<div  class="dropdown-menu tx-13">
				<div style="width: 240px; padding: 10px 5px 0 10px; border: blue solid 1px;" class="dropdown-item-text">
					<p>
			<b>Confirmar Exclusão? </b>
			<a href="#" onclick="excluir('{$id}')">
				<span class="text-danger"  style="text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
				<b>Sim</b>
				</span>
			</a>
		</p>
				</div>
			</div>
		</div>

		</td>
		</tr>
		
HTML;
	}
} else {
	echo 'Não encontrei nenhum Cliente cadastrado!';
}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;
?>



<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"language": {
				//"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
			},
			"ordering": false,
			"stateSave": true
		});
	});
</script>

<script type="text/javascript">
	function editar(id, nome, email, telefone, endereco, cpf, tipo_pessoa, data_nasc, numero, bairro, cidade, estado, cep, complemento, profissao, estado_civil, nacionalidade) {
		$('#mensagem').text('');
		$('#titulo_inserir').text('Cliente ' + nome);

		$('#id').val(id);
		$('#nome').val(nome);
		$('#email').val(email);
		$('#telefone').val(telefone);
		$('#cpf').val(cpf);
		$('#tipo_pessoa').val(tipo_pessoa).change();
		$('#data_nasc').val(data_nasc);

		$('#cep').val(cep);
		$('#endereco').val(endereco);
		$('#numero').val(numero);
		$('#bairro').val(bairro);
		$('#cidade').val(cidade);
		$('#estado').val(estado).change();
		$('#complemento').val(complemento);

		$('#profissao').val(profissao);
		$('#nacionalidade').val(nacionalidade);
		$('#estado_civil').val(estado_civil).change();

		$('#modalForm').modal('show');

	}


	function mostrar(id, nome, email, telefone, endereco, data_cad, cpf, tipo_pessoa, data_nasc, numero, bairro, cidade, estado, cep, complemento, profissao, estado_civil, nacionalidade) {

		$('#titulo_dados').text(nome);
		$('#email_dados').text(email);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#cpf_dados').text(cpf);
		$('#data_dados').text(data_cad);
		$('#pessoa_dados').text(tipo_pessoa);
		$('#data_nasc_dados').text(data_nasc);

		$('#complemento_dados').text(complemento);
		$('#numero_dados').text(numero);
		$('#bairro_dados').text(bairro);
		$('#cidade_dados').text(cidade);
		$('#estado_dados').text(estado);
		$('#cep_dados').text(cep);

		$('#profissao_dados').text(profissao);
		$('#nacionalidade_dados').text(nacionalidade);
		$('#estado_civil_dados').text(estado_civil);


		$('#modalDados').modal('show');
	}

	function limparCampos() {
		
		$('#id').val('');
		$('#nome').val('');
		$('#email').val('');
		$('#telefone').val('');
		$('#cpf').val('');
		$('#tipo_pessoa').val('').change();
		$('#data_nasc').val('');

		$('#cep').val('');
		$('#endereco').val('');
		$('#numero').val('');
		$('#bairro').val('');
		$('#cidade').val('');
		$('#estado').val('').change();
		$('#complemento').val('');

		$('#profissao').val('');
		$('#nacionalidade').val('');
		$('#estado_civil').val('').change();

		$('#ids').val('');
		$('#btn-deletar').hide();
	}

	function selecionar(id) {

		var ids = $('#ids').val();

		if ($('#seletor-' + id).is(":checked") == true) {
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		} else {
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if (ids_final == "") {
			$('#btn-deletar').hide();
		} else {
			$('#btn-deletar').show();
		}
	}

	function deletarSel() {
		var ids = $('#ids').val();
		var id = ids.split("-");

		for (i = 0; i < id.length - 1; i++) {
			excluirMultiplos(id[i]);
		}

		setTimeout(() => {
			listar();

		}, 1000);

		limparCampos();



	}


	function listarDebitos(id) {


		$.ajax({
			url: 'paginas/' + pag + "/listar_debitos.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "html",

			success: function(result) {
				$("#listar_debitos").html(result);
			}
		});
	}


	function mostrarContas(nome, id) {

		$('#titulo_contas').text(nome);
		$('#id_contas').val(id);

		$('#modalContas').modal('show');
		listarDebitos(id);

	}


	function listarServicos(id) {
		$.ajax({
			url: 'paginas/' + pag + "/listar_servicos.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "html",

			success: function(result) {
				$("#listar_servicos").html(result);
			}
		});
	}



	function arquivo(id, nome) {
		$('#id-arquivo').val(id);
		$('#nome-arquivo').text(nome);
		$('#modalArquivos').modal('show');
		$('#mensagem-arquivo').text('');
		listarArquivos();
	}
</script>