<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$senhaAtual = $_POST['senha_atual'];
$novaSenha = $_POST['nova_senha'];

if (empty($senhaAtual) || empty($novaSenha)) {
    header('Location: ../painel.php?erro=senha_vazia');
    exit;
}

$arquivo = __DIR__ . '/../data/users.json';
$users = json_decode(file_get_contents($arquivo), true);
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
    file_put_contents($arquivo, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: ../painel.php?ok=senha_alterada');
} else {
    header('Location: ../painel.php?erro=usuario_nao_encontrado');
}
exit;