<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$selecao = trim($_POST['selecao'] ?? '');

$arquivoVotos = __DIR__ . '/../data/votos.json';
$arquivoSelecoes = __DIR__ . '/../data/selecoes_2026.json';

$selecoes = [];

if (file_exists($arquivoSelecoes)) {
    $selecoes = json_decode(file_get_contents($arquivoSelecoes), true);
}

if (!is_array($selecoes)) {
    $selecoes = [];
}

if ($selecao === '' || !in_array($selecao, $selecoes)) {
    header('Location: ../votacao.php?erro=1');
    exit;
}

$votos = [];

if (file_exists($arquivoVotos)) {
    $votos = json_decode(file_get_contents($arquivoVotos), true);
}

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

file_put_contents(
    $arquivoVotos,
    json_encode($votos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

header('Location: ../votacao.php?sucesso=1');
exit;