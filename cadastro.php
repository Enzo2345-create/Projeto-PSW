<?php include 'includes/header.php'; ?>
<head><link rel="stylesheet" href="assets/css/login.css"></head>
<main class="login-main">
    
    <div class="login-box">

        <div class="login-logo">MFM</div>
        <h2 class="login-titulo">Criar conta</h2>
        <p class="login-sub">Junte-se à Memória do Futebol Mundial</p>

        <form method="post" action="controllers/proc_cadastro.php" class="login-form">

            <input name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Cadastrar</button>

        </form>

        <p class="login-cadastro">
            Já tem conta? <a href="login.php">Entrar</a>
        </p>

    </div>

</main>

<?php include 'includes/footer.php'; ?>