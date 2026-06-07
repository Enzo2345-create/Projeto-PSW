<?php
$copas = json_decode(file_get_contents('data/selecoes.json'), true);
$ano = $_GET['ano'] ?? null;
$copa = null;
foreach($copas as $c){
    if($c['ano'] == $ano){ $copa = $c; break; }
}
if(!$copa){ header('Location: selecoes.php'); exit; }

$grupos = [
    'Goleiros'      => $copa['goleiros'],
    'Defensores'    => $copa['defensores'],
    'Meio-campistas'=> $copa['meios'],
    'Atacantes'     => $copa['atacantes'],
];
?>

<?php include 'includes/header.php'; ?>

<main>

    <section class="hero hero-small">
        <div class="hero-overlay"></div>
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-content">
            <span class="tag">COPA DO MUNDO — <?= $copa['ano'] ?></span>
            <h1><?= $copa['pais'] ?></h1>
            <p><?= $copa['apelido'] ?></p>
        </div>
    </section>

    <section class="copa-detalhe">

        <div class="copa-resumo">

            <div class="copa-fotos panel">
                <div class="copa-foto-item">
                    <span class="resumo-label">Poster Oficial</span>
                    <img src="<?= $copa['poster'] ?>" alt="Poster <?= $copa['ano'] ?>" class="copa-img-poster">
                </div>
                <div class="copa-foto-item">
                    <span class="resumo-label">Seleção Campeã</span>
                    <img src="<?= $copa['foto_selecao'] ?>" alt="Seleção <?= $copa['pais'] ?>" class="copa-img-selecao">
                </div>
            </div>

            <div class="copa-resumo-grid">
                <div class="resumo-item">
                    <span class="resumo-label">Sede</span>
                    <span class="resumo-valor"><?= $copa['sede'] ?></span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-label">Vice-campeão</span>
                    <span class="resumo-valor"><?= $copa['vice'] ?></span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-label">Placar da Final</span>
                    <span class="resumo-valor"><?= $copa['placar'] ?></span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-label">Artilheiro</span>
                    <span class="resumo-valor"><?= $copa['artilheiro'] ?></span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-label">Técnico</span>
                    <span class="resumo-valor"><?= $copa['tecnico'] ?></span>
                </div>
            </div>

        </div>

        <div class="copa-elenco panel">

            <h2>Elenco Campeão</h2>

            <?php foreach($grupos as $titulo => $lista): ?>
                <div class="elenco-grupo">
                    <div class="elenco-titulo"><?= $titulo ?></div>
                    <div class="elenco-jogadores">
                        <?php foreach(explode(',', $lista) as $j):
                            $nome = trim($j); ?>
                            <div class="jogador-card fifa-card" data-nome="<?= htmlspecialchars($nome) ?>">
                                <div class="fifa-card-foto"></div>
                                <div class="fifa-card-info">
                                    <span class="fifa-card-nome"><?= $nome ?></span>
                                    <span class="fifa-card-pos"><?= $titulo ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="copa-nav">
            <a href="selecoes.php" class="btn">← Voltar para Seleções</a>
        </div>

    </section>

</main>

<script>
document.querySelectorAll('.fifa-card').forEach(card => {
    const nome = card.dataset.nome;
    const fotoDiv = card.querySelector('.fifa-card-foto');

    fetch(`/PROJETO PSW/controllers/buscar_foto.php?nome=${encodeURIComponent(nome)}`)
        .then(r => r.json())
        .then(data => {
            if(data.foto){
                fotoDiv.style.backgroundImage = `url('${data.foto}')`;
                fotoDiv.classList.add('tem-foto');
            } else {
                fotoDiv.classList.add('sem-foto');
                fotoDiv.innerHTML = `<span>${nome.trim().split(' ').filter(p=>p.length>1).slice(0,2).map(p=>p[0].toUpperCase()).join('')}</span>`;
            }
        })
        .catch(() => {
            fotoDiv.classList.add('sem-foto');
            fotoDiv.innerHTML = `<span>${nome.trim().split(' ').filter(p=>p.length>1).slice(0,2).map(p=>p[0].toUpperCase()).join('')}</span>`;
        });
});
</script>

<?php include 'includes/footer.php'; ?>