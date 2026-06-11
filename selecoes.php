<?php include 'includes/header.php'; ?>

<head><link rel="stylesheet" href="assets/css/selecoes.css"></head>
<main>
    
    <section class="hero hero-small">
        <div class="hero-overlay"></div>
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-content">
            <span class="tag">HISTORIA — 1930 ATÉ 2022</span>
            <h1>Seleções Campeãs</h1>
            <p>22 edições. 8 países campeões. Uma história que nunca para de crescer.</p>
        </div>
    </section>

    <section class="selecoes-grid-section">

        <div class="selecoes-grid">
            <?php
            $copas = json_decode(file_get_contents('data/selecoes.json'), true);
            foreach($copas as $copa): ?>
                <a href="copa.php?ano=<?= $copa['ano'] ?>" class="selecao-card">
                    <img
                        src="<?= $copa['poster'] ?>"
                        alt="Poster <?= $copa['ano'] ?>"
                        class="selecao-poster"
                        onerror="this.style.display='none'"
                    >
                    <div class="selecao-ano"><?= $copa['ano'] ?></div>
                    <div class="selecao-info">
                        <h3><?= $copa['pais'] ?></h3>
                        <span class="selecao-apelido"><?= $copa['apelido'] ?></span>
                        <div class="selecao-meta">
                            <span>Sede: <?= $copa['sede'] ?></span>
                            <span>Final: <?= $copa['placar'] ?></span>
                        </div>
                    </div>
                    <div class="selecao-arrow">→</div>
                </a>
            <?php endforeach; ?>
        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>