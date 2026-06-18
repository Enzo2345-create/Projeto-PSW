<?php
require_once '../includes/funcoes.php';

$usuario = limpar($_POST['usuario'] ?? '');
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$usuarios = lerJson(__DIR__ . '/../data/users.json');

foreach ($usuarios as $u) {
    if ($u['usuario'] === $usuario) {
        header('Location: ../cadastro.php?erro=usuario_existente');
        exit;
    }
}

$usuarios[] = [
    "usuario" => $usuario,
    "senha"   => $senha,
    "foto_perfil" => ""
];

escreverJson(__DIR__ . '/../data/users.json', $usuarios);

header('Location: ../login.php?cadastro=ok');
exit;