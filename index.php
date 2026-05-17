<?php
include 'verifica_sessao.php';
verificarPerfil('ALUNO');
require_once 'conexao.php';

// Busca o ID do aluno
$stmtAluno = $pdo->prepare("SELECT id FROM alunos WHERE usuario_id = ?");
$stmtAluno->execute([$_SESSION['usuario_id']]);
$aluno = $stmtAluno->fetch();
$aluno_id = $aluno['id'];

// Busca as vagas ativas
$vagas = $pdo->query("
    SELECT v.*, u.nome as empresa_nome 
    FROM vagas v 
    JOIN empresas e ON v.empresa_id = e.id 
    JOIN usuarios u ON e.usuario_id = u.id
    WHERE v.status = 'Ativa' 
    ORDER BY v.dataCadastro DESC
")->fetchAll();

// Verifica em quais vagas o aluno já se candidatou
$stmtMinhasCand = $pdo->prepare("SELECT vaga_id FROM candidaturas WHERE aluno_id = ?");
$stmtMinhasCand->execute([$aluno_id]);
$minhas_candidaturas = $stmtMinhasCand->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatec Itapira | Vagas de Estágio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="logo.png" alt="Logo" class="navbar-logo me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="bi bi-briefcase me-1"></i> Vagas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="candidaturas.php"><i class="bi bi-card-checklist me-1"></i> Minhas Candidaturas</a>
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
<br>
    <main class="container mt-5 pt-5 pb-5">
        <section class="view-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">Vagas Disponíveis</h2>
                    <p class="text-muted">Encontre a oportunidade ideal para alavancar sua carreira.</p>
                </div>
            </div>

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success py-2 mb-4"><?php echo htmlspecialchars($_GET['sucesso']); ?></div>
            <?php endif; ?>
            
            <div class="row g-4">
                <?php if (count($vagas) > 0): ?>
                    <?php foreach ($vagas as $vaga): ?>
                        <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                            <div class="card vaga-card w-100 shadow-sm border-0 rounded-4">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($vaga['local']); ?></span>
                                        <span class="text-muted small"><?php echo date('d/m/Y', strtotime($vaga['dataCadastro'])); ?></span>
                                    </div>
                                    <h4 class="card-title fw-bold mt-2 mb-1"><?php echo htmlspecialchars($vaga['titulo']); ?></h4>
                                    <p class="text-primary fw-medium mb-3"><i class="bi bi-building"></i> <?php echo htmlspecialchars($vaga['empresa_nome']); ?></p>
                                    
                                    <div class="mb-3 flex-grow-1">
                                        <p class="card-text small text-muted mb-2"><strong>Atividades:</strong> <?php echo nl2br(htmlspecialchars($vaga['descricao'])); ?></p>
                                        <p class="card-text small text-muted"><strong>Requisitos:</strong> <?php echo htmlspecialchars($vaga['requisitos']); ?></p>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <hr class="text-muted opacity-25">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Bolsa Auxílio</span>
                                            <span class="fw-bold text-success">R$ <?php echo number_format($vaga['bolsa'], 2, ',', '.'); ?></span>
                                        </div>
                                        
                                        <?php if (in_array($vaga['id'], $minhas_candidaturas)): ?>
                                            <button class="btn btn-secondary w-100 mt-3" disabled><i class="bi bi-check-circle me-1"></i> Já Candidatado</button>
                                        <?php else: ?>
                                            <form action="processa_candidatura.php" method="POST">
                                                <input type="hidden" name="vaga_id" value="<?php echo $vaga['id']; ?>">
                                                <button type="submit" class="btn btn-primary w-100 mt-3">Candidatar-se</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-search fs-1 d-block mb-3"></i>
                        <p>Nenhuma vaga disponível no momento.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
