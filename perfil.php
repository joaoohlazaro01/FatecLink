<?php
include 'verifica_sessao.php';
require_once 'conexao.php';

$perfil_tipo = $_SESSION['usuario_perfil'];

if ($perfil_tipo === 'ALUNO') {
    header("Location: perfil_aluno.php");
    exit;
}

// Para EMPRESA ou ADMIN, mostra dados básicos
$stmt = $pdo->prepare("SELECT nome, email, perfil FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil | Fatec Estágios</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar <?php echo ($perfil_tipo === 'ADMIN') ? 'navbar-admin' : ''; ?>">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="javascript:history.back()">
                <img src="logo.png" alt="Logo" class="navbar-logo me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:history.back()"><i class="bi bi-arrow-left me-1"></i> Voltar</a>
                    </li>
                </ul>
                <div class="dropdown user-profile">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['usuario_nome']); ?>&background=ffffff&color=b30000" alt="Avatar" class="rounded-circle me-2" width="35">
                        <span class="fs-6 fw-medium me-1"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item py-2 active" href="perfil.php"><i class="bi bi-person-circle me-2"></i> Meu Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
<br>
    <main class="container mt-5 pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <?php if (isset($_GET['erro'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo htmlspecialchars($_GET['erro']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['sucesso'])): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?php echo htmlspecialchars($_GET['sucesso']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="bg-dark p-4 text-white text-center">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['nome']); ?>&size=100&background=ffffff&color=000000" class="rounded-circle shadow-sm mb-3 border border-3 border-secondary">
                        <h3 class="fw-bold mb-0"><?php echo htmlspecialchars($user['nome']); ?></h3>
                        <p class="opacity-75 mb-0"><?php echo $user['perfil']; ?></p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-medium">Nome</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($user['nome']); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-medium">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-10">

                        <h5 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2"></i>Alterar Senha</h5>
                        <form action="processa_senha.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small fw-medium">Senha Atual</label>
                                    <input type="password" name="senha_atual" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium">Nova Senha</label>
                                    <input type="password" name="nova_senha" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium">Confirmar Nova Senha</label>
                                    <input type="password" name="confirma_senha" class="form-control" required>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-dark w-100 rounded-3 py-2">
                                        <i class="bi bi-key me-2"></i>Atualizar Senha
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
