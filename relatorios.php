<?php
include 'conexao.php';
include 'verifica_sessao.php';
verificarPerfil('ADMIN');

// 1. Estatísticas Gerais
$total_alunos = $pdo->query("SELECT COUNT(*) FROM alunos")->fetchColumn();
$total_empresas = $pdo->query("SELECT COUNT(*) FROM empresas")->fetchColumn();
$total_vagas = $pdo->query("SELECT COUNT(*) FROM vagas")->fetchColumn();
$total_candidaturas = $pdo->query("SELECT COUNT(*) FROM candidaturas")->fetchColumn();

// 2. Distribuição de Candidaturas por Status
$stmt_status = $pdo->query("SELECT status, COUNT(*) as total FROM candidaturas GROUP BY status");
$candidaturas_status = $stmt_status->fetchAll(PDO::FETCH_ASSOC);

// 3. Lista de Vagas com Total de Candidatos
$sql_vagas = "SELECT v.id, v.titulo, u.nome as empresa, v.dataCadastro, v.status,
              (SELECT COUNT(*) FROM candidaturas c WHERE c.vaga_id = v.id) as total_candidatos
              FROM vagas v
              JOIN empresas e ON v.empresa_id = e.id
              JOIN usuarios u ON e.usuario_id = u.id
              ORDER BY v.dataCadastro DESC";
$vagas_list = $pdo->query($sql_vagas)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatec Itapira | Relatórios</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar navbar-admin">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="painel_admin.php">
                <img src="logo.png" alt="Logo" class="navbar-logo me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="painel_admin.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="relatorios.php"><i class="bi bi-graph-up me-1"></i> Relatórios</a>
                    </li>
                </ul>
                <div class="dropdown user-profile bg-dark">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['usuario_nome']); ?>&background=ffffff&color=000000" alt="Avatar" class="rounded-circle me-2" width="35">
                        <span class="fs-6 fw-medium me-1"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item py-2" href="perfil.php"><i class="bi bi-person-circle me-2"></i> Meu Perfil</a></li>
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
                <h2 class="fw-bold text-dark mb-0">Relatórios do Sistema</h2>
            </div>

         
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card stat-card border-0 shadow-sm rounded-4 bg-primary text-white">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-people fs-1"></i>
                            <h3 class="fw-bold mt-2 mb-0"><?php echo $total_alunos; ?></h3>
                            <p class="mb-0 opacity-75">Alunos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-0 shadow-sm rounded-4 bg-success text-white">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-building fs-1"></i>
                            <h3 class="fw-bold mt-2 mb-0"><?php echo $total_empresas; ?></h3>
                            <p class="mb-0 opacity-75">Empresas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-0 shadow-sm rounded-4 bg-warning text-dark">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-briefcase fs-1"></i>
                            <h3 class="fw-bold mt-2 mb-0"><?php echo $total_vagas; ?></h3>
                            <p class="mb-0 opacity-75">Vagas Totais</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-0 shadow-sm rounded-4 bg-info text-white">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-journal-check fs-1"></i>
                            <h3 class="fw-bold mt-2 mb-0"><?php echo $total_candidaturas; ?></h3>
                            <p class="mb-0 opacity-75">Candidaturas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Status das Candidaturas</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php foreach($candidaturas_status as $status): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>
                                        <i class="bi bi-circle-fill me-2 <?php 
                                            echo match($status['status']) {
                                                'Pendente' => 'text-warning',
                                                'Em Análise' => 'text-info',
                                                'Aprovado' => 'text-success',
                                                'Reprovado' => 'text-danger',
                                                default => 'text-secondary'
                                            };
                                        ?>"></i>
                                        <?php echo $status['status']; ?>
                                    </span>
                                    <span class="badge bg-secondary rounded-pill"><?php echo $status['total']; ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Monitoramento de Vagas</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Título da Vaga</th>
                                            <th>Empresa</th>
                                            <th>Cadastrada em</th>
                                            <th>Candidatos</th>
                                            <th class="pe-4 text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($vagas_list as $vaga): ?>
                                        <tr>
                                            <td class="ps-4 fw-medium"><?php echo htmlspecialchars($vaga['titulo']); ?></td>
                                            <td><?php echo htmlspecialchars($vaga['empresa']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($vaga['dataCadastro'])); ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border"><?php echo $vaga['total_candidatos']; ?></span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <span class="badge <?php echo $vaga['status'] == 'Ativa' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?> rounded-pill px-3">
                                                    <?php echo $vaga['status']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if(empty($vagas_list)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Nenhuma vaga cadastrada no sistema.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
