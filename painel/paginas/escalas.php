<?php
$pag = 'escalas';

//verificar se ele tem a permissão de estar nessa página
if (@$escalas == 'ocultar') {
	echo "<script>window.location='index.php'</script>";
	exit();
}

$query = $pdo->query("SELECT * from funcoes order by nome asc");
$funcoes_cadastradas = $query->fetchAll(PDO::FETCH_ASSOC);

//escalante precisa ter a função "Escalante" atribuída em Policiais
//selecionado direto da lista de Policiais, sem precisar ter login no sistema
$query = $pdo->query("SELECT p.id, p.nome_guerra, po.nome posto_nome FROM policiais p
	INNER JOIN funcoes f ON f.id = p.funcao_id
	LEFT JOIN postos po ON po.id = p.posto_id
	WHERE f.nome = 'Escalante'
	ORDER BY p.nome_guerra ASC");
$policiais_escalantes = $query->fetchAll(PDO::FETCH_ASSOC);

//comandante precisa ser um oficial: postos QOPM/QOAPM (Cel, Ten Cel, Major, Capitão, Tenentes).
//SubTen, Sargentos, Cabos e Soldados são PM, não entram nessa lista.
//selecionado direto da lista de Policiais, sem precisar ter login no sistema
$query = $pdo->query("SELECT p.id, p.nome_guerra, po.nome posto_nome FROM policiais p
	INNER JOIN postos po ON po.id = p.posto_id
	WHERE LOWER(po.nome) LIKE '%qopm' OR LOWER(po.nome) LIKE '%qoapm'
	ORDER BY p.nome_guerra ASC");
$policiais_comandantes = $query->fetchAll(PDO::FETCH_ASSOC);

$id_escala = @$_GET['id'];
$escala_existente = null;

if ($id_escala != "") {
	$query = $pdo->prepare("SELECT * from escalas_diarias where id = :id");
	$query->bindValue(":id", $id_escala);
	$query->execute();
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res) > 0) {
		$escala = $res[0];

		if ($escala['status'] == 'Publicada') {
			echo "<script>alert('Essa escala já foi publicada e não pode mais ser editada.'); window.location='escalas_listagem'</script>";
			exit();
		}

		$query2 = $pdo->prepare("SELECT * from escala_equipes where escala_id = :id order by turno asc, id asc");
		$query2->bindValue(":id", $id_escala);
		$query2->execute();
		$equipes = $query2->fetchAll(PDO::FETCH_ASSOC);

		foreach ($equipes as &$equipe) {
			$query3 = $pdo->prepare("SELECT em.*, p.nome_guerra, f.nome funcao_nome from escala_membros em INNER JOIN policiais p ON p.id = em.policial_id INNER JOIN funcoes f ON f.id = em.funcao_na_escala_id where em.equipe_id = :equipe_id");
			$query3->bindValue(":equipe_id", $equipe['id']);
			$query3->execute();
			$equipe['membros'] = $query3->fetchAll(PDO::FETCH_ASSOC);
		}
		unset($equipe);

		$escala_existente = ['escala' => $escala, 'equipes' => $equipes];
	}
}
?>

<div class="breadcrumb-header justify-content-between">
	<div class="left-content mt-2">
		<h4 class="mb-0">Criar Escala</h4>
	</div>
</div>

