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

            <a href="dreamteam.php" class="card" id="card-dreamteam">
                <h3>Time dos Sonhos</h3>
                <p>
                    Monte seu time ideal utilizando jogadores históricos.
                </p>
            </a>

            <a href="votacao.php" class="card" id="card-selecoes">
                <h3>Votação 2026</h3>
                <p>
                    Vote nos melhores jogadores e seleções da Copa 2026.
                </p>
            </a>

        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>