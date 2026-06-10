<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$usuarioAtual = $_SESSION['usuario'];
$novoUsuario = trim($_POST['novo_usuario']);
if (empty($novoUsuario)) {
    header('Location: ../painel.php?erro=nome_vazio');
    exit;
}

$arquivo = __DIR__ . '/../data/users.json';
$users = json_decode(file_get_contents($arquivo), true);
$encontrado = false;
foreach ($users as &$u) {
    if ($u['usuario'] === $usuarioAtual) {
        // Verifica se o novo nome já existe
        foreach ($users as $outro) {
            if ($outro['usuario'] === $novoUsuario && $outro['usuario'] !== $usuarioAtual) {
                header('Location: ../painel.php?erro=nome_existente');
                exit;
            }
        }
        $u['usuario'] = $novoUsuario;
        $encontrado = true;
        break;
    }
}
if ($encontrado) {
    file_put_contents($arquivo, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['usuario'] = $novoUsuario;
}
header('Location: ../painel.php?ok=nome_alterado');
exit;