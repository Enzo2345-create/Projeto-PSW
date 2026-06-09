<?php
session_start();
include 'includes/header.php';

$copas = json_decode(file_get_contents('data/selecoes.json'), true);

$jogadores = [];

foreach($copas as $copa){
    $ano = $copa['ano'];
    $pais = $copa['pais'];
    $copaLabel = "$ano — $pais";
    
    $grupos = [
        'goleiros'    => 'Goleiro',
        'defensores'  => 'Defensor',
        'meios'       => 'Meio-campista',
        'atacantes'   => 'Atacante'
    ];
    
    foreach($grupos as $grupoKey => $posicao){
        if(!isset($copa[$grupoKey]) || !is_array($copa[$grupoKey])) continue;
        foreach($copa[$grupoKey] as $jogadorObj){
            $nome = $jogadorObj['nome'] ?? '';
            if(empty($nome)) continue;
            $foto = $jogadorObj['foto'] ?? '';
            if(!empty($foto) && !file_exists($foto)) $foto = '';
            $jogadores[] = [
                'nome'    => $nome,
                'posicao' => $posicao,
                'copa'    => $copaLabel,
                'foto'    => $foto
            ];
        }
    }
}

$jogadores_json = json_encode($jogadores, JSON_UNESCAPED_UNICODE);
?>

<style>
/* ========== ESTILOS DREAM TEAM ========== */
.dream-wrapper {
    display: flex;
    flex-direction: row;
    gap: 20px;
    flex-wrap: wrap;
    margin: 20px 0;
}

.dream-campo-container {
    flex: 3;
    min-width: 700px;
    background: #1a5d3c;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    position: relative;
    aspect-ratio: 16 / 9;
}

.dream-campo {
    position: relative;
    width: 100%;
    height: 100%;
}

.dream-campo-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1a5d3c 0%, #1e6b45 100%);
}
.dream-campo-bg::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: repeating-linear-gradient(90deg, rgba(255,255,255,0.05) 0px, rgba(255,255,255,0.05) 30px, transparent 30px, transparent 60px);
}

