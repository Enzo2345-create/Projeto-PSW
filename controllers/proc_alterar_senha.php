<?php
require_once '../includes/funcoes.php';

$usuario = verificarLogin();
$senhaAtual = $_POST['senha_atual'] ?? '';
$novaSenha = $_POST['nova_senha'] ?? '';

if (empty($senhaAtual) || empty($novaSenha)) {
    header('Location: ../painel.php?erro=senha_vazia');
    exit;
}

$users = lerJson(__DIR__ . '/../data/users.json');
$encontrado = false;

foreach ($users as &$u) {
    if ($u['usuario'] === $usuario) {
        if (password_verify($senhaAtual, $u['senha'])) {
            $u['senha'] = password_hash($novaSenha, PASSWORD_DEFAULT);
            $encontrado = true;
        } else {
            header('Location: ../painel.php?erro=senha_atual_incorreta');
            exit;
        }
        break;
    }
}

if ($encontrado) {
    escreverJson(__DIR__ . '/../data/users.json', $users);
    header('Location: ../painel.php?ok=senha_alterada');
} else {
    header('Location: ../painel.php?erro=usuario_nao_encontrado');
}
exit;