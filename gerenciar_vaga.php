<?php
include 'verifica_sessao.php';
verificarPerfil('EMPRESA');
require_once 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: minhas_vagas.php");
    exit;
}

$vaga_id = $_GET['id'];

// Busca o ID da empresa vinculada ao usuário logado para segurança
$stmtEmpresa = $pdo->prepare("SELECT id FROM empresas WHERE usuario_id = ?");
$stmtEmpresa->execute([$_SESSION['usuario_id']]);
$empresa = $stmtEmpresa->fetch();
$empresa_id = $empresa['id'];

// Busca detalhes da vaga e verifica se pertence à empresa
$stmtVaga = $pdo->prepare("SELECT * FROM vagas WHERE id = ? AND empresa_id = ?");
$stmtVaga->execute([$vaga_id, $empresa_id]);
$vaga = $stmtVaga->fetch();

if (!$vaga) {
    header("Location: minhas_vagas.php?erro=Vaga não encontrada");
    exit;
}

// Busca candidatos para esta vaga
$stmtCandidatos = $pdo->prepare("
    SELECT c.id as candidatura_id, c.dataCandidatura, c.status, 
           a.id as aluno_id, a.ra, a.curso, a.semestre, a.telefone,
           u.nome, u.email
    FROM candidaturas c
    JOIN alunos a ON c.aluno_id = a.id
    JOIN usuarios u ON a.usuario_id = u.id
    WHERE c.vaga_id = ?
    ORDER BY c.dataCandidatura DESC
");
$stmtCandidatos->execute([$vaga_id]);
$candidatos = $stmtCandidatos->fetchAll();

// Processa alteração de status se necessário
if (isset($_POST['alterar_status'])) {
    $cand_id = $_POST['candidatura_id'];
    $novo_status = $_POST['status'];
    
    $stmtUpdate = $pdo->prepare("UPDATE candidaturas SET status = ? WHERE id = ?");
    $stmtUpdate->execute([$novo_status, $cand_id]);
    
    header("Location: gerenciar_vaga.php?id=$vaga_id&sucesso=Status atualizado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Vaga | Fatec Estágios</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="cadastro.php">
                <img src="logo.png" alt="Logo" class="navbar-logo me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro.php"><i class="bi bi-plus-circle me-1"></i> Publicar Vaga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="minhas_vagas.php"><i class="bi bi-list-task me-1"></i> Minhas Vagas</a>
                    </li>
                </ul>
                <div class="dropdown user-profile">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['usuario_nome']); ?>&background=ffffff&color=b30000" alt="Avatar" class="rounded-circle me-2" width="35">
                        <span class="fs-6 fw-medium me-1"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item py-2" href="perfil.php"><i class="bi bi-person-circle me-2"></i> Meu Perfil</a></li>
                        <li><a class="dropdown-item py-2" href="minhas_vagas.php"><i class="bi bi-list-task me-2"></i> Minhas Vagas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-5 pt-5 pb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="minhas_vagas.php">Minhas Vagas</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($vaga['titulo']); ?></li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($vaga['titulo']); ?></h2>
                        <p class="text-muted mb-0"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($vaga['local']); ?> | <i class="bi bi-calendar3 me-1"></i> Publicada em <?php echo date('d/m/Y', strtotime($vaga['dataCadastro'])); ?></p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="badge fs-6 <?php echo $vaga['status'] === 'Ativa' ? 'bg-success' : 'bg-secondary'; ?>">
                            Status: <?php echo $vaga['status']; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success py-2 mb-4"><?php echo htmlspecialchars($_GET['sucesso']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Candidatos Inscritos (<?php echo count($candidatos); ?>)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Candidato</th>
                            <th>Curso / Semestre</th>
                            <th>Data Inscrição</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($candidatos) > 0): ?>
                            <?php foreach ($candidatos as $cand): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($cand['nome']); ?>&background=random" class="rounded-circle me-3" width="40">
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($cand['nome']); ?></div>
                                                <div class="text-muted small"><?php echo htmlspecialchars($cand['email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small"><?php echo htmlspecialchars($cand['curso']); ?></div>
                                        <div class="text-muted small"><?php echo $cand['semestre']; ?>º Semestre</div>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($cand['dataCandidatura'])); ?></td>
                                    <td>
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="candidatura_id" value="<?php echo $cand['candidatura_id']; ?>">
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                <option value="Pendente" <?php echo $cand['status'] === 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                                                <option value="Em Análise" <?php echo $cand['status'] === 'Em Análise' ? 'selected' : ''; ?>>Em Análise</option>
                                                <option value="Aprovado" <?php echo $cand['status'] === 'Aprovado' ? 'selected' : ''; ?>>Aprovado</option>
                                                <option value="Reprovado" <?php echo $cand['status'] === 'Reprovado' ? 'selected' : ''; ?>>Reprovado</option>
                                            </select>
                                            <input type="hidden" name="alterar_status" value="1">
                                        </form>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group">
                                            <?php 
                                                $whatsapp = preg_replace('/[^0-9]/', '', $cand['telefone']);
                                                $msg = "Olá " . $cand['nome'] . ", vimos sua candidatura para a vaga " . $vaga['titulo'] . " na " . $_SESSION['usuario_nome'] . " e gostaríamos de conversar.";
                                            ?>
                                            <a href="https://wa.me/55<?php echo $whatsapp; ?>?text=<?php echo urlencode($msg); ?>" target="_blank" class="btn btn-sm btn-success" title="Enviar WhatsApp">
                                                <i class="bi bi-whatsapp"></i> Mensagem
                                            </a>
                                            <a href="mailto:<?php echo $cand['email']; ?>?subject=Vaga: <?php echo urlencode($vaga['titulo']); ?>&body=<?php echo urlencode($msg); ?>" class="btn btn-sm btn-outline-primary" title="Enviar E-mail">
                                                <i class="bi bi-envelope"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Nenhum candidato inscrito para esta vaga ainda.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
