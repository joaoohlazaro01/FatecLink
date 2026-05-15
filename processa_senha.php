<?php
include 'verifica_sessao.php';
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    $redirect = ($_SESSION['usuario_perfil'] === 'ALUNO') ? 'perfil_aluno.php' : 'perfil.php';

    if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
        header("Location: $redirect?erro=Preencha todos os campos");
        exit;
    }

    if ($nova_senha !== $confirma_senha) {
        header("Location: $redirect?erro=As senhas não coincidem");
        exit;
    }

    try {
        // Verifica a senha atual
        $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        $usuario = $stmt->fetch();

        if ($usuario && $senha_atual === $usuario['senha']) {
            // Atualiza para a nova senha
            $update = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            if ($update->execute([$nova_senha, $usuario_id])) {
                header("Location: $redirect?sucesso=Senha alterada com sucesso");
            } else {
                header("Location: $redirect?erro=Erro ao atualizar senha");
            }
        } else {
            header("Location: $redirect?erro=Senha atual incorreta");
        }
    } catch (PDOException $e) {
        header("Location: $redirect?erro=Erro no banco de dados");
    }
} else {
    header("Location: perfil.php");
}
exit;
