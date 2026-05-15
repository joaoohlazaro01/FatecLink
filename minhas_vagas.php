<?php
include 'verifica_sessao.php';
verificarPerfil('EMPRESA');
require_once 'conexao.php';

// Busca o ID da empresa vinculada ao usuário logado
$stmtEmpresa = $pdo->prepare("SELECT id FROM empresas WHERE usuario_id = ?");
$stmtEmpresa->execute([$_SESSION['usuario_id']]);
$empresa = $stmtEmpresa->fetch();
$empresa_id = $empresa['id'];

// Busca as vagas postadas por esta empresa
$stmtVagas = $pdo->prepare("
    SELECT v.*, 
    (SELECT COUNT(*) FROM candidaturas WHERE vaga_id = v.id) as total_candidatos
    FROM vagas v 
    WHERE v.empresa_id = ? 
    ORDER BY v.dataCadastro DESC
");
$stmtVagas->execute([$empresa_id]);
$vagas = $stmtVagas->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vagas | Fatec Estágios</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="cadastro.php">
                <i class="bi bi-mortarboard-fill fs-3 me-2"></i>
                <span class="fw-bold">Fatec Estágios</span>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark">Minhas Vagas</h2>
                <p class="text-muted">Gerencie as oportunidades publicadas e veja os candidatos.</p>
            </div>
            <a href="cadastro.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Nova Vaga</a>
        </div>

        <div class="row g-4">
            <?php if (count($vagas) > 0): ?>
                <?php foreach ($vagas as $vaga): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge <?php echo $vaga['status'] === 'Ativa' ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $vaga['status']; ?>
                                    </span>
                                    <span class="text-muted small"><?php echo date('d/m/Y', strtotime($vaga['dataCadastro'])); ?></span>
                                </div>
                                <h4 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($vaga['titulo']); ?></h4>
                                <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($vaga['local']); ?></p>
                                
                                <div class="bg-light p-3 rounded-3 mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people-fill text-primary fs-4 me-2"></i>
                                        <div>
                                            <span class="d-block fw-bold fs-5"><?php echo $vaga['total_candidatos']; ?></span>
                                            <span class="text-muted small">Candidatos inscritos</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <a href="gerenciar_vaga.php?id=<?php echo $vaga['id']; ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Ver Candidatos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="bg-white p-5 rounded-4 shadow-sm">
                        <i class="bi bi-clipboard-x fs-1 text-muted mb-3"></i>
                        <p class="text-muted">Você ainda não publicou nenhuma vaga.</p>
                        <a href="cadastro.php" class="btn btn-primary mt-2">Publicar minha primeira vaga</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
