<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$uploadDir = '../uploads/';
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];
    if (!in_array($ext, $allowed)) {
        header('Location: ../painel.php?erro=formato');
        exit;
    }

    $nomeArquivo = $usuario . '_' . time() . '.' . $ext;
    $caminho = 'uploads/' . $nomeArquivo;
    $caminhoAbsoluto = '../' . $caminho;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminhoAbsoluto)) {
        $arquivoUsers = '../data/users.json';
        $users = json_decode(file_get_contents($arquivoUsers), true);
        foreach ($users as &$u) {
            if ($u['usuario'] === $usuario) {
                if (!empty($u['foto_perfil']) && file_exists('../' . $u['foto_perfil'])) {
                    unlink('../' . $u['foto_perfil']);
                }
                $u['foto_perfil'] = $caminho;
                break;
            }
        }
        file_put_contents($arquivoUsers, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $_SESSION['foto_perfil'] = $caminho;
        header('Location: ../painel.php?ok=foto_alterada');
    } else {
        header('Location: ../painel.php?erro=upload');
    }
} else {
    header('Location: ../painel.php');
}
exit;