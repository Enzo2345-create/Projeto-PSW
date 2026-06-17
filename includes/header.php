<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFM - Memória do Futebol Mundial</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <div class="logo">MFM</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="selecoes.php">Seleções</a>
        <a href="dreamteam.php">Dream Team</a>
        <a href="votacao.php">Votação</a>
    </nav>
    <div class="header-perfil">
        <?php if (isset($_SESSION['usuario'])): ?>
            <button class="avatar-btn" id="openSidebarBtn">
                <div class="avatar">
                    <?php if (!empty($_SESSION['foto_perfil']) && file_exists($_SESSION['foto_perfil'])): ?>
                        <img src="<?= $_SESSION['foto_perfil'] ?>" alt="Avatar">
                    <?php else: ?>
                        <?= strtoupper(substr($_SESSION['usuario'], 0, 2)) ?>
                    <?php endif; ?>
                </div>
            </button>
        <?php else: ?>
            <a href="login.php" class="btn-login">Entrar</a>
        <?php endif; ?>
    </div>
</header>

<!-- Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<!-- ══════════════════════════════════════
     SIDEBAR DE PERFIL
══════════════════════════════════════ -->
<div id="profileSidebar" class="sidebar">

    <div class="sidebar-header">
        <h3>Meu Perfil</h3>
        <button id="closeSidebarBtn" class="close-sidebar">&times;</button>
    </div>

    <div class="sidebar-content">

        <!-- Avatar + nome -->
        <div class="sidebar-user-info">
            <div class="sidebar-avatar-img">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <?php if (!empty($_SESSION['foto_perfil']) && file_exists($_SESSION['foto_perfil'])): ?>
                        <img src="<?= $_SESSION['foto_perfil'] ?>" alt="Avatar">
                    <?php else: ?>
                        <div class="sidebar-iniciais"><?= strtoupper(substr($_SESSION['usuario'], 0, 2)) ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="sidebar-user-text">
                <div class="sidebar-user-name"><?= htmlspecialchars($_SESSION['usuario'] ?? 'Usuário') ?></div>
            </div>
        </div>

        <!-- Botão Configurações -->
        <div class="btn-config-wrap">
            <button class="btn-configuracoes" id="btnConfig">
                <span>⚙ Configurações</span>
                <span class="cfg-icon">›</span>
            </button>
        </div>

        <!-- Painel de configurações -->
        <div class="config-panel" id="configPanel">

            <!-- ETAPA 1: verificar senha atual -->
            <div class="cfg-step ativa" id="stepSenha">
                <div style="padding:20px 18px 4px;">
                    <div class="cfg-step-titulo">Verificação</div>
                    <p style="font-size:13px;color:#666;margin-bottom:14px;line-height:1.5;">
                        Digite sua senha atual para liberar as configurações.
                    </p>
                    <input type="password" class="cfg-input" id="senhaVerificacao" placeholder="Senha atual" autocomplete="current-password">
                    <div class="cfg-erro" id="erroSenha">Senha incorreta. Tente novamente.</div>
                </div>
                <div style="padding:0 18px 18px;">
                    <button class="cfg-btn-primary" id="btnVerificarSenha">Verificar →</button>
                </div>
            </div>

            <!-- ETAPA 2: abas de edição (só aparece após verificação) -->
            <div class="cfg-step" id="stepEdicao">

                <!-- Abas -->
                <div class="cfg-abas" style="padding:0;">
                    <button class="cfg-aba ativa" data-aba="foto">Foto</button>
                    <button class="cfg-aba" data-aba="usuario">Usuário</button>
                    <button class="cfg-aba" data-aba="senha">Senha</button>
                </div>

                <!-- Aba: Foto -->
                <div class="cfg-aba-conteudo ativa" id="aba-foto" style="padding:18px;">
                    <form enctype="multipart/form-data" action="controllers/proc_upload_foto.php" method="post">
                        <label for="foto_sidebar" class="cfg-btn-upload">
                            📷 Escolher nova foto
                        </label>
                        <input type="file" name="foto_perfil" id="foto_sidebar"
                               accept="image/jpeg,image/png,image/jpg" style="display:none">
                        <button type="submit" class="cfg-btn-primary" style="margin-top:10px;">
                            Salvar foto
                        </button>
                    </form>
                </div>

                <!-- Aba: Usuário -->
                <div class="cfg-aba-conteudo" id="aba-usuario" style="display:none;padding:18px;">
                    <form action="controllers/proc_alterar_nome.php" method="post"
                          style="display:flex;flex-direction:column;gap:10px;">
                        <input type="text" name="novo_usuario" class="cfg-input"
                               placeholder="Novo nome de usuário" required>
                        <button type="submit" class="cfg-btn-primary">Salvar usuário</button>
                    </form>
                </div>

                <!-- Aba: Senha -->
                <div class="cfg-aba-conteudo" id="aba-senha" style="display:none;padding:18px;">
                    <form action="controllers/proc_alterar_senha.php" method="post"
                          style="display:flex;flex-direction:column;gap:10px;">
                        <!-- Passa a senha já verificada para o controller -->
                        <input type="hidden" name="senha_atual" id="senhaAtualHidden">
                        <input type="password" name="nova_senha" class="cfg-input"
                               placeholder="Nova senha" required>
                        <button type="submit" class="cfg-btn-primary">Salvar senha</button>
                    </form>
                </div>

            </div><!-- /stepEdicao -->

        </div><!-- /configPanel -->

        <!-- Sair -->
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">Sair da conta</a>
        </div>

    </div><!-- /sidebar-content -->
