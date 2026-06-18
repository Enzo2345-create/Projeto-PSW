<?php
require_once '../includes/funcoes.php';

$usuarioAtual = verificarLogin();
$novoUsuario = limpar($_POST['novo_usuario'] ?? '');

if (empty($novoUsuario)) {
    header('Location: ../painel.php?erro=nome_vazio');
    exit;
}

$users = lerJson(__DIR__ . '/../data/users.json');

foreach ($users as $outro) {
    if ($outro['usuario'] === $novoUsuario && $outro['usuario'] !== $usuarioAtual) {
        header('Location: ../painel.php?erro=nome_existente');
        exit;
    }
}

$encontrado = false;
foreach ($users as &$u) {
    if ($u['usuario'] === $usuarioAtual) {
        $u['usuario'] = $novoUsuario;
        $encontrado = true;
        break;
    }
}

if ($encontrado) {
    escreverJson(__DIR__ . '/../data/users.json', $users);
    $_SESSION['usuario'] = $novoUsuario;
}

header('Location: ../painel.php?ok=nome_alterado');
exit;