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

<!-- Sidebar de perfil -->
<div id="profileSidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>Meu perfil</h3>
        <button id="closeSidebarBtn" class="close-sidebar">&times;</button>
    </div>
    <div class="sidebar-content">
        <!-- Avatar e nome do usuário -->
        <div class="sidebar-user-info">
            <div class="sidebar-avatar-img">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <?php if (!empty($_SESSION['foto_perfil']) && file_exists($_SESSION['foto_perfil'])): ?>
                        <img src="<?= $_SESSION['foto_perfil'] ?>" id="sidebarAvatarImg" alt="Avatar">
                    <?php else: ?>
                        <div class="sidebar-iniciais"><?= strtoupper(substr($_SESSION['usuario'], 0, 2)) ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="sidebar-user-name"><?= $_SESSION['usuario'] ?? 'Usuário' ?></div>
        </div>

        <!-- Botões de ação principais -->
        <div class="sidebar-buttons">
            <?php 
                $temFoto = !empty($_SESSION['foto_perfil']) && file_exists($_SESSION['foto_perfil']);
                $textoBotaoFoto = $temFoto ? 'Alterar foto' : 'Adicionar foto';
            ?>
            <button class="sidebar-action-btn" data-action="foto"><?= $textoBotaoFoto ?></button>
            <button class="sidebar-action-btn" data-action="usuario">Alterar usuário</button>
            <button class="sidebar-action-btn" data-action="senha">Alterar senha</button>
        </div>

        <!-- Painéis -->
        <div id="panel-foto" class="action-panel" style="display:none;">
            <form enctype="multipart/form-data" action="controllers/proc_upload_foto.php" method="post" class="sidebar-form">
                <label for="foto_sidebar" class="btn-upload-sidebar">Escolher nova foto</label>
                <input type="file" name="foto_perfil" id="foto_sidebar" accept="image/jpeg,image/png,image/jpg" style="display:none">
                <button type="submit" class="btn-salvar-sidebar">Salvar foto</button>
            </form>
        </div>

        <div id="panel-usuario" class="action-panel" style="display:none;">
            <form id="formAlterarNome" action="controllers/proc_alterar_nome.php" method="post" class="sidebar-form">
                <input type="text" name="novo_usuario" placeholder="Novo nome de usuário" required>
                <button type="submit">Alterar usuário</button>
            </form>
        </div>

        <div id="panel-senha" class="action-panel" style="display:none;">
            <form id="formAlterarSenha" action="controllers/proc_alterar_senha.php" method="post" class="sidebar-form">
                <input type="password" name="senha_atual" placeholder="Senha atual" required>
                <input type="password" name="nova_senha" placeholder="Nova senha" required>
                <button type="submit">Alterar senha</button>
            </form>
        </div>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">Sair</a>
        </div>
    </div>
</div>

<script>
    // Abrir/fechar sidebar
    const openBtn = document.getElementById('openSidebarBtn');
    const sidebar = document.getElementById('profileSidebar');
    const closeBtn = document.getElementById('closeSidebarBtn');
    if (openBtn) openBtn.onclick = () => sidebar.classList.add('active');
    if (closeBtn) closeBtn.onclick = () => sidebar.classList.remove('active');
    window.onclick = function(event) {
        if (event.target == sidebar) sidebar.classList.remove('active');
    }

    // Controle de toggle dos painéis
    const panels = {
        foto: document.getElementById('panel-foto'),
        usuario: document.getElementById('panel-usuario'),
        senha: document.getElementById('panel-senha')
    };
    const actionBtns = document.querySelectorAll('.sidebar-action-btn');
    let currentOpen = null;

    actionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const action = btn.getAttribute('data-action');
            // Se o painel já está aberto e é o mesmo, fecha
            if (currentOpen === action) {
                panels[action].style.display = 'none';
                currentOpen = null;
            } else {
                // Fecha todos
                Object.values(panels).forEach(p => p.style.display = 'none');
                // Abre o selecionado
                if (panels[action]) {
                    panels[action].style.display = 'block';
                    currentOpen = action;
                }
            }
        });
    });

    // Auto-submit ao escolher foto
    const fotoInput = document.getElementById('foto_sidebar');
    if (fotoInput) {
        fotoInput.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }
</script>