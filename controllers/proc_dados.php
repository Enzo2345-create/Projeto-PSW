<?php
/* proc_dados.php — verifica se a senha digitada é a do usuário logado */
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['ok' => false, 'erro' => 'Não autenticado']);
    exit;
}

$senhaDigitada = $_POST['senha'] ?? '';

// Carrega usuários
$arquivo = __DIR__ . '/../data/users.json';
if (!file_exists($arquivo)) {
    echo json_encode(['ok' => false, 'erro' => 'Arquivo não encontrado']);
    exit;
}

$usuarios = json_decode(file_get_contents($arquivo), true) ?? [];
$usuario  = $_SESSION['usuario'];

foreach ($usuarios as $u) {
    if ($u['usuario'] === $usuario) {
        // Tenta password_verify (hash) e também comparação direta (caso esteja em texto puro)
        $ok = password_verify($senhaDigitada, $u['senha']) || $u['senha'] === $senhaDigitada;
        echo json_encode(['ok' => $ok]);
        exit;
    }
}

echo json_encode(['ok' => false, 'erro' => 'Usuário não encontrado']);