.dream-campo-linha-meio {
    position: absolute;
    width: 2px;
    height: 100%;
    background: white;
    left: 50%;
    top: 0;
    transform: translateX(-50%);
}
.dream-campo-circulo {
    position: absolute;
    width: 80px;
    height: 80px;
    border: 2px solid white;
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
/* Área grande (penalty) */
.dream-campo-area {
    position: absolute;
    width: 12%;
    height: 40%;
    border: 2px solid white;
    background: rgba(0,0,0,0.1);
}
.dream-area-esquerda {
    left: 0;
    top: 30%;
    border-left: none;
}
.dream-area-direita {
    right: 0;
    top: 30%;
    border-right: none;
}
/* Área pequena (6 jardas) */
.dream-campo-area-pequena {
    position: absolute;
    width: 5%;
    height: 20%;
    border: 1px solid white;
}
.dream-pequena-esquerda {
    left: 0;
    top: 40%;
    border-left: none;
}
.dream-pequena-direita {
    right: 0;
    top: 40%;
    border-right: none;
}

.dream-posicoes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.dream-posicao {
    position: absolute;
    transform: translate(-50%, -50%);
    width: 80px;
    text-align: center;
    cursor: pointer;
}
.dream-avatar-wrap {
    width: 70px;
    height: 70px;
    margin: 0 auto 4px;
    border-radius: 50%;
    overflow: hidden;
    background: #2c2c2c;
    border: 2px solid #ffd700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dream-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    background: #2c2c2c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
}
.dream-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.dream-nome {
    display: block;
    font-size: 11px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 2px 6px;
    border-radius: 12px;
    white-space: nowrap;
    overflow-x: hidden;
    text-overflow: ellipsis;
    max-width: 80px;
}
.dream-label {
    display: inline-block;
    background: #ffd700;
    color: #1a5d3c;
    font-weight: bold;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 12px;
    margin-top: 2px;
}
.dream-remover {
    position: absolute;
    top: -8px;
    right: -8px;
    background: red;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    cursor: pointer;
    line-height: 1;
}

.dream-sidebar {
    flex: 2;
    min-width: 280px;
    background: #f5f5f5;
    border-radius: 20px;
    padding: 15px;
    max-height: 500px;
    overflow-y: auto;
}
.dream-filtros input, .dream-filtros select {
    width: 100%;
    margin-bottom: 10px;
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.dream-jogadores {
    margin-top: 15px;
}
.dream-jogador-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: white;
    margin-bottom: 10px;
    padding: 8px;
    border-radius: 40px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    cursor: grab;
    transition: transform 0.1s, box-shadow 0.1s;
}
.dream-jogador-item:active {
    cursor: grabbing;
}
.dream-jogador-item.dragging {
    opacity: 0.4;
    transform: scale(0.98);
}
.dream-jogador-item .dream-avatar {
    width: 60px;
    height: 60px;
    flex-shrink: 0;
}
.dream-jogador-info {
    flex: 1;
}
.dream-jogador-nome {
    font-weight: bold;
    font-size: 14px;
}
.dream-jogador-meta {
    font-size: 12px;
    color: #666;
}
.dream-controles {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: wrap;
}
.dream-btn-limpar {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
}
.dream-formacao select {
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
@media (max-width: 900px) {
    .dream-wrapper { flex-direction: column; }
    .dream-campo-container { min-width: auto; }
}
</style>

<main>
    <section class="hero hero-small">
        <div class="hero-overlay"></div>
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-content">
            <span class="tag">DREAM TEAM — MONTE SEU TIME</span>
            <h1>Time dos Sonhos</h1>
            <p>Clique numa posição vazia ou arraste um jogador para o campo.</p>
        </div>
    </section>

    <section class="dreamteam-section">
        <div class="dream-controles">
            <div class="dream-formacao">
                <label>Formação:</label>
                <select id="dreamFormacao" onchange="dreamMudarFormacao()">
                    <option value="4-3-3">4-3-3</option>
                    <option value="4-4-2">4-4-2</option>
                    <option value="3-5-2">3-5-2</option>
                    <option value="5-3-2">5-3-2</option>
                    <option value="4-2-3-1">4-2-3-1</option>
                </select>
            </div>
            <button class="dream-btn-limpar" onclick="dreamLimparTime()">Limpar Time</button>
        </div>

        <div class="dream-wrapper">
            <div class="dream-campo-container">
                <div class="dream-campo">
                    <div class="dream-campo-bg"></div>
                    <div class="dream-campo-linha-meio"></div>
                    <div class="dream-campo-circulo"></div>
                    <div class="dream-campo-area dream-area-esquerda"></div>
                    <div class="dream-campo-area dream-area-direita"></div>
                    <div class="dream-campo-area-pequena dream-pequena-esquerda"></div>
                    <div class="dream-campo-area-pequena dream-pequena-direita"></div>
                    <div class="dream-posicoes" id="dreamPosicoes"></div>
                </div>
            </div>

            <div class="dream-sidebar">
                <div class="dream-filtros">
                    <input type="text" id="dreamBusca" placeholder="Buscar jogador..." oninput="dreamFiltrarJogadores()">
                    <select id="dreamFiltroPos" onchange="dreamFiltrarJogadores()">
                        <option value="">Todas as posições</option>
                        <option value="Goleiro">Goleiros</option>
                        <option value="Defensor">Defensores</option>
                        <option value="Meio-campista">Meios</option>
                        <option value="Atacante">Atacantes</option>
                    </select>
                    <select id="dreamFiltroCopa" onchange="dreamFiltrarJogadores()">
                        <option value="">Todas as Copas</option>
                        <?php foreach($copas as $c): ?>
                            <option value="<?= $c['ano'].' — '.$c['pais'] ?>"><?= $c['ano'] ?> — <?= $c['pais'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="dream-jogadores" id="dreamListaJogadores"></div>
            </div>
        </div>
    </section>
</main>

<script>
const DREAM_JOGADORES = <?= $jogadores_json ?>;

// Mapeamento do rótulo da posição para filtro de posição
const DREAM_MAPEAMENTO_POSICAO = {
    'GOL': 'Goleiro',
    'LD': 'Defensor', 'ZAG': 'Defensor', 'LE': 'Defensor',
    'MC': 'Meio-campista', 'MD': 'Meio-campista', 'ME': 'Meio-campista',
    'VOL': 'Meio-campista', 'MEI': 'Meio-campista', 'MAD': 'Atacante', 'MAE': 'Atacante',
    'CA': 'Atacante', 'PD': 'Atacante', 'PE': 'Atacante', 'AD': 'Atacante', 'AE': 'Atacante'
};

const DREAM_POSICOES_LAYOUT = {
    '4-3-3': [
        {label:'GOL', x:8,  y:50},
        {label:'LD',  x:25, y:25},
        {label:'ZAG', x:35, y:15},
        {label:'ZAG', x:35, y:85},
        {label:'LE',  x:25, y:75},
        {label:'MC',  x:50, y:25},
        {label:'MC',  x:55, y:50},
        {label:'MC',  x:50, y:75},
        {label:'PD',  x:80, y:20},
        {label:'CA',  x:85, y:50},
        {label:'PE',  x:80, y:80},
    ],
    '4-4-2': [
        {label:'GOL', x:8,  y:50},
        {label:'LD',  x:25, y:20},
        {label:'ZAG', x:35, y:10},
        {label:'ZAG', x:35, y:90},
        {label:'LE',  x:25, y:80},
        {label:'MD',  x:50, y:20},
        {label:'MC',  x:55, y:40},
        {label:'MC',  x:55, y:60},
        {label:'ME',  x:50, y:80},
        {label:'CA',  x:80, y:35},
        {label:'CA',  x:80, y:65},
    ],
    '3-5-2': [
        {label:'GOL', x:8,  y:50},
        {label:'ZAG', x:30, y:20},
        {label:'ZAG', x:30, y:50},
        {label:'ZAG', x:30, y:80},
        {label:'AD',  x:45, y:10},
        {label:'MC',  x:55, y:25},
        {label:'MC',  x:60, y:50},
        {label:'MC',  x:55, y:75},
        {label:'AE',  x:45, y:90},
        {label:'CA',  x:80, y:35},
        {label:'CA',  x:80, y:65},
    ],
    '5-3-2': [
        {label:'GOL', x:8,  y:50},
        {label:'LD',  x:20, y:20},
        {label:'ZAG', x:30, y:10},
        {label:'ZAG', x:35, y:50},
        {label:'ZAG', x:30, y:90},
        {label:'LE',  x:20, y:80},
        {label:'MC',  x:50, y:25},
        {label:'MC',  x:55, y:50},
        {label:'MC',  x:50, y:75},
        {label:'CA',  x:80, y:35},
        {label:'CA',  x:80, y:65},
    ],
    '4-2-3-1': [
        {label:'GOL', x:8,  y:50},
        {label:'LD',  x:25, y:20},
        {label:'ZAG', x:35, y:15},
        {label:'ZAG', x:35, y:85},
        {label:'LE',  x:25, y:80},
        {label:'VOL', x:45, y:30},
        {label:'VOL', x:45, y:70},
        {label:'MAD', x:65, y:15},
        {label:'MAE', x:65, y:85},
        {label:'MEI', x:65, y:50},
        {label:'CA',  x:85, y:50},
    ],
};

let dreamTime = Array(11).fill(null);
let dreamDragJogador = null;
let dreamPosicaoSelecionada = null; // índice da posição aguardando escolha

function dreamIniciais(nome){
    return nome.trim().split(' ')
        .filter(p => p.length > 1)
        .slice(0, 2)
        .map(p => p[0].toUpperCase())
        .join('');
}

function dreamCriarAvatar(jogador, tamanho){
    const div = document.createElement('div');
    div.className = 'dream-avatar';
    div.style.width = tamanho + 'px';
    div.style.height = tamanho + 'px';
    if(jogador.foto && jogador.foto.trim() !== ''){
        const img = document.createElement('img');
        img.src = jogador.foto;
        img.alt = jogador.nome;
        img.onerror = function(){
            div.innerHTML = `<span>${dreamIniciais(jogador.nome)}</span>`;
        };
        div.appendChild(img);
    } else {
        div.innerHTML = `<span>${dreamIniciais(jogador.nome)}</span>`;
    }
    return div;
}

// Renderiza o campo com as posições
function dreamRenderCampo(){
    const formacao = document.getElementById('dreamFormacao').value;
    const layout = DREAM_POSICOES_LAYOUT[formacao];
    const container = document.getElementById('dreamPosicoes');
    container.innerHTML = '';
    layout.forEach((pos, i) => {
        const div = document.createElement('div');
        div.className = 'dream-posicao';
        div.style.left = pos.x + '%';
        div.style.top = pos.y + '%';
        if(dreamTime[i]){
            const avatar = dreamCriarAvatar(dreamTime[i], 70);
            const sobrenome = dreamTime[i].nome.split(' ').slice(-1)[0];
            div.innerHTML = `
                <div class="dream-avatar-wrap"></div>
                <span class="dream-nome">${sobrenome}</span>
                <span class="dream-label">${pos.label}</span>
                <button class="dream-remover" onclick="dreamRemoverJogador(${i})">×</button>
            `;
            div.querySelector('.dream-avatar-wrap').appendChild(avatar);
        } else {
            div.innerHTML = `<span class="dream-label">${pos.label}</span>`;
            // Evento de clique para selecionar posição e filtrar jogadores
            div.addEventListener('click', (e) => {
                e.stopPropagation();
                dreamPosicaoSelecionada = i;
                // Filtra a lista pela posição esperada
                const posLabel = pos.label;
                const filtroPos = DREAM_MAPEAMENTO_POSICAO[posLabel] || '';
                if(filtroPos){
                    document.getElementById('dreamFiltroPos').value = filtroPos;
                } else {
                    document.getElementById('dreamFiltroPos').value = '';
                }
                // Limpa busca
                document.getElementById('dreamBusca').value = '';
                dreamFiltrarJogadores();
                // Destaque visual da posição (opcional)
                document.querySelectorAll('.dream-posicao').forEach(p => p.style.boxShadow = '');
                div.style.boxShadow = '0 0 0 3px #ffd700';
                setTimeout(() => { div.style.boxShadow = ''; }, 1000);
            });
            div.addEventListener('dragover', e => e.preventDefault());
            div.addEventListener('drop', () => dreamDroparJogador(i));
        }
        container.appendChild(div);
    });
}

// Filtra a lista de jogadores (considerando posição selecionada e filtros manuais)
function dreamFiltrarJogadores(){
    const busca = document.getElementById('dreamBusca').value.toLowerCase();
    let pos = document.getElementById('dreamFiltroPos').value;
    const copa = document.getElementById('dreamFiltroCopa').value;
    const ocupados = dreamTime.filter(Boolean).map(j => j.nome);
    let lista = DREAM_JOGADORES.filter(j =>
        (!busca || j.nome.toLowerCase().includes(busca)) &&
        (!pos || j.posicao === pos) &&
        (!copa || j.copa === copa) &&
        !ocupados.includes(j.nome)
    );
    const container = document.getElementById('dreamListaJogadores');
    container.innerHTML = '';
    if(lista.length === 0){
        container.innerHTML = '<p class="dt-vazio">Nenhum jogador encontrado.</p>';
        return;
    }
    lista.forEach(jogador => {
        const div = document.createElement('div');
        div.className = 'dream-jogador-item';
        div.draggable = true;
        const avatar = dreamCriarAvatar(jogador, 60);
        const info = document.createElement('div');
        info.className = 'dream-jogador-info';
        info.innerHTML = `
            <div class="dream-jogador-nome">${jogador.nome}</div>
            <div class="dream-jogador-meta">${jogador.posicao} — ${jogador.copa}</div>
        `;
        div.appendChild(avatar);
        div.appendChild(info);
        
        // Se há uma posição aguardando seleção, ao clicar no jogador coloca diretamente
        div.addEventListener('click', () => {
            if(dreamPosicaoSelecionada !== null && !dreamTime[dreamPosicaoSelecionada]){
                dreamTime[dreamPosicaoSelecionada] = jogador;
                dreamPosicaoSelecionada = null;
                dreamRenderCampo();
                dreamFiltrarJogadores();
            }
        });
        
        // Drag and drop melhorado
        div.addEventListener('dragstart', (e) => {
            dreamDragJogador = jogador;
            // Cria uma imagem personalizada para o drag
            const dragImg = document.createElement('div');
            dragImg.style.width = '100px';
            dragImg.style.background = '#fff';
            dragImg.style.borderRadius = '50px';
            dragImg.style.padding = '8px';
            dragImg.style.display = 'flex';
            dragImg.style.alignItems = 'center';
            dragImg.style.gap = '8px';
            dragImg.style.boxShadow = '0 4px 10px rgba(0,0,0,0.3)';
            const avatarClone = dreamCriarAvatar(jogador, 40);
            dragImg.appendChild(avatarClone);
            const nameSpan = document.createElement('span');
            nameSpan.innerText = jogador.nome;
            nameSpan.style.fontSize = '12px';
            dragImg.appendChild(nameSpan);
            document.body.appendChild(dragImg);
            e.dataTransfer.setDragImage(dragImg, 20, 20);
            setTimeout(() => document.body.removeChild(dragImg), 0);
            e.dataTransfer.effectAllowed = 'copy';
            div.classList.add('dragging');
        });
        div.addEventListener('dragend', () => {
            div.classList.remove('dragging');
            dreamDragJogador = null;
        });
        container.appendChild(div);
    });
}

function dreamDroparJogador(index){
    if(dreamDragJogador){
        dreamTime[index] = dreamDragJogador;
        dreamDragJogador = null;
        dreamRenderCampo();
        dreamFiltrarJogadores();
    }
}

function dreamRemoverJogador(index){
    dreamTime[index] = null;
    dreamRenderCampo();
    dreamFiltrarJogadores();
}

function dreamMudarFormacao(){
    dreamTime = Array(11).fill(null);
    dreamRenderCampo();
    dreamFiltrarJogadores();
}

function dreamLimparTime(){
    dreamTime = Array(11).fill(null);
    dreamRenderCampo();
    dreamFiltrarJogadores();
}

dreamRenderCampo();
dreamFiltrarJogadores();
</script>

<?php include 'includes/footer.php'; ?>