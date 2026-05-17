<?php
include 'verifica_sessao.php';
verificarPerfil('EMPRESA');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatec Itapira | Cadastro de Vagas</title>
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
                        <a class="nav-link active" href="cadastro.php"><i class="bi bi-plus-circle me-1"></i> Publicar Vaga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="minhas_vagas.php"><i class="bi bi-list-task me-1"></i> Minhas Vagas</a>
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
<br>
    <main class="container mt-5 pt-5 pb-5">
        <section class="view-section">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-5">
                            <?php if (isset($_GET['sucesso'])): ?>
                                <div class="alert alert-success py-2 mb-4"><?php echo htmlspecialchars($_GET['sucesso']); ?></div>
                            <?php endif; ?>
                            <?php if (isset($_GET['erro'])): ?>
                                <div class="alert alert-danger py-2 mb-4"><?php echo htmlspecialchars($_GET['erro']); ?></div>
                            <?php endif; ?>

                            <form action="processa_vaga.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label fw-medium">Título da Vaga</label>
                                        <input type="text" name="titulo" class="form-control" placeholder="Ex: Estagiário de Desenvolvimento Web" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label fw-medium">Localização</label>
                                        <input type="text" name="local" class="form-control" placeholder="Ex: Itapira/SP ou Remoto" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label fw-medium">Bolsa Auxílio (R$)</label>
                                        <input type="number" step="0.01" name="bolsa" class="form-control" placeholder="Ex: 1200.00" required>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label fw-medium">Descrição das Atividades</label>
                                        <textarea name="descricao" class="form-control" rows="3" placeholder="Descreva o que o estagiário irá fazer..." required></textarea>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label fw-medium">Requisitos</label>
                                        <textarea name="requisitos" class="form-control" rows="2" placeholder="Ex: Conhecimentos em HTML, CSS e JS..." required></textarea>
                                    </div>
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Publicar Vaga</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
