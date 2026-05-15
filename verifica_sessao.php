<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_perfil'])) {
    header("Location: login.php?erro=Necessário fazer login");
    exit;
}

// Função utilitária para verificar se o usuário logado tem determinado perfil
function verificarPerfil($perfilExigido) {
    if ($_SESSION['usuario_perfil'] !== $perfilExigido) {
        // Se tentar acessar página que não tem permissão, volta pra página principal correspondente
        if ($_SESSION['usuario_perfil'] === 'ALUNO') header("Location: index.php");
        else if ($_SESSION['usuario_perfil'] === 'EMPRESA') header("Location: cadastro.php");
        else if ($_SESSION['usuario_perfil'] === 'ADMIN') header("Location: painel_admin.php");
        exit;
    }
}
?>
