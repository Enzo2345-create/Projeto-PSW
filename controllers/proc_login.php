<?php 
    session_start();
    function login($usuario, $senha) {
        if(isset($usuario) && isset($senha)) {
            if($usuario == 'welison' && $senha == '1234') {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['logado'] = true;
                header('Location: ../index.php');
                exit;
            }else{
            header('Location: ../login.php');
            exit;}
        }
    }
    login($_POST['usuário'], $_POST['senha']);
?> 