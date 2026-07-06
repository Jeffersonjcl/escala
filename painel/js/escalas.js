$(document).ready(function() {
	if (escalaExistente) {
		$('#data_escala').val(escalaExistente.escala.data_escala);
		$('#turno').val(escalaExistente.escala.turno);
		carregarEfetivoPronto();
	}
});

function carregarEfetivoPronto() {
	var data_escala = $('#data_escala').val();
	var turno = $('#turno').val();

	if (data_escala == "" || turno == "") {
		alert('Selecione a Data e o Turno antes de carregar o efetivo!');
		return;
	}

	$.ajax({
		url: 'paginas/escalas/buscar_efetivo_pronto.php',
		method: 'POST',
		data: {
			data_escala,
			turno,
			id_escala: idEscalaAtual
		},
		dataType: 'json',
		success: function(res) {
			efetivoDisponivel = res;
			$('#painel-equipes').show();

			if (escalaExistente && $('#lista-equipes').children().length == 0) {
				escalaExistente.equipes.forEach(function(equipe) {
					adicionarEquipe(equipe.nome_equipe, equipe.viatura, equipe.membros);
				});
			} else if ($('#lista-equipes').children().length == 0) {
				adicionarEquipe('Raio 01', '', []);
			}

			atualizarSelectsPoliciais();
		}
	});
}

function idsUsados() {
	var usados = [];
	$('.membro-row').each(function() {
		usados.push(parseInt($(this).data('policial-id')));
	});
	return usados;
}

function montarOptionsPoliciais(equipeDiv) {
	var usados = idsUsados();
	var select = equipeDiv.find('.select-policial');
	var atual = select.val();
	select.empty();
	select.append('<option value="">Selecione o Policial</option>');

	efetivoDisponivel.forEach(function(p) {
		if (usados.indexOf(parseInt(p.id)) === -1) {
			select.append('<option value="' + p.id + '" data-funcao="' + p.funcao_id + '">' + p.nome_guerra + ' - ' + p.funcao_nome + ' (' + p.grupo + ')</option>');
		}
	});

	if (atual) {
		select.val(atual);
	}
}

function montarOptionsFuncoes(equipeDiv) {
	var select = equipeDiv.find('.select-funcao');
	select.empty();
	funcoesCadastradas.forEach(function(f) {
		select.append('<option value="' + f.id + '">' + f.nome + '</option>');
	});
}

function atualizarSelectsPoliciais() {
	$('.equipe-box').each(function() {
		montarOptionsPoliciais($(this));
	});
}

function adicionarEquipe(nome, viatura, membros) {
	equipeContador++;
	nome = nome || ('Raio ' + String(equipeContador).padStart(2, '0'));
	viatura = viatura || '';
	membros = membros || [];

	var idx = equipeContador;

	var html = '' +
		'<div class="card equipe-box mb-3" data-equipe-idx="' + idx + '">' +
		'  <div class="card-header d-flex flex-wrap gap-2 align-items-center">' +
		'    <input type="text" class="form-control form-control-sm nome-equipe" style="max-width:160px" value="' + nome + '">' +
		'    <input type="text" class="form-control form-control-sm viatura-equipe" style="max-width:160px" placeholder="Viatura (opcional)" value="' + viatura + '">' +
		'    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="removerEquipe(this)"><i class="fa fa-trash-can"></i> Remover Equipe</button>' +
		'  </div>' +
		'  <div class="card-body">' +
		'    <table class="table table-sm">' +
		'      <thead><tr><th>Policial</th><th>Função na Escala</th><th></th></tr></thead>' +
		'      <tbody class="membros-tbody"></tbody>' +
		'    </table>' +
		'    <div class="d-flex gap-2">' +
		'      <select class="form-select form-select-sm select-policial"></select>' +
		'      <select class="form-select form-select-sm select-funcao" style="max-width:220px"></select>' +
		'      <button type="button" class="btn btn-sm btn-success" onclick="adicionarMembro(this)">Adicionar</button>' +
		'    </div>' +
		'    <div class="aviso-equipe small mt-2"></div>' +
		'  </div>' +
		'</div>';

	var equipeDiv = $(html);
	$('#lista-equipes').append(equipeDiv);

	montarOptionsFuncoes(equipeDiv);

	membros.forEach(function(m) {
		inserirLinhaMembro(equipeDiv, m.policial_id, m.nome_guerra, m.funcao_na_escala_id, m.funcao_nome);
	});

	atualizarSelectsPoliciais();
	validarEquipe(equipeDiv);
}