<div class="row row-sm">
	<div class="col-lg-12">
		<div class="card custom-card">
			<div class="card-body">

				<div class="row">
					<div class="col-md-3 mb-2">
						<label>Data da Escala</label>
						<input type="date" class="form-control" id="data_escala" <?php echo $escala_existente ? 'readonly' : '' ?>>
					</div>
					<?php
					$grupo_atual_partes = $escala_existente ? explode(',', $escala_existente['escala']['grupo']) : [];
					$guardas = ['Guarda01', 'Guarda02', 'Guarda03', 'Guarda04'];
					$grupo_atual_normal = '';
					$guarda_atual = '';
					$admin_atual = 'Não';
					foreach ($grupo_atual_partes as $parte) {
						if ($parte == 'Adm') {
							$admin_atual = 'Sim';
						} elseif (in_array($parte, $guardas, true)) {
							$guarda_atual = $parte;
						} else {
							$grupo_atual_normal = $parte;
						}
					}
					?>
					<div class="col-md-2 mb-2">
						<label>Grupo de Serviço</label>
						<select class="form-select" id="grupo_escala" <?php echo $escala_existente ? 'disabled' : '' ?>>
							<option value="" <?php echo $grupo_atual_normal == '' ? 'selected' : '' ?>>Selecione</option>
							<option value="Alpha" <?php echo $grupo_atual_normal == 'Alpha' ? 'selected' : '' ?>>Alpha</option>
							<option value="Bravo" <?php echo $grupo_atual_normal == 'Bravo' ? 'selected' : '' ?>>Bravo</option>
						</select>
					</div>
					<div class="col-md-2 mb-2">
						<label>Guarda</label>
						<select class="form-select" id="guarda_escala" <?php echo $escala_existente ? 'disabled' : '' ?>>
							<option value="" <?php echo $guarda_atual == '' ? 'selected' : '' ?>>Selecione</option>
							<option value="Guarda01" <?php echo $guarda_atual == 'Guarda01' ? 'selected' : '' ?>>Guarda 01</option>
							<option value="Guarda02" <?php echo $guarda_atual == 'Guarda02' ? 'selected' : '' ?>>Guarda 02</option>
							<option value="Guarda03" <?php echo $guarda_atual == 'Guarda03' ? 'selected' : '' ?>>Guarda 03</option>
							<option value="Guarda04" <?php echo $guarda_atual == 'Guarda04' ? 'selected' : '' ?>>Guarda 04</option>
						</select>
					</div>
					<div class="col-md-2 mb-2">
						<label>Incluir Administrativo?</label>
						<select class="form-select" id="administrativo_escala" <?php echo $escala_existente ? 'disabled' : '' ?>>
							<option value="Não" <?php echo $admin_atual == 'Não' ? 'selected' : '' ?>>Não</option>
							<option value="Sim" <?php echo $admin_atual == 'Sim' ? 'selected' : '' ?>>Sim</option>
						</select>
					</div>
					<div class="col-md-3 mb-2 d-flex align-items-end">
						<button type="button" class="btn btn-primary" id="btn-carregar-efetivo" onclick="carregarEfetivoPronto()">
							<i class="fa fa-magnifying-glass me-1"></i> Carregar Efetivo Pronto
						</button>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 mb-2">
						<label>Escalante</label>
						<?php $escalante_atual = $escala_existente ? $escala_existente['escala']['escalante_policial_id'] : '' ?>
						<select class="form-select" id="escalante_policial_id">
							<option value="">Selecione</option>
							<?php foreach ($policiais_escalantes as $p): ?>
								<option value="<?php echo $p['id'] ?>" <?php echo $escalante_atual == $p['id'] ? 'selected' : '' ?>><?php echo htmlspecialchars(trim(($p['posto_nome'] ?? '') . ' ' . $p['nome_guerra'])) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-4 mb-2">
						<label>Comandante</label>
						<?php $comandante_atual = $escala_existente ? $escala_existente['escala']['comandante_policial_id'] : '' ?>
						<select class="form-select" id="comandante_policial_id">
							<option value="">Selecione</option>
							<?php foreach ($policiais_comandantes as $p): ?>
								<option value="<?php echo $p['id'] ?>" <?php echo $comandante_atual == $p['id'] ? 'selected' : '' ?>><?php echo htmlspecialchars($p['posto_nome'] . ' ' . $p['nome_guerra']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<p class="text-muted mb-0" style="font-size:12px">
					A escala é sempre por dia: o(s) Grupo(s)/Guarda(s) selecionados cobrem o Turno A e o Turno B do dia escolhido. Você pode combinar um Grupo de Serviço, uma Guarda e/ou incluir o Administrativo (dias úteis) — o efetivo pronto trará os policiais de todos os selecionados. O Escalante e o Comandante selecionados aparecem no PDF como responsáveis pela assinatura; o registro só vira "assinado eletronicamente" quando a própria pessoa acessar o sistema e assinar.
				</p>

			</div>
		</div>
	</div>
</div>

<div class="row row-sm" id="painel-turnos" style="display:none">
	<div class="col-lg-6" id="painel-turno-A" data-turno-painel="A">
		<div class="card custom-card">
			<div class="card-body">
				<h5 class="mb-3">Turno A</h5>
				<button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="adicionarEquipe('A')"><i class="fa fa-plus me-1"></i> Adicionar Equipe</button>
				<div id="lista-equipes-A"></div>
			</div>
		</div>
	</div>
	<div class="col-lg-6" id="painel-turno-B" data-turno-painel="B">
		<div class="card custom-card">
			<div class="card-body">
				<h5 class="mb-3">Turno B</h5>
				<button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="adicionarEquipe('B')"><i class="fa fa-plus me-1"></i> Adicionar Equipe</button>
				<div id="lista-equipes-B"></div>
			</div>
		</div>
	</div>
</div>

<div class="row row-sm" id="painel-salvar" style="display:none">
	<div class="col-lg-12">
		<div class="card custom-card">
			<div class="card-body">
				<div id="mensagem-escala" class="mb-2"></div>
				<button type="button" class="btn btn-secondary" onclick="salvarEscala('Rascunho')"><i class="fa fa-floppy-disk me-1"></i> Salvar Rascunho</button>
				<button type="button" class="btn btn-success" onclick="salvarEscala('Publicada')"><i class="fa fa-check me-1"></i> Salvar e Publicar</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var funcoesCadastradas = <?php echo json_encode($funcoes_cadastradas) ?>;
	var escalaExistente = <?php echo $escala_existente ? json_encode($escala_existente) : 'null' ?>;
	var idEscalaAtual = <?php echo $id_escala != "" ? $id_escala : 'null' ?>;
	var efetivoDisponivel = { A: [], B: [] };
	var equipeContador = { A: 0, B: 0 };
</script>
<script src="js/escalas.js?v=<?php echo filemtime(__DIR__ . '/../js/escalas.js') ?>"></script>
