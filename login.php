<?php include 'includes/header.php'; ?>
<main>
        <form action="controllers/proc_login.php" method="post">
            <input type="text" name="usuário" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
</main>
<?php include 'includes/footer.php'; ?>