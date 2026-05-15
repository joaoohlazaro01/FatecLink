<?php
include 'verifica_sessao.php';
verificarPerfil('ALUNO');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vaga_id = $_POST['vaga_id'];
    
    // Busca o ID do aluno
    $stmtAluno = $pdo->prepare("SELECT id FROM alunos WHERE usuario_id = ?");
    $stmtAluno->execute([$_SESSION['usuario_id']]);
    $aluno = $stmtAluno->fetch();
    $aluno_id = $aluno['id'];

    try {
        // Verifica se já existe candidatura
        $stmtCheck = $pdo->prepare("SELECT id FROM candidaturas WHERE aluno_id = ? AND vaga_id = ?");
        $stmtCheck->execute([$aluno_id, $vaga_id]);
        
        if ($stmtCheck->fetch()) {
            header("Location: index.php?erro=Você já se candidatou a esta vaga");
            exit;
        }

        // Insere a candidatura
        $stmt = $pdo->prepare("INSERT INTO candidaturas (aluno_id, vaga_id, dataCandidatura, status) VALUES (?, ?, NOW(), 'Pendente')");
        $stmt->execute([$aluno_id, $vaga_id]);

        header("Location: index.php?sucesso=Candidatura enviada com sucesso!");
        exit;
    } catch (PDOException $e) {
        die("Erro ao candidatar: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit;
}
?>
