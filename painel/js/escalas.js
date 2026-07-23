$(document).ready(function() {
	if (escalaExistente) {
		$('#data_escala').val(escalaExistente.escala.data_escala);
		carregarEfetivoPronto();
	}
});

function carregarEfetivoPronto() {
	var data_escala = $('#data_escala').val();

	if (data_escala == "") {
		alert('Selecione a Data antes de carregar o efetivo!');
		return;
	}

	var turnos = idEscalaAtual ? [turnoEdicao] : ['A', 'B'];

	var promessas = turnos.map(function(turno) {
		return $.ajax({
			url: 'paginas/escalas/buscar_efetivo_pronto.php',
			method: 'POST',
			data: {
				data_escala: data_escala,
				turno: turno,
				id_escala: idEscalaAtual
			},
			dataType: 'json'
		}).then(function(res) {
			efetivoDisponivel[turno] = res;
		});
	});

	$.when.apply($, promessas).done(function() {
		$('#painel-turnos').show();
		$('#painel-salvar').show();

		turnos.forEach(function(turno) {
			$('#painel-turno-' + turno).show();

			if (idEscalaAtual && escalaExistente && turno == turnoEdicao && $('#lista-equipes-' + turno).children().length == 0) {
				escalaExistente.equipes.forEach(function(equipe) {
					adicionarEquipe(turno, equipe.nome_equipe, equipe.viatura, equipe.membros, equipe.horario_inicio, equipe.horario_fim, equipe.area_atuacao);
				});
			} else if ($('#lista-equipes-' + turno).children().length == 0) {
				adicionarEquipe(turno, 'Raio 01', '', []);
			}

			atualizarSelectsPoliciais(turno);
		});

		if (idEscalaAtual) {
			var outroTurno = turnoEdicao == 'A' ? 'B' : 'A';
			$('#painel-turno-' + outroTurno).hide();
		}
	});
}

function idsUsados(turno) {
	var usados = [];
	$('#painel-turno-' + turno + ' .membro-row').each(function() {
		usados.push(parseInt($(this).data('policial-id')));
	});
	return usados;
}

