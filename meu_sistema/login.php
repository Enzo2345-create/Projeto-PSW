<?php include_once 'includes/header.php'; ?>
<main>
        <form action="controllers/con_login.php" method="post">
            <input type="text" name="usuário" placeholder="Usuário">
            <input type="password" name="senha" placeholder="Senha">
            <button type="submit">Entrar</button>
        </form>
</main>
<?php include_once 'includes/footer.php'; ?>