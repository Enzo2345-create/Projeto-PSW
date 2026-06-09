<?php if(!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFM - Memória do Futebol Mundial</title>
    <link rel="stylesheet" href="assets/css/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="assets/js/fotos.js"></script>
</head>
<body>

<header>
    <div class="logo">MFM</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="selecoes.php">Seleções</a>
        <a href="dreamteam.php">Dream Team</a>
        <a href="votacao.php">Votação</a>
    </nav>

    <div class="header-perfil">
        <?php if(isset($_SESSION['usuario'])): ?>
            <a href="painel.php" class="avatar">
                <?= strtoupper(substr($_SESSION['usuario'], 0, 2)) ?>
            </a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Entrar</a>
        <?php endif; ?>
    </div>

</header>