<?php
$arquivo = __DIR__ . '/../data/users.json';

$usuario = $_POST['usuario'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$usuarios = [];

if (file_exists($arquivo)) {
    $conteudo = file_get_contents($arquivo);
    $usuarios = json_decode($conteudo, true) ?? [];
}

// Verifica se usuário já existe
foreach ($usuarios as $u) {
    if ($u['usuario'] === $usuario) {
        header('Location: ../cadastro.php?erro=usuario_existente');
        exit;
    }
}

$usuarios[] = [
    "usuario" => $usuario,
    "senha"   => $senha,
    "foto_perfil" => ""   // campo vazio para depois adicionar foto
];

file_put_contents(
    $arquivo,
    json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

header('Location: ../login.php?cadastro=ok');
exit;