<?php
session_start();
if(!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<main>

    <section class="hero">

        <div class="hero-overlay"></div>

        <div class="hero-content">

            <span class="tag">BEM-VINDO DE VOLTA</span>

            <h1>
                OLÁ,
                <?= strtoupper($_SESSION['usuario']) ?>!
            </h1>

            <p>
                Explore o futebol mundial, monte seu Dream Team
                e vote nos melhores da Copa 2026.
            </p>

        </div>

    </section>

    <section class="features">

        <div class="section-title">
            <h2>O que você quer fazer?</h2>
            <p>Escolha uma opção abaixo</p>
        </div>

        <div class="cards">

            <a href="selecoes.php" class="card" id="card-selecoes">
                <div class="card-badge">Histórico</div>
                <h3>Seleções Campeãs</h3>
                <p>
                    Conheça a história completa de cada seleção
                    campeã do mundo desde 1930.
                </p>
                <span class="card-link">Ver seleções →</span>
            </a>

            <a href="dreamteam.php" class="card" id="card-dreamteam">
                <div class="card-badge">Interativo</div>
                <h3>Time dos Sonhos</h3>
                <p>
                    Monte seu time ideal utilizando jogadores
                    históricos das Copas do Mundo.
                </p>
                <span class="card-link">Montar time →</span>
            </a>

        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>