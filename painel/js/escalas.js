$(document).ready(function() {
	if (escalaExistente) {
		$('#data_escala').val(escalaExistente.escala.data_escala);
		carregarEfetivoPronto();
	}
});

// pode combinar um Grupo de Serviço (Alpha/Bravo), uma Guarda (Guarda01-04) e o Administrativo na mesma escala
function gruposEscalaSelecionados() {
	var admin = $('#administrativo_escala').val() == 'Sim' ? 'Adm' : '';
	return [$('#grupo_escala').val(), $('#guarda_escala').val(), admin].filter(function(v) {
		return v != '';
	});
}

// valor salvo em escalas_diarias.grupo, ex: "Alpha,Guarda03"
function grupoEscalaSelecionado() {
	return gruposEscalaSelecionados().join(',');
}

function carregarEfetivoPronto() {
	var data_escala = $('#data_escala').val();
	var grupos = gruposEscalaSelecionados();

	if (data_escala == "") {
		alert('Selecione a Data antes de carregar o efetivo!');
		return;
	}

	if (grupos.length == 0) {
		alert('Selecione o Grupo de Serviço e/ou a Guarda antes de carregar o efetivo!');
		return;
	}

	var turnos = ['A', 'B'];

	var promessas = turnos.map(function(turno) {
		var buscasDoTurno = grupos.map(function(grupo) {
			return $.ajax({
				url: 'paginas/escalas/buscar_efetivo_pronto.php',
				method: 'POST',
				data: {
					data_escala: data_escala,
					turno: turno,
					grupo: grupo,
					id_escala: idEscalaAtual
				},
				dataType: 'json'
			});
		});

		return $.when.apply($, buscasDoTurno).then(function() {
			// $.when com múltiplas promises entrega um array de [res, status, jqXHR] por chamada
			var resultados = buscasDoTurno.length > 1 ? Array.prototype.slice.call(arguments).map(function(a) { return a[0]; }) : [arguments[0]];

			var combinados = [];
			var idsVistos = [];
			resultados.forEach(function(res) {
				res.forEach(function(p) {
					if (idsVistos.indexOf(p.id) === -1) {
						idsVistos.push(p.id);
						combinados.push(p);
					}
				});
			});

			efetivoDisponivel[turno] = combinados;
		});
	});

	$('#btn-carregar-efetivo').prop('disabled', true);

	$.when.apply($, promessas).done(function() {
		$('#painel-turnos').show();
		$('#painel-salvar').show();

		turnos.forEach(function(turno) {
			$('#painel-turno-' + turno).show();

			var equipesDoTurno = (escalaExistente ? escalaExistente.equipes : []).filter(function(e) {
				return e.turno == turno;
			});

			if (idEscalaAtual && equipesDoTurno.length > 0 && $('#lista-equipes-' + turno).children().length == 0) {
				equipesDoTurno.forEach(function(equipe) {
					adicionarEquipe(turno, equipe.nome_equipe, equipe.viatura, equipe.membros, equipe.horario_inicio, equipe.horario_fim, equipe.area_atuacao);
				});
			} else if (!idEscalaAtual && $('#lista-equipes-' + turno).children().length == 0) {
				adicionarEquipe(turno, 'Raio 01', '', []);
			}
		});

		atualizarSelectsAmbosTurnos();
	}).fail(function(jqXHR) {
		alert('Erro ao carregar o efetivo pronto. Detalhes no console (F12).');
		console.error('Falha ao carregar efetivo pronto:', jqXHR.status, jqXHR.responseText);
	}).always(function() {
		$('#btn-carregar-efetivo').prop('disabled', false);
	});
}

function idsUsados() {
	var usados = [];
	$('#painel-turnos .membro-row').each(function() {
		usados.push(parseInt($(this).data('policial-id')));
	});
	return usados;
}

var labelsGrupo = { 'Adm': 'Administrativo', 'Alpha': 'Alpha', 'Bravo': 'Bravo', 'Guarda01': 'Guarda 01', 'Guarda02': 'Guarda 02', 'Guarda03': 'Guarda 03', 'Guarda04': 'Guarda 04' };
var labelsTurno = { 'Adm': 'Administrativo', 'A': 'Turno A', 'B': 'Turno B', '24H': 'Turno 24H' };

