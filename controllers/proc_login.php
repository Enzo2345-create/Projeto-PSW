<?php

session_start();

$arquivo = '../data/users.json';

$usuario = $_POST['usuario'];
$senha   = $_POST['senha'];

if(file_exists($arquivo)){
    $conteudo = file_get_contents($arquivo);
    $usuarios = json_decode($conteudo, true) ?? [];

    foreach($usuarios as $u){
        if($u['usuario'] === $usuario && password_verify($senha, $u['senha'])){
            $_SESSION['usuario'] = $usuario;
            $_SESSION['logado']  = true;
            header('Location: ../painel.php');
            exit;
        }
    }
}

header('Location: ../login.php?erro=1');
exit;