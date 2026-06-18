<?php
require_once '../includes/funcoes.php';

$usuario = verificarLogin();
$selecao = limpar($_POST['selecao'] ?? '');

$arquivoSelecoes = __DIR__ . '/../data/selecoes_2026.json';
$selecoes = lerJson($arquivoSelecoes);

if (!is_array($selecoes)) {
    $selecoes = [];
}

if ($selecao === '' || !in_array($selecao, $selecoes)) {
    header('Location: ../votacao.php?erro=1');
    exit;
}

$arquivoVotos = __DIR__ . '/../data/votos.json';
$votos = lerJson($arquivoVotos);

if (!is_array($votos)) {
    $votos = [];
}

$jaVotou = false;
foreach ($votos as &$voto) {
    if ($voto['usuario'] === $usuario) {
        $voto['selecao'] = $selecao;
        $voto['data'] = date('Y-m-d H:i:s');
        $jaVotou = true;
        break;
    }
}

if (!$jaVotou) {
    $votos[] = [
        'usuario' => $usuario,
        'selecao' => $selecao,
        'data' => date('Y-m-d H:i:s')
    ];
}

escreverJson($arquivoVotos, $votos);

header('Location: ../votacao.php?sucesso=1');
exit;