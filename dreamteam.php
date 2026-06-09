<?php
session_start();
include 'includes/header.php';

$copas = json_decode(file_get_contents('data/selecoes.json'), true);

$jogadores = [];
foreach($copas as $copa){
    $posicoes = [
        'Goleiro'       => $copa['goleiros'],
        'Defensor'      => $copa['defensores'],
        'Meio-campista' => $copa['meios'],
        'Atacante'      => $copa['atacantes'],
    ];
    foreach($posicoes as $pos => $lista){
        foreach(explode(',', $lista) as $j){
            $nome = trim($j);
            if($nome){
                $jogadores[] = [
                    'nome'    => $nome,
                    'posicao' => $pos,
                    'copa'    => $copa['ano'].' — '.$copa['pais']
                ];
            }
        }
    }
}

$jogadores_json = json_encode($jogadores, JSON_UNESCAPED_UNICODE);
?>

<main>

    <section class="hero hero-small">
        <div class="hero-overlay"></div>
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-content">
            <span class="tag">DREAM TEAM — MONTE SEU TIME</span>
            <h1>Time dos Sonhos</h1>
            <p>Escolha a formação e arraste os jogadores para o campo.</p>
        </div>
    </section>

    <section class="dreamteam-section">

        <div class="dt-controles">
            <div class="dt-formacao">
                <label>Formação:</label>
                <select id="formacao" onchange="mudarFormacao()">
                    <option value="4-3-3">4-3-3</option>
                    <option value="4-4-2">4-4-2</option>
                    <option value="3-5-2">3-5-2</option>
                    <option value="5-3-2">5-3-2</option>
                    <option value="4-2-3-1">4-2-3-1</option>
                </select>
            </div>
            <button class="btn btn-limpar" onclick="limparTime()">Limpar Time</button>
        </div>

        <div class="dt-wrapper">

            <div class="campo" id="campo">
                <div class="campo-bg">
                    <div class="campo-linha meio"></div>
                    <div class="campo-circulo"></div>
                    <div class="campo-area area-cima"></div>
                    <div class="campo-area area-baixo"></div>
                </div>
                <div class="posicoes" id="posicoes"></div>
            </div>

            <div class="dt-sidebar">
                <div class="dt-filtros">
                    <input type="text" id="busca" placeholder="Buscar jogador..." oninput="filtrarJogadores()">
                    <select id="filtro-pos" onchange="filtrarJogadores()">
                        <option value="">Todas as posições</option>
                        <option value="Goleiro">Goleiros</option>
                        <option value="Defensor">Defensores</option>
                        <option value="Meio-campista">Meios</option>
                        <option value="Atacante">Atacantes</option>
                    </select>
                    <select id="filtro-copa" onchange="filtrarJogadores()">
                        <option value="">Todas as Copas</option>
                        <?php foreach($copas as $c): ?>
                            <option value="<?= $c['ano'].' — '.$c['pais'] ?>"><?= $c['ano'] ?> — <?= $c['pais'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="dt-jogadores" id="lista-jogadores"></div>
            </div>

        </div>

    </section>

</main>

<script>
const JOGADORES = <?= $jogadores_json ?>;
const BASE_URL = '/PROJETO PSW/controllers/buscar_foto.php?nome=';

const POSICOES_LAYOUT = {
    '4-3-3': [
        {label:'GOL', x:50, y:88},
        {label:'LD',  x:20, y:70},
        {label:'ZAG', x:38, y:70},
        {label:'ZAG', x:62, y:70},
        {label:'LE',  x:80, y:70},
        {label:'MC',  x:30, y:50},
        {label:'MC',  x:50, y:50},
        {label:'MC',  x:70, y:50},
        {label:'PD',  x:20, y:25},
        {label:'CA',  x:50, y:18},
        {label:'PE',  x:80, y:25},
    ],
    '4-4-2': [
        {label:'GOL', x:50, y:88},
        {label:'LD',  x:15, y:70},
        {label:'ZAG', x:35, y:70},
        {label:'ZAG', x:65, y:70},
        {label:'LE',  x:85, y:70},
        {label:'MD',  x:15, y:48},
        {label:'MC',  x:38, y:48},
        {label:'MC',  x:62, y:48},
        {label:'ME',  x:85, y:48},
        {label:'CA',  x:35, y:20},
        {label:'CA',  x:65, y:20},
    ],
    '3-5-2': [
        {label:'GOL', x:50, y:88},
        {label:'ZAG', x:25, y:70},
        {label:'ZAG', x:50, y:70},
        {label:'ZAG', x:75, y:70},
        {label:'AD',  x:10, y:50},
        {label:'MC',  x:30, y:50},
        {label:'MC',  x:50, y:50},
        {label:'MC',  x:70, y:50},
        {label:'AE',  x:90, y:50},
        {label:'CA',  x:35, y:20},
        {label:'CA',  x:65, y:20},
    ],
    '5-3-2': [
        {label:'GOL', x:50, y:88},
        {label:'LD',  x:10, y:70},
        {label:'ZAG', x:28, y:70},
        {label:'ZAG', x:50, y:70},
        {label:'ZAG', x:72, y:70},
        {label:'LE',  x:90, y:70},
        {label:'MC',  x:25, y:45},
        {label:'MC',  x:50, y:45},
        {label:'MC',  x:75, y:45},
        {label:'CA',  x:35, y:20},
        {label:'CA',  x:65, y:20},
    ],
    '4-2-3-1': [
        {label:'GOL', x:50, y:88},
        {label:'LD',  x:15, y:70},
        {label:'ZAG', x:38, y:70},
        {label:'ZAG', x:62, y:70},
        {label:'LE',  x:85, y:70},
        {label:'VOL', x:35, y:55},
        {label:'VOL', x:65, y:55},
        {label:'MAE', x:20, y:35},
        {label:'MAD', x:80, y:35},
        {label:'MEI', x:50, y:35},
        {label:'CA',  x:50, y:18},
    ],
};

let time = Array(11).fill(null);
let dragJogador = null;

// Gera iniciais do nome
function iniciais(nome){
    return nome.trim().split(' ')
        .filter(p => p.length > 1)
        .slice(0, 2)
        .map(p => p[0].toUpperCase())
        .join('');
}

// Cria elemento de foto circular
function criarCirculoFoto(nome, tamanho){
    const div = document.createElement('div');
    div.className = 'dt-avatar';
    div.style.width  = tamanho + 'px';
    div.style.height = tamanho + 'px';
    div.innerHTML = `<span>${iniciais(nome)}</span>`;

    fetch(BASE_URL + encodeURIComponent(nome))
        .then(r => r.json())
        .then(data => {
            if(data.foto){
                div.innerHTML = `<img src="${data.foto}" alt="${nome}" onerror="this.parentElement.innerHTML='<span>${iniciais(nome)}</span>'">`;
                div.classList.add('tem-foto');
            }
        })
        .catch(() => {});

    return div;
}

function mudarFormacao(){
    time = Array(11).fill(null);
    renderCampo();
    filtrarJogadores();
}

function renderCampo(){
    const formacao = document.getElementById('formacao').value;
    const layout   = POSICOES_LAYOUT[formacao];
    const container = document.getElementById('posicoes');
    container.innerHTML = '';

    layout.forEach((pos, i) => {
        const div = document.createElement('div');
        div.className = 'posicao';
        div.style.left = pos.x + '%';
        div.style.top  = pos.y + '%';

        if(time[i]){
            div.classList.add('ocupada');

            const avatar = criarCirculoFoto(time[i].nome, 38);
            const sobrenome = time[i].nome.split(' ').slice(-1)[0];

            div.innerHTML = `
                <div class="pos-jogador">
                    <div class="pos-avatar-wrap"></div>
                    <span class="pos-nome">${sobrenome}</span>
                    <span class="pos-label">${pos.label}</span>
                    <button class="pos-remover" onclick="removerJogador(${i})">×</button>
                </div>`;

            div.querySelector('.pos-avatar-wrap').appendChild(avatar);

        } else {
            div.innerHTML = `<span class="pos-label">${pos.label}</span>`;
            div.addEventListener('dragover', e => e.preventDefault());
            div.addEventListener('drop', () => droparJogador(i));
        }

        container.appendChild(div);
    });
}

function filtrarJogadores(){
    const busca    = document.getElementById('busca').value.toLowerCase();
    const pos      = document.getElementById('filtro-pos').value;
    const copa     = document.getElementById('filtro-copa').value;
    const ocupados = time.filter(Boolean).map(j => j.nome);

    const lista = JOGADORES.filter(j =>
        (!busca || j.nome.toLowerCase().includes(busca))
        && (!pos  || j.posicao === pos)
        && (!copa || j.copa === copa)
        && !ocupados.includes(j.nome)
    );

    const container = document.getElementById('lista-jogadores');
    container.innerHTML = '';

    if(lista.length === 0){
        container.innerHTML = '<p class="dt-vazio">Nenhum jogador encontrado.</p>';
        return;
    }

    lista.forEach(j => {
        const div = document.createElement('div');
        div.className = 'jogador-item';
        div.draggable = true;

        const avatar = criarCirculoFoto(j.nome, 42);

        const info = document.createElement('div');
        info.className = 'jogador-item-info';
        info.innerHTML = `
            <div class="jogador-nome">${j.nome}</div>
            <div class="jogador-meta">${j.posicao} — ${j.copa}</div>`;

        div.appendChild(avatar);
        div.appendChild(info);
        div.addEventListener('dragstart', () => { dragJogador = j; });
        container.appendChild(div);
    });
}

function droparJogador(index){
    if(!dragJogador) return;
    time[index] = dragJogador;
    dragJogador = null;
    renderCampo();
    filtrarJogadores();
}

function removerJogador(index){
    time[index] = null;
    renderCampo();
    filtrarJogadores();
}

function limparTime(){
    time = Array(11).fill(null);
    renderCampo();
    filtrarJogadores();
}

renderCampo();
filtrarJogadores();
</script>

<?php include 'includes/footer.php'; ?>