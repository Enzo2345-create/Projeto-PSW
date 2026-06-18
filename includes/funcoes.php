<?php
    function limpar($valor) {
        return htmlspecialchars(trim($valor));
    }

    function lerJson($caminho) {
        if (!file_exists($caminho)) {
            return [];
        }
        $conteudo = file_get_contents($caminho);
        return json_decode($conteudo, true) ?? [];
    }

    function escreverJson($caminho, $dados) {
        file_put_contents(
            $caminho,
            json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    function verificarLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario'])) {
            header('Location: login.php');
            exit;
        }
        return $_SESSION['usuario'];
    }

    function buscarUsuario($nome) {
        $usuarios = lerJson(__DIR__ . '/../data/users.json');
        foreach ($usuarios as $u) {
            if ($u['usuario'] === $nome) {
                return $u;
            }
        }
        return null;
    }

    function atualizarUsuario($nome, $dadosNovos) {
        $usuarios = lerJson(__DIR__ . '/../data/users.json');
        $encontrou = false;
        foreach ($usuarios as &$u) {
            if ($u['usuario'] === $nome) {
                foreach ($dadosNovos as $chave => $valor) {
                    $u[$chave] = $valor;
                }
                $encontrou = true;
                break;
            }
        }
        if ($encontrou) {
            escreverJson(__DIR__ . '/../data/users.json', $usuarios);
            return true;
        }
        return false;
    }
?>