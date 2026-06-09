<?php
    $arquivo = '../data/users.json';

    $usuario = $_POST['usuario'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $usuarios = [];

    if(file_exists($arquivo)){
        $conteudo = file_get_contents($arquivo);
        $usuarios = json_decode($conteudo, true) ?? [];
    }

    $usuarios[] = [
        "usuario" => $usuario,
        "senha"   => $senha
    ];

    file_put_contents(
        $arquivo,
        json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    header('Location: ../login.php');
?>