function removerEquipe(btn) {
	if (!confirm('Remover essa equipe e todos os seus membros?')) return;
	$(btn).closest('.equipe-box').remove();
	atualizarSelectsPoliciais();
}

function inserirLinhaMembro(equipeDiv, policial_id, nome_guerra, funcao_id, funcao_nome) {
	var row = $('' +
		'<tr class="membro-row" data-policial-id="' + policial_id + '" data-funcao-id="' + funcao_id + '">' +
		'  <td>' + nome_guerra + '</td>' +
		'  <td>' + funcao_nome + '</td>' +
		'  <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removerMembro(this)"><i class="fa fa-xmark"></i></button></td>' +
		'</tr>');
	equipeDiv.find('.membros-tbody').append(row);
}

function adicionarMembro(btn) {
	var equipeDiv = $(btn).closest('.equipe-box');
	var totalAtual = equipeDiv.find('.membro-row').length;

	if (totalAtual >= 5) {
		alert('Erro Crítico: Limite máximo operacional de 05 membros por equipe foi excedido!');
		return;
	}

	var selectPolicial = equipeDiv.find('.select-policial');
	var selectFuncao = equipeDiv.find('.select-funcao');

	var policial_id = selectPolicial.val();
	if (!policial_id) {
		alert('Selecione um Policial!');
		return;
	}

	var nome_guerra = selectPolicial.find('option:selected').text();
	var funcao_id = selectFuncao.val();
	var funcao_nome = selectFuncao.find('option:selected').text();

	inserirLinhaMembro(equipeDiv, policial_id, nome_guerra, funcao_id, funcao_nome);

	atualizarSelectsPoliciais();
	validarEquipe(equipeDiv);
}

function removerMembro(btn) {
	var equipeDiv = $(btn).closest('.equipe-box');
	$(btn).closest('.membro-row').remove();
	atualizarSelectsPoliciais();
	validarEquipe(equipeDiv);
}

function validarEquipe(equipeDiv) {
	var total = equipeDiv.find('.membro-row').length;
	var aviso = equipeDiv.find('.aviso-equipe');

	if (total < 4) {
		aviso.removeClass('text-success').addClass('text-warning')
			.text('Aviso: Equipe abaixo do padrão regulamentar (04 membros). Atual: ' + total);
	} else {
		aviso.removeClass('text-warning').addClass('text-success')
			.text('Equipe dentro do padrão (' + total + ' membros).');
	}

	return total >= 4 && total <= 5;
}

function validarTodasEquipes() {
	var equipes = $('.equipe-box');
	if (equipes.length == 0) {
		alert('Adicione ao menos uma Equipe antes de salvar!');
		return false;
	}

	var valido = true;
	equipes.each(function() {
		if (!validarEquipe($(this))) {
			valido = false;
		}
	});
	return valido;
}

function montarPayload() {
	var equipes = [];
	$('.equipe-box').each(function() {
		var membros = [];
		$(this).find('.membro-row').each(function() {
			membros.push({
				policial_id: $(this).data('policial-id'),
				funcao_na_escala_id: $(this).data('funcao-id')
			});
		});

		equipes.push({
			nome_equipe: $(this).find('.nome-equipe').val(),
			viatura: $(this).find('.viatura-equipe').val(),
			membros: membros
		});
	});

	return {
		id: idEscalaAtual,
		data_escala: $('#data_escala').val(),
		turno: $('#turno').val(),
		equipes: equipes
	};
}

function salvarEscala(statusDesejado) {
	if (statusDesejado == 'Publicada' && !validarTodasEquipes()) {
		alert('Existe(m) equipe(s) fora do padrão de 4 a 5 membros. Corrija antes de publicar.');
		return;
	}

	if ($('.equipe-box').length == 0) {
		alert('Adicione ao menos uma Equipe antes de salvar!');
		return;
	}

	var payload = montarPayload();
	payload.status = statusDesejado;

	$('#mensagem-escala').removeClass('text-danger text-success').text('Salvando...');

	$.ajax({
		url: 'paginas/escalas/salvar.php',
		method: 'POST',
		data: {
			payload: JSON.stringify(payload)
		},
		dataType: 'json',
		success: function(res) {
			if (res.status == 'success') {
				$('#mensagem-escala').addClass('text-success').text(res.message);
				setTimeout(function() {
					window.location = 'index.php?pagina=escalas_listagem';
				}, 1200);
			} else {
				$('#mensagem-escala').addClass('text-danger').text(res.message);
			}
		}
	});
}
