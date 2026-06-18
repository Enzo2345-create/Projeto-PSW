<?php
require_once '../includes/funcoes.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['ok' => false, 'erro' => 'Não autenticado']);
    exit;
}

$senhaDigitada = $_POST['senha'] ?? '';
$usuario = $_SESSION['usuario'];

$usuarios = lerJson(__DIR__ . '/../data/users.json');

foreach ($usuarios as $u) {
    if ($u['usuario'] === $usuario) {
        $ok = password_verify($senhaDigitada, $u['senha']) || $u['senha'] === $senhaDigitada;
        echo json_encode(['ok' => $ok]);
        exit;
    }
}

echo json_encode(['ok' => false, 'erro' => 'Usuário não encontrado']);