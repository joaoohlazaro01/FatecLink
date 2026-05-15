<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        header("Location: login.php?erro=Campos vazios");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, perfil, status, senha FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $senha === $usuario['senha']) {
            if ($usuario['status'] !== 'ATIVO') {
                header("Location: login.php?erro=Usuário inativo");
                exit;
            }

            // Inicia os dados na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_perfil'] = $usuario['perfil'];

            // Redirecionamento baseado no perfil
            if ($usuario['perfil'] === 'ALUNO') {
                header("Location: index.php");
            } else if ($usuario['perfil'] === 'EMPRESA') {
                header("Location: minhas_vagas.php");
            } else if ($usuario['perfil'] === 'ADMIN') {
                header("Location: painel_admin.php");
            }
            exit;
        } else {
            header("Location: login.php?erro=Credenciais inválidas");
            exit;
        }
    } catch (PDOException $e) {
        die("Erro no login: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit;
}
?>
