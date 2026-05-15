<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $perfil = $_POST['perfil'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($nome) || empty($email) || empty($senha)) {
        $redirecionar = ($perfil === 'ALUNO') ? 'registro_aluno.php' : 'registro_empresa.php';
        header("Location: $redirecionar?erro=Preencha todos os campos obrigatórios");
        exit;
    }

    if (!isset($_POST['termos'])) {
        $redirecionar = ($perfil === 'ALUNO') ? 'registro_aluno.php' : 'registro_empresa.php';
        header("Location: $redirecionar?erro=Você deve aceitar os termos de uso para continuar");
        exit;
    }

    try {
        // Inicia transação para garantir que ambos os inserts funcionem
        $pdo->beginTransaction();

        // Verifica se o e-mail já existe
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            $pdo->rollBack();
            $redirecionar = ($perfil === 'ALUNO') ? 'registro_aluno.php' : 'registro_empresa.php';
            header("Location: $redirecionar?erro=Este e-mail já está cadastrado");
            exit;
        }

        // Insere na tabela usuarios
        $stmtUser = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)");
        $stmtUser->execute([$nome, $email, $senha, $perfil]);
        $usuario_id = $pdo->lastInsertId();

        if ($perfil === 'ALUNO') {
            $ra = trim($_POST['ra']);
            $curso = $_POST['curso'];
            $semestre = $_POST['semestre'];
            $telefone = trim($_POST['telefone']);

            $stmtAluno = $pdo->prepare("INSERT INTO alunos (usuario_id, ra, curso, semestre, telefone) VALUES (?, ?, ?, ?, ?)");
            $stmtAluno->execute([$usuario_id, $ra, $curso, $semestre, $telefone]);
        } else if ($perfil === 'EMPRESA') {
            $cnpj = trim($_POST['cnpj']);
            $telefone = trim($_POST['telefone']);
            $endereco = trim($_POST['endereco']);

            $stmtEmpresa = $pdo->prepare("INSERT INTO empresas (usuario_id, cnpj, telefone, endereco) VALUES (?, ?, ?, ?)");
            $stmtEmpresa->execute([$usuario_id, $cnpj, $telefone, $endereco]);
        }

        $pdo->commit();
        header("Location: login.php?sucesso=Cadastro realizado com sucesso! Faça login para continuar.");
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao cadastrar: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit;
}
?>