</div>

<script>
(function () {
    /* ── Abrir / fechar sidebar ── */
    const openBtn  = document.getElementById('openSidebarBtn');
    const sidebar  = document.getElementById('profileSidebar');
    const closeBtn = document.getElementById('closeSidebarBtn');
    const overlay  = document.getElementById('sidebarOverlay');

    function abrirSidebar()  { sidebar.classList.add('active');    overlay.classList.add('active'); }
    function fecharSidebar() { sidebar.classList.remove('active'); overlay.classList.remove('active'); }

    if (openBtn)  openBtn.onclick  = abrirSidebar;
    if (closeBtn) closeBtn.onclick = fecharSidebar;
    if (overlay)  overlay.onclick  = fecharSidebar;

    /* ── Botão Configurações (toggle) ── */
    const btnConfig   = document.getElementById('btnConfig');
    const configPanel = document.getElementById('configPanel');

    btnConfig.addEventListener('click', () => {
        const aberto = configPanel.classList.toggle('aberto');
        btnConfig.classList.toggle('aberto', aberto);
        // Resetar para etapa 1 ao fechar e reabrir
        if (!aberto) resetConfig();
    });

    /* ── Verificação de senha via fetch ── */
    const btnVerificar  = document.getElementById('btnVerificarSenha');
    const inputSenha    = document.getElementById('senhaVerificacao');
    const erroSenha     = document.getElementById('erroSenha');
    const stepSenha     = document.getElementById('stepSenha');
    const stepEdicao    = document.getElementById('stepEdicao');
    const senhaHidden   = document.getElementById('senhaAtualHidden');

    btnVerificar.addEventListener('click', async () => {
        const senha = inputSenha.value.trim();
        if (!senha) { mostrarErro('Digite sua senha.'); return; }

        btnVerificar.textContent = 'Verificando...';
        btnVerificar.disabled = true;

        try {
            const fd = new FormData();
            fd.append('senha', senha);
            const res  = await fetch('controllers/proc_dados.php', { method: 'POST', body: fd });
            const json = await res.json();

            if (json.ok) {
                erroSenha.style.display = 'none';
                senhaHidden.value = senha; // passa para o form de alterar senha
                stepSenha.classList.remove('ativa');
                stepEdicao.classList.add('ativa');
            } else {
                mostrarErro('Senha incorreta. Tente novamente.');
            }
        } catch (e) {
            mostrarErro('Erro de conexão. Tente novamente.');
        }

        btnVerificar.textContent = 'Verificar →';
        btnVerificar.disabled = false;
    });

    /* Enter no campo de senha */
    inputSenha.addEventListener('keydown', e => {
        if (e.key === 'Enter') btnVerificar.click();
    });

    function mostrarErro(msg) {
        erroSenha.textContent = msg;
        erroSenha.style.display = 'block';
    }

    /* ── Abas de edição ── */
    document.querySelectorAll('.cfg-aba').forEach(aba => {
        aba.addEventListener('click', () => {
            const alvo = aba.dataset.aba;

            document.querySelectorAll('.cfg-aba').forEach(a => a.classList.remove('ativa'));
            document.querySelectorAll('.cfg-aba-conteudo').forEach(c => c.style.display = 'none');

            aba.classList.add('ativa');
            document.getElementById('aba-' + alvo).style.display = 'block';
        });
    });

    /* ── Auto-submit ao escolher foto ── */
    const fotoInput = document.getElementById('foto_sidebar');
    if (fotoInput) {
        fotoInput.addEventListener('change', function () {
            this.closest('form').submit();
        });
    }

    /* ── Reset ao fechar configurações ── */
    function resetConfig() {
        inputSenha.value = '';
        erroSenha.style.display = 'none';
        stepSenha.classList.add('ativa');
        stepEdicao.classList.remove('ativa');
        // Volta para aba foto
        document.querySelectorAll('.cfg-aba').forEach((a,i) => a.classList.toggle('ativa', i===0));
        document.querySelectorAll('.cfg-aba-conteudo').forEach((c,i) => c.style.display = i===0 ? 'block' : 'none');
    }
})();
</script>
