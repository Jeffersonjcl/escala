//Auto Close Timer
function sucesso() {
	$('body').removeClass('timer-alert');
	Swal.fire({
		title: 'Salvo com Sucesso!',
		text: '',
		icon: "success",
		timer: 1000
	})?.then(
		function () {
		},
		// lidando com a rejeição da promessa
		function (dismiss) {
			if (dismiss === 'timer') {
				console.log('Eu estava fechado pelo cronômetro')
			}
		}
	)
}


function inserido() {
	$('body').removeClass('timer-alert');
	Swal.fire({
		title: 'Inserido com Sucesso!',
		text: '',
		icon: "success",
		timer: 1000
	})?.then(
		function () {
		},
		// lidando com a rejeição da promessa
		function (dismiss) {
			if (dismiss === 'timer') {
				console.log('Eu estava fechado pelo cronômetro')
			}
		}
	)
}


//Auto Close Timer
function excluido() {
	$('body').removeClass('timer-alert');
	Swal.fire({
		title: 'Excluido com Sucesso!',
		text: '',
		icon: "success",
		timer: 1000
	})?.then(
		function () {
		},
		// lidando com a rejeição da promessa
		function (dismiss) {
			if (dismiss === 'timer') {
				console.log('Eu estava fechado pelo cronômetro')
			}
		}
	)
}


//Auto Close Timer
function alertcobrar() {
	$('body').removeClass('timer-alert');
	Swal.fire({
		title: 'Cobrança Efetuada!',
		text: '',
		icon: "success",
		timer: 1000
	})?.then(
		function () {
		},
		// lidando com a rejeição da promessa
		function (dismiss) {
			if (dismiss === 'timer') {
				console.log('Eu estava fechado pelo cronômetro')
			}
		}
	)
}


function aprovado() {
	$('body').removeClass('timer-alert');
	Swal.fire({
		title: 'Nota Aprovada!',
		text: '',
		icon: "success",
		timer: 1000
	})?.then(
		function () {
		},
		// lidando com a rejeição da promessa
		function (dismiss) {
			if (dismiss === 'timer') {
				console.log('Eu estava fechado pelo cronômetro')
			}
		}
	)
}


function naoAprovado() {
	$('body').removeClass('timer-alert');
	Swal.fire({
		title: 'Nota Não Aprovada!',
		text: 'Não Foi Possível Aprovar esta Nota!',
		icon: "error",
		timer: 2000
	})?.then(
		function () {
		},
		// lidando com a rejeição da promessa
		function (dismiss) {
			if (dismiss === 'timer') {
				console.log('Eu estava fechado pelo cronômetro')
			}
		}
	)
}


function abertoEditar() {
	$('body').removeClass('timer-alert');
	Swal.fire({
		title: 'Aberta para Edição!',
		text: 'Edição permitida ao Cliente!',
		icon: "info",
		timer: 1000
	})?.then(
		function () {
		},
		// lidando com a rejeição da promessa
		function (dismiss) {
			if (dismiss === 'timer') {
				console.log('Eu estava fechado pelo cronômetro')
			}
		}
	)
}


function naoabertoEditar() {
	$('body').removeClass('timer-alert');
	Swal.fire({
		title: 'Edição Não Permitida!',
		text: 'Não Foi Possível Permitir Edição!',
		icon: "error",
		timer: 2000
	})?.then(
		function () {
		},
		// lidando com a rejeição da promessa
		function (dismiss) {
			if (dismiss === 'timer') {
				console.log('Eu estava fechado pelo cronômetro')
			}
		}
	)
}