// cor do nome no select conforme o grupo/turno do próprio policial (não o painel A/B que está
// sendo exibido): Guarda e Administrativo pelo grupo; os demais pelo turno_padrao cadastrado
var CORES_POLICIAL = {
	turnoA: { cor: '#00008B', fundo: '#e8ecf9' },
	turnoB: { cor: '#8B0000', fundo: '#f9e8e8' },
	guarda: { cor: '#B25900', fundo: '#f9f0e2' },
	administrativo: { cor: '#006400', fundo: '#e6f3e6' }
};

function corPolicial(p) {
	if ((p.grupo && p.grupo.indexOf('Guarda') === 0) || p.turno_padrao === '24H') {
		return CORES_POLICIAL.guarda;
	}
	if (p.grupo === 'Adm' || p.turno_padrao === 'Adm') {
		return CORES_POLICIAL.administrativo;
	}
	return p.turno_padrao === 'B' ? CORES_POLICIAL.turnoB : CORES_POLICIAL.turnoA;
}

function montarOptionsPoliciais(turno, equipeDiv) {
	var usados = idsUsados();
	var select = equipeDiv.find('.select-policial');
	var atual = select.val();
	select.empty();
	select.append('<option value="">Selecione o Policial</option>');

	(efetivoDisponivel[turno] || []).forEach(function(p) {
		if (usados.indexOf(parseInt(p.id)) === -1) {
			var infoGrupo = (p.grupo ? (labelsGrupo[p.grupo] || p.grupo) : 'Sem grupo');
			if (p.turno_padrao) {
				infoGrupo += ' - ' + (labelsTurno[p.turno_padrao] || ('Turno ' + p.turno_padrao));
			}
			if (parseInt(p.via_drso) === 1) {
				infoGrupo += ' - DRSO';
			}
			var cor = corPolicial(p);
			select.append('<option value="' + p.id + '" data-funcao="' + p.funcao_id + '" style="color:' + cor.cor + ' !important; background-color:' + cor.fundo + ' !important">' + p.nome_guerra + ' - ' + p.funcao_nome + ' (' + infoGrupo + ')</option>');
		}
	});

	if (atual && select.find('option[value="' + atual + '"]').length > 0) {
		select.val(atual);
	} else {
		select.val('');
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

// um policial escalado em um turno não pode ser escalado no outro, então
// qualquer inclusão/remoção de membro precisa atualizar os selects dos dois turnos
function atualizarSelectsAmbosTurnos() {
	atualizarSelectsPoliciais('A');
	atualizarSelectsPoliciais('B');
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

	atualizarSelectsAmbosTurnos();
	validarEquipe(equipeDiv);
}

function removerEquipe(btn) {
	if (!confirm('Remover essa equipe e todos os seus membros?')) return;
	var equipeDiv = $(btn).closest('.equipe-box');
	equipeDiv.remove();
	atualizarSelectsAmbosTurnos();
}

// mesma ordem de precedência de funções usada na geração do PDF (gerar_pdf.php)
var ORDEM_FUNCOES = {
	'comandante': 1,
	'comandante de equipe': 1,
	'comandante da guarda': 1,
	'sentinela 01': 2,
	'sentinela 02': 3,
	'2º homem': 2,
	'3º homem': 3,
	'garupa': 4,
	'atirador': 4,
	'5º homem': 5,
	'sub comandante': 6,
	'subcomandante': 6,
	'motorista': 7,
	'piloto': 7,
	'1º patrulheiro': 8,
	'2º patrulheiro': 8,
	'patrulheiro': 8,
	'permanente': 9,
	'sai': 10,
	'auxiliar do p1': 11,
	'auxiliar do p4': 12,
	'escalante': 13,
	'reserva de armamento': 14,
	'rancheiro': 15
};

function ordenarMembros(equipeDiv) {
	var tbody = equipeDiv.find('.membros-tbody');
	var rows = tbody.find('.membro-row').get();

	rows.sort(function(a, b) {
		var funcaoA = $(a).find('td').eq(1).text().trim().toLowerCase();
		var funcaoB = $(b).find('td').eq(1).text().trim().toLowerCase();
		var rankA = ORDEM_FUNCOES[funcaoA] || 16;
		var rankB = ORDEM_FUNCOES[funcaoB] || 16;

		if (rankA !== rankB) {
			return rankA - rankB;
		}

		var nomeA = $(a).find('td').eq(0).text().trim().toLowerCase();
		var nomeB = $(b).find('td').eq(0).text().trim().toLowerCase();
		return nomeA.localeCompare(nomeB);
	});

	rows.forEach(function(row) {
		tbody.append(row);
	});
}

function inserirLinhaMembro(equipeDiv, policial_id, nome_guerra, funcao_id, funcao_nome) {
	var row = $('' +
		'<tr class="membro-row" data-policial-id="' + policial_id + '" data-funcao-id="' + funcao_id + '">' +
		'  <td>' + nome_guerra + '</td>' +
		'  <td>' + funcao_nome + '</td>' +
		'  <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removerMembro(this)"><i class="fa fa-xmark"></i></button></td>' +
		'</tr>');
	equipeDiv.find('.membros-tbody').append(row);
	ordenarMembros(equipeDiv);
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

	atualizarSelectsAmbosTurnos();
	validarEquipe(equipeDiv);
}

function removerMembro(btn) {
	var equipeDiv = $(btn).closest('.equipe-box');
	$(btn).closest('.membro-row').remove();
	atualizarSelectsAmbosTurnos();
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

function montarPayload() {
	var equipes = [];

	['A', 'B'].forEach(function(turno) {
		$('#lista-equipes-' + turno + ' .equipe-box').each(function() {
			var membros = [];
			$(this).find('.membro-row').each(function() {
				membros.push({
					policial_id: $(this).data('policial-id'),
					funcao_na_escala_id: $(this).data('funcao-id')
				});
			});

			equipes.push({
				turno: turno,
				nome_equipe: $(this).find('.nome-equipe').val(),
				viatura: $(this).find('.viatura-equipe').val(),
				horario_inicio: $(this).find('.horario-inicio-equipe').val(),
				horario_fim: $(this).find('.horario-fim-equipe').val(),
				area_atuacao: $(this).find('.area-atuacao-equipe').val(),
				membros: membros
			});
		});
	});

	return {
		id: idEscalaAtual,
		data_escala: $('#data_escala').val(),
		grupo: grupoEscalaSelecionado(),
		escalante_policial_id: $('#escalante_policial_id').val(),
		comandante_policial_id: $('#comandante_policial_id').val(),
		equipes: equipes
	};
}

function salvarEscala(statusDesejado) {
	if (grupoEscalaSelecionado() == "") {
		alert('Selecione o Grupo de Serviço ou a Guarda antes de salvar!');
		return;
	}

	var totalEquipes = $('#lista-equipes-A .equipe-box').length + $('#lista-equipes-B .equipe-box').length;
	if (totalEquipes == 0) {
		alert('Adicione ao menos uma Equipe em algum Turno antes de salvar!');
		return;
	}

	if (statusDesejado == 'Publicada') {
		var algumInvalido = !validarTodasEquipes('A') || !validarTodasEquipes('B');
		if (algumInvalido) {
			alert('Existe(m) equipe(s) fora do padrão de 4 a 5 membros. Corrija antes de publicar.');
			return;
		}
	}

	$('#mensagem-escala').removeClass('text-danger text-success').text('Salvando...');

	var payload = montarPayload();
	payload.status = statusDesejado;

	$.ajax({
		url: 'paginas/escalas/salvar.php',
		method: 'POST',
		data: {
			payload: JSON.stringify(payload)
		},
		dataType: 'json',
		success: function(res) {
			$('#mensagem-escala').removeClass('text-danger text-success').addClass(res.status == 'success' ? 'text-success' : 'text-danger').text(res.message);

			if (res.status == 'success') {
				setTimeout(function() {
					window.location = 'index.php?pagina=escalas_listagem';
				}, 1500);
			}
		}
	});
}
