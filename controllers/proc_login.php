<?php
require_once '../includes/funcoes.php';

session_start();

$usuario = limpar($_POST['usuario'] ?? '');
$senha = $_POST['senha'] ?? '';

$usuarios = lerJson(__DIR__ . '/../data/users.json');

foreach ($usuarios as $u) {
    if ($u['usuario'] === $usuario && password_verify($senha, $u['senha'])) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['logado'] = true;
        $_SESSION['foto_perfil'] = $u['foto_perfil'] ?? '';
        header('Location: ../painel.php');
        exit;
    }
}

header('Location: ../login.php?erro=1');
exit;