<?php include 'includes/header.php'; ?>

<head><link rel="stylesheet" href="assets/css/login.css"></head>
<main class="login-main">
    
    <div class="login-box">

        <div class="login-logo">MFM</div>
        <h2 class="login-titulo">Bem-vindo de volta</h2>
        <p class="login-sub">Acesse sua conta para continuar</p>

        <form action="controllers/proc_login.php" method="post" class="login-form">

            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>

        </form>

        <p class="login-cadastro">
            Não tem conta? <a href="cadastro.php">Cadastre-se</a>
        </p>

    </div>

</main>

<?php include 'includes/footer.php'; ?>