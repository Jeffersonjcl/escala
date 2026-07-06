<?php
$tabela = 'usuarios';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT * from $tabela where nivel != 'Policial' and nivel != 'Administrador' order by nome asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	// Adicionar Código HTML dentro do PHP
	echo <<<HTML
	
<small>
	<table class="table table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr>
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Nome</th>	
	<th>Telefone</th>	
	<th>Email</th>	
	<th>Nível</th>	
	<th>Foto</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $linhas; $i++) {
		$id = $res[$i]['id'];
		$nome = $res[$i]['nome'];
		$telefone = $res[$i]['telefone'];
		$email = $res[$i]['email'];
		$senha = '********';
		$foto = $res[$i]['foto'];
		$nivel = $res[$i]['nivel'];
		$endereco = $res[$i]['endereco'];
		$ativo = $res[$i]['ativo'];
		$data = $res[$i]['data'];

		$dataF = implode('/', array_reverse(@explode('-', $data)));

		if ($ativo == 'Sim') {
			$icone = 'bi bi-check-square-fill';
			$titulo_link = 'Desativar Usuário';
			$acao = 'Não';
			$classe_ativo = '';
		} else {
			$icone = 'bi bi-square';
			$titulo_link = 'Ativar Usuário';
			$acao = 'Sim';
			$classe_ativo = '#eb0e0e';
		}

		$ocultar_permissoes = '';
		if ($nivel == 'Administrador') {
			$senha = '********';
			$ocultar_permissoes = 'ocultar';
		}


		echo <<<HTML
		
		
		<tr style="color:{$classe_ativo}">
		<td align="center">
		<div class="custom-checkbox custom-control">
		<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
		<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
		</div>
		</td>
		<td>{$nome}</td>
		<td>{$telefone}</td>
		<td>{$email}</td>
		<td align="center"><span class="badge bg-primary me-1 my-1 p-1" style="color:#FFF ;width: 12Em; font-size: 10px"><big>{$nivel}</big></span></td>
		<td><img onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}','{$ativo}','{$dataF}', '{$senha}', '{$nivel}', '{$foto}')" class="hovv" src="images/perfil/{$foto}" width="25px" style="text-align: center;"></td>
		<td>

		<a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$nivel}')" title="Editar Dados"><i class="fa fa-edit"></i></a>

		<a class="btn btn-warning btn-sm" href="#" onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}','{$ativo}','{$dataF}', '{$senha}', '{$nivel}', '{$foto}')" title="Mostrar Dados"><i class="fa fa-info-circle"></i></a>

		<a class="btn btn-success btn-sm" href="#" onclick="ativar('{$id}', '{$acao}')" title="{$titulo_link}"><i class="fa {$icone} "></i></a>

		<a class="btn btn-success btn-sm" class="{$ocultar_permissoes}" href="#" onclick="permissoes('{$id}', '{$nome}')" title="Definir Permissões"><i class="fa fa-lock "></i></a>

		<div class="dropdown" style="display: inline-block;">                      
			<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown" title="Excluir Usuário"><i class="fa fa-trash-can"></i> </a>
			<div  class="dropdown-menu tx-13">
				<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
					<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger"><button class="btn-danger">Sim</button></span></a></p>
				</div>
			</div>
		</div>

		</td>
		</tr>

		HTML;
	}


} else {

	echo 'Não encontrei nenhum Usuário cadastrado!';
}


echo <<<HTML


</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;
?>


<!-- DATA TABLES TÍTULO DA TABELA -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"language": {
				//"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
			},
			"ordering": false, //REMOVER O ORDENAR TABELA
			"stateSave": true // editar e continuar na msm página
		});
	});
</script>

<script type="text/javascript">
	function editar(id, nome, email, telefone, endereco, nivel) {
		$('#mensagem').text('');
		$('#titulo_inserir').text('Editar Registro');

		$('#id').val(id);
		$('#nome').val(nome);
		$('#email').val(email);
		$('#telefone').val(telefone);
		$('#endereco').val(endereco);
		$('#nivel').val(nivel).change();

		$('#modalForm').modal('show');
	}


	function mostrar(nome, email, telefone, endereco, ativo, data, senha, nivel, foto) {

		$('#titulo_dados').text(nome);
		$('#email_dados').text(email);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#ativo_dados').text(ativo);
		$('#data_dados').text(data);
		$('#senha_dados').text(senha);
		$('#nivel_dados').text(nivel);
		$('#foto_dados').attr("src", "images/perfil/" + foto);


		$('#modalDados').modal('show');
	}

	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');
		$('#email').val('');
		$('#telefone').val('');
		$('#endereco').val('');

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
</script>


<script type="text/javascript">
	function permissoes(id, nome) {
		$('#id-usuario').val(id);
		$('#nome-usuario').text(nome);
		$('#modalPermissoes').modal('show');
		$('#mensagem-permissao').text('');
		listarPermissoes(id);
	}
</script>