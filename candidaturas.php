<?php
include 'verifica_sessao.php';
verificarPerfil('ALUNO');
require_once 'conexao.php';

// Busca o ID do aluno baseado no usuário da sessão
$stmtAluno = $pdo->prepare("SELECT id FROM alunos WHERE usuario_id = ?");
$stmtAluno->execute([$_SESSION['usuario_id']]);
$aluno = $stmtAluno->fetch();
$aluno_id = $aluno['id'];

// Busca candidaturas
$stmtCand = $pdo->prepare("
    SELECT c.status, c.dataCandidatura, v.titulo, u.nome as empresa_nome 
    FROM candidaturas c
    JOIN vagas v ON c.vaga_id = v.id
    JOIN empresas e ON v.empresa_id = e.id
    JOIN usuarios u ON e.usuario_id = u.id
    WHERE c.aluno_id = ?
    ORDER BY c.dataCandidatura DESC
");
$stmtCand->execute([$aluno_id]);
$candidaturas = $stmtCand->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatec Itapira | Minhas Candidaturas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="bi bi-mortarboard-fill fs-3 me-2"></i>
                <span class="fw-bold">Fatec Estágios</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="bi bi-briefcase me-1"></i> Vagas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="candidaturas.php"><i class="bi bi-card-checklist me-1"></i> Minhas Candidaturas</a>
                    </li>
                </ul>
                <div class="dropdown user-profile">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['usuario_nome']); ?>&background=ffffff&color=b30000" alt="Avatar" class="rounded-circle me-2" width="35">
                        <span class="fs-6 fw-medium me-1"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item py-2" href="perfil_aluno.php"><i class="bi bi-person-circle me-2"></i> Meu Perfil</a></li>
                        <li><a class="dropdown-item py-2" href="candidaturas.php"><i class="bi bi-card-checklist me-2"></i> Minhas Candidaturas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-5 pt-5 pb-5">
        <section class="view-section">
            <h2 class="fw-bold text-dark mb-2">Acompanhamento de Candidaturas</h2>
            <p class="text-muted mb-4">Veja o status das vagas nas quais você se inscreveu.</p>
            
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4">Vaga</th>
                                <th class="py-3">Empresa</th>
                                <th class="py-3 text-center">Data</th>
                                <th class="py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($candidaturas) > 0): ?>
                                <?php foreach ($candidaturas as $cand): ?>
                                    <tr>
                                        <td class="px-4">
                                            <p class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($cand['titulo']); ?></p>
                                        </td>
                                        <td><?php echo htmlspecialchars($cand['empresa_nome']); ?></td>
                                        <td class="text-center small"><?php echo date('d/m/Y', strtotime($cand['dataCandidatura'])); ?></td>
                                        <td class="text-center">
                                            <?php 
                                                $statusClass = 'status-pendente';
                                                if ($cand['status'] === 'Aprovado') $statusClass = 'bg-success text-white';
                                                else if ($cand['status'] === 'Reprovado') $statusClass = 'bg-danger text-white';
                                                else if ($cand['status'] === 'Em Análise') $statusClass = 'bg-warning text-dark';
                                            ?>
                                            <span class="badge rounded-pill <?php echo $statusClass; ?> px-3 py-2"><?php echo $cand['status']; ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                                        Você ainda não se candidatou a nenhuma vaga.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
