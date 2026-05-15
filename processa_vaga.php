<?php
include 'verifica_sessao.php';
verificarPerfil('EMPRESA');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $local = trim($_POST['local']);
    $bolsa = $_POST['bolsa'];
    $descricao = trim($_POST['descricao']);
    $requisitos = trim($_POST['requisitos']);

    if (empty($titulo) || empty($local) || empty($bolsa) || empty($descricao)) {
        header("Location: cadastro.php?erro=Preencha todos os campos");
        exit;
    }

    try {
        // Busca o ID da empresa baseado no usuário da sessão
        $stmtEmpresa = $pdo->prepare("SELECT id FROM empresas WHERE usuario_id = ?");
        $stmtEmpresa->execute([$_SESSION['usuario_id']]);
        $empresa = $stmtEmpresa->fetch();
        
        if (!$empresa) {
            header("Location: cadastro.php?erro=Erro ao identificar empresa");
            exit;
        }

        $empresa_id = $empresa['id'];

        $stmt = $pdo->prepare("INSERT INTO vagas (empresa_id, titulo, descricao, requisitos, local, bolsa, dataCadastro) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$empresa_id, $titulo, $descricao, $requisitos, $local, $bolsa]);

        header("Location: cadastro.php?sucesso=Vaga publicada com sucesso!");
        exit;
    } catch (PDOException $e) {
        die("Erro ao publicar vaga: " . $e->getMessage());
    }
} else {
    header("Location: cadastro.php");
    exit;
}
?>
