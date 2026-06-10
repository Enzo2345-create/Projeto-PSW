<?php
session_start();


if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}


$selecoes = json_decode(file_get_contents('data/selecoes_2026.json'), true) ?? [];
$votos = [];


if (file_exists('data/votos.json')) {
    $votos = json_decode(file_get_contents('data/votos.json'), true) ?? [];
}


$usuario = $_SESSION['usuario'];
$votoUsuario = null;
$resultados = array_fill_keys($selecoes, 0);


foreach ($votos as $voto) {
    if (isset($resultados[$voto['selecao']])) {
        $resultados[$voto['selecao']]++;
    }


    if ($voto['usuario'] === $usuario) {
        $votoUsuario = $voto['selecao'];
    }
}


$totalVotos = count($votos);
arsort($resultados);


function bandeira($selecao) {
    $bandeiras = [
        'Canadá' => 'canada.png',
        'México' => 'mexico.png',
        'Estados Unidos' => 'estados_unidos.png',
        'Austrália' => 'australia.png',
        'Iraque' => 'iraque.png',
        'Irã' => 'ira.png',
        'Japão' => 'japao.png',
        'Jordânia' => 'jordania.png',
        'Coreia do Sul' => 'coreia_do_sul.png',
        'Catar' => 'catar.png',
        'Arábia Saudita' => 'arabia_saudita.png',
        'Uzbequistão' => 'uzbequistao.png',
        'Argélia' => 'argelia.png',
        'Cabo Verde' => 'cabo_verde.png',
        'República Democrática do Congo' => 'republica_democratica_do_congo.png',
        'Costa do Marfim' => 'costa_do_marfim.png',
        'Egito' => 'egito.png',
        'Gana' => 'gana.png',
        'Marrocos' => 'marrocos.png',
        'Senegal' => 'senegal.png',
        'África do Sul' => 'africa_do_sul.png',
        'Tunísia' => 'tunisia.png',
        'Curaçao' => 'curacao.png',
        'Haiti' => 'haiti.png',
        'Panamá' => 'panama.png',
        'Argentina' => 'argentina.png',
        'Brasil' => 'brasil.png',
        'Colômbia' => 'colombia.png',
        'Equador' => 'equador.png',
        'Paraguai' => 'paraguai.png',
        'Uruguai' => 'uruguai.png',
        'Nova Zelândia' => 'nova_zelandia.png',
        'Áustria' => 'austria.png',
        'Bélgica' => 'belgica.png',
        'Bósnia e Herzegovina' => 'bosnia_e_herzegovina.png',
        'Croácia' => 'croacia.png',
        'Tchéquia' => 'tchequia.png',
        'Inglaterra' => 'inglaterra.png',
        'França' => 'franca.png',
        'Alemanha' => 'alemanha.png',
        'Países Baixos' => 'paises_baixos.png',
        'Noruega' => 'noruega.png',
        'Portugal' => 'portugal.png',
        'Escócia' => 'escocia.png',
        'Espanha' => 'espanha.png',
        'Suécia' => 'suecia.png',
        'Suíça' => 'suica.png',
        'Turquia' => 'turquia.png'
    ];
   
    $arquivo = $bandeiras[$selecao] ?? 'padrao.png';
    return 'assets/imagens/bandeiras/' . $arquivo;
}
?>


<?php include 'includes/header.php'; ?>


<main class="votacao-page">


    <section class="votacao-topo">
        <h1>Escolha sua seleção</h1>


        <?php if ($votoUsuario): ?>
            <p>
                Seu voto atual:
                <strong>
                    <img src="<?= bandeira($votoUsuario) ?>" class="flag-img" alt="<?= htmlspecialchars($votoUsuario) ?>">
                    <?= htmlspecialchars($votoUsuario) ?>
                </strong>
            </p>
        <?php else: ?>
            <p>Vote na seleção que você acha que será campeã.</p>
        <?php endif; ?>


        <?php if (isset($_GET['sucesso'])): ?>
            <div class="voto-alert sucesso">Seu voto foi salvo com sucesso.</div>
        <?php endif; ?>


        <?php if (isset($_GET['erro'])): ?>
            <div class="voto-alert erro">Seleção inválida. Tente novamente.</div>
        <?php endif; ?>


        <form action="controllers/proc_voto.php" method="POST" class="votacao-form-clean">
            <select name="selecao" required>
                <option value="">Selecione uma seleção</option>


                <?php foreach ($selecoes as $selecao): ?>
                    <option
                        value="<?= htmlspecialchars($selecao) ?>"
                        <?= $votoUsuario === $selecao ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($selecao) ?>
                    </option>
                <?php endforeach; ?>
            </select>


            <button type="submit">
                <?= $votoUsuario ? 'Alterar voto' : 'Votar' ?>
            </button>
        </form>
    </section>


    <section class="resultado-card-clean">
        <h2>Resultado parcial</h2>
        <p class="total-votos">
            <strong><?= $totalVotos ?></strong> voto(s) registrado(s) (Total)
        </p>


        <div class="ranking-grid">
            <?php foreach ($resultados as $selecao => $quantidade): ?>
                <?php $porcentagem = $totalVotos > 0 ? round(($quantidade / $totalVotos) * 100) : 0; ?>


                <div class="ranking-linha <?= $votoUsuario === $selecao ? 'ativo' : '' ?>">
                    <div class="ranking-nome">
                        <img src="<?= bandeira($selecao) ?>" class="flag-img" alt="<?= htmlspecialchars($selecao) ?>">
                        <span><?= htmlspecialchars($selecao) ?></span>
                    </div>


                    <div class="ranking-barra">
                        <div style="width: <?= $porcentagem ?>%"></div>
                    </div>


                    <div class="ranking-numero">
                        <?= $quantidade ?> voto(s) - <?= $porcentagem ?>%
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


</main>


<?php include 'includes/footer.php'; ?>