function montarOptionsPoliciais(turno, equipeDiv) {
	var usados = idsUsados(turno);
	var select = equipeDiv.find('.select-policial');
	var atual = select.val();
	select.empty();
	select.append('<option value="">Selecione o Policial</option>');

	(efetivoDisponivel[turno] || []).forEach(function(p) {
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

function atualizarSelectsPoliciais(turno) {
	$('#painel-turno-' + turno + ' .equipe-box').each(function() {
		montarOptionsPoliciais(turno, $(this));
	});
}

function adicionarEquipe(turno, nome, viatura, membros, horario_inicio, horario_fim, area_atuacao) {
	equipeContador[turno]++;
	nome = nome || ('Raio ' + String(equipeContador[turno]).padStart(2, '0'));
	viatura = viatura || '';
	membros = membros || [];
	horario_inicio = (horario_inicio || '').substring(0, 5);
	horario_fim = (horario_fim || '').substring(0, 5);
	area_atuacao = area_atuacao || '';

	var idx = equipeContador[turno];

	var html = '' +
		'<div class="card equipe-box mb-3" data-equipe-idx="' + idx + '">' +
		'  <div class="card-header d-flex flex-wrap gap-2 align-items-center">' +
		'    <input type="text" class="form-control form-control-sm nome-equipe" style="max-width:160px" value="' + nome + '">' +
		'    <input type="text" class="form-control form-control-sm viatura-equipe" style="max-width:160px" placeholder="Viatura (opcional)" value="' + viatura + '">' +
		'    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="removerEquipe(this)"><i class="fa fa-trash-can"></i> Remover Equipe</button>' +
		'  </div>' +
		'  <div class="card-body">' +
		'    <div class="mb-3">' +
		'      <label class="form-label small mb-1">Horário do Serviço (Início e Final)</label>' +
		'      <div class="d-flex gap-2">' +
		'        <input type="time" class="form-control form-control-sm horario-inicio-equipe" value="' + horario_inicio + '">' +
		'        <input type="time" class="form-control form-control-sm horario-fim-equipe" value="' + horario_fim + '">' +
		'      </div>' +
		'    </div>' +
		'    <div class="mb-3">' +
		'      <label class="form-label small mb-1">Área de Atuação</label>' +
		'      <input type="text" class="form-control form-control-sm area-atuacao-equipe" placeholder="Ex: Setor Centro" value="' + area_atuacao + '">' +
		'    </div>' +
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
	$('#lista-equipes-' + turno).append(equipeDiv);

	montarOptionsFuncoes(equipeDiv);

	membros.forEach(function(m) {
		inserirLinhaMembro(equipeDiv, m.policial_id, m.nome_guerra, m.funcao_na_escala_id, m.funcao_nome);
	});

	atualizarSelectsPoliciais(turno);
	validarEquipe(equipeDiv);
}

function removerEquipe(btn) {
	if (!confirm('Remover essa equipe e todos os seus membros?')) return;
	var equipeDiv = $(btn).closest('.equipe-box');
	var turno = equipeDiv.closest('[data-turno-painel]').attr('data-turno-painel');
	equipeDiv.remove();
	atualizarSelectsPoliciais(turno);
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
	var turno = equipeDiv.closest('[data-turno-painel]').attr('data-turno-painel');
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

	atualizarSelectsPoliciais(turno);
	validarEquipe(equipeDiv);
}

function removerMembro(btn) {
	var equipeDiv = $(btn).closest('.equipe-box');
	var turno = equipeDiv.closest('[data-turno-painel]').attr('data-turno-painel');
	$(btn).closest('.membro-row').remove();
	atualizarSelectsPoliciais(turno);
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

function validarTodasEquipes(turno) {
	var equipes = $('#lista-equipes-' + turno + ' .equipe-box');
	if (equipes.length == 0) return true;

	var valido = true;
	equipes.each(function() {
		if (!validarEquipe($(this))) {
			valido = false;
		}
	});
	return valido;
}

function montarPayload(turno) {
	var equipes = [];
	$('#lista-equipes-' + turno + ' .equipe-box').each(function() {
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
			horario_inicio: $(this).find('.horario-inicio-equipe').val(),
			horario_fim: $(this).find('.horario-fim-equipe').val(),
			area_atuacao: $(this).find('.area-atuacao-equipe').val(),
			membros: membros
		});
	});

	return {
		id: (idEscalaAtual && turno == turnoEdicao) ? idEscalaAtual : null,
		data_escala: $('#data_escala').val(),
		turno: turno,
		equipes: equipes
	};
}

function salvarEscala(statusDesejado) {
	var turnosAtivos = idEscalaAtual ? [turnoEdicao] : ['A', 'B'].filter(function(turno) {
		return $('#lista-equipes-' + turno + ' .equipe-box').length > 0;
	});

	if (turnosAtivos.length == 0) {
		alert('Adicione ao menos uma Equipe em algum Turno antes de salvar!');
		return;
	}

	if (statusDesejado == 'Publicada') {
		var algumInvalido = turnosAtivos.some(function(turno) {
			return !validarTodasEquipes(turno);
		});
		if (algumInvalido) {
			alert('Existe(m) equipe(s) fora do padrão de 4 a 5 membros. Corrija antes de publicar.');
			return;
		}
	}

	$('#mensagem-escala').removeClass('text-danger text-success').text('Salvando...');

	salvarSequencial(turnosAtivos.slice(), statusDesejado, []);
}

function salvarSequencial(turnos, statusDesejado, resultados) {
	if (turnos.length == 0) {
		var sucesso = resultados.every(function(r) {
			return r.status == 'success';
		});
		var texto = resultados.map(function(r) {
			return 'Turno ' + r.turno + ': ' + r.message;
		}).join(' | ');

		$('#mensagem-escala').removeClass('text-danger text-success').addClass(sucesso ? 'text-success' : 'text-danger').text(texto);

		if (sucesso) {
			setTimeout(function() {
				window.location = 'index.php?pagina=escalas_listagem';
			}, 1500);
		}
		return;
	}

	var turno = turnos[0];
	var payload = montarPayload(turno);
	payload.status = statusDesejado;

	$.ajax({
		url: 'paginas/escalas/salvar.php',
		method: 'POST',
		data: {
			payload: JSON.stringify(payload)
		},
		dataType: 'json',
		success: function(res) {
			resultados.push({
				turno: turno,
				status: res.status,
				message: res.message
			});
			salvarSequencial(turnos.slice(1), statusDesejado, resultados);
		}
	});
}
