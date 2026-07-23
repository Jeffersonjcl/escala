<?php

/**
 * Leitor mínimo de .xlsx sem depender da extensão zip do PHP.
 * Um .xlsx é um ZIP contendo XML; aqui localizamos as entradas do ZIP
 * via Central Directory e descomprimimos com gzinflate() (deflate puro,
 * sem cabeçalho zlib/gzip), suficiente para arquivos gerados por
 * Excel/LibreOffice/Google Sheets (sem streaming/ZIP64).
 */

function xlsx_zip_ler_entrada($zipBinario, $nomeEntrada) {
	$eocdPos = strrpos($zipBinario, "PK\x05\x06");
	if ($eocdPos === false) {
		return null;
	}

	$eocd = substr($zipBinario, $eocdPos, 22);
	$totalEntradas = unpack('v', substr($eocd, 10, 2))[1];
	$cdOffset = unpack('V', substr($eocd, 16, 4))[1];

	$pos = $cdOffset;
	for ($i = 0; $i < $totalEntradas; $i++) {
		if (substr($zipBinario, $pos, 4) !== "PK\x01\x02") {
			break;
		}

		$metodoCompressao = unpack('v', substr($zipBinario, $pos + 10, 2))[1];
		$tamanhoComprimido = unpack('V', substr($zipBinario, $pos + 20, 4))[1];
		$tamanhoNome = unpack('v', substr($zipBinario, $pos + 28, 2))[1];
		$tamanhoExtra = unpack('v', substr($zipBinario, $pos + 30, 2))[1];
		$tamanhoComentario = unpack('v', substr($zipBinario, $pos + 32, 2))[1];
		$offsetLocal = unpack('V', substr($zipBinario, $pos + 42, 4))[1];
		$nome = substr($zipBinario, $pos + 46, $tamanhoNome);

		if ($nome === $nomeEntrada) {
			$nomeLocalLen = unpack('v', substr($zipBinario, $offsetLocal + 26, 2))[1];
			$extraLocalLen = unpack('v', substr($zipBinario, $offsetLocal + 28, 2))[1];
			$inicioDados = $offsetLocal + 30 + $nomeLocalLen + $extraLocalLen;
			$dados = substr($zipBinario, $inicioDados, $tamanhoComprimido);

			if ($metodoCompressao === 0) {
				return $dados;
			}

			if ($metodoCompressao === 8) {
				return gzinflate($dados);
			}

			return null;
		}

		$pos += 46 + $tamanhoNome + $tamanhoExtra + $tamanhoComentario;
	}

	return null;
}

function xlsx_coluna_para_indice($referenciaCelula) {
	preg_match('/[A-Z]+/', $referenciaCelula, $m);
	$letras = $m[0] ?? 'A';

	$indice = 0;
	for ($i = 0; $i < strlen($letras); $i++) {
		$indice = $indice * 26 + (ord($letras[$i]) - ord('A') + 1);
	}

	return $indice - 1;
}

function xlsx_ler($caminhoArquivo) {
	$zip = file_get_contents($caminhoArquivo);
	if ($zip === false) {
		throw new Exception('Não foi possível ler o arquivo enviado.');
	}

	$sharedStrings = [];
	$sharedStringsXml = xlsx_zip_ler_entrada($zip, 'xl/sharedStrings.xml');
	if ($sharedStringsXml !== null) {
		$sst = @simplexml_load_string($sharedStringsXml);
		if ($sst !== false) {
			foreach ($sst->si as $si) {
				if (isset($si->t)) {
					$sharedStrings[] = (string) $si->t;
				} else {
					$texto = '';
					foreach ($si->r as $r) {
						$texto .= (string) $r->t;
					}
					$sharedStrings[] = $texto;
				}
			}
		}
	}

	$sheetXml = xlsx_zip_ler_entrada($zip, 'xl/worksheets/sheet1.xml');
	if ($sheetXml === null) {
		throw new Exception('Não encontrei nenhuma planilha dentro do arquivo .xlsx enviado.');
	}

	$sheet = @simplexml_load_string($sheetXml);
	if ($sheet === false) {
		throw new Exception('Não foi possível interpretar o conteúdo da planilha.');
	}

	$linhas = [];
	foreach ($sheet->sheetData->row as $row) {
		$celulas = [];
		$maiorIndice = -1;

		foreach ($row->c as $c) {
			$ref = (string) $c['r'];
			$indice = $ref !== '' ? xlsx_coluna_para_indice($ref) : (count($celulas));
			$tipo = (string) $c['t'];

			if ($tipo === 's') {
				$posicao = isset($c->v) ? (int) $c->v : -1;
				$valor = $posicao >= 0 && isset($sharedStrings[$posicao]) ? $sharedStrings[$posicao] : '';
			} elseif ($tipo === 'inlineStr') {
				$valor = isset($c->is->t) ? (string) $c->is->t : '';
			} else {
				$valor = isset($c->v) ? (string) $c->v : '';
			}

			$celulas[$indice] = $valor;
			$maiorIndice = max($maiorIndice, $indice);
		}

		$linha = [];
		for ($i = 0; $i <= $maiorIndice; $i++) {
			$linha[] = $celulas[$i] ?? '';
		}

		$linhas[] = $linha;
	}

	return $linhas;
}
