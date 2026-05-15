<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['usuario_perfil'] === 'ALUNO') header("Location: index.php");
    else if ($_SESSION['usuario_perfil'] === 'EMPRESA') header("Location: cadastro.php");
    else if ($_SESSION['usuario_perfil'] === 'ADMIN') header("Location: painel_admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatec Itapira | Login de Acesso</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        .login-banner {
            background: linear-gradient(135deg, var(--primary-color), #8c0000);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            text-align: center;
        }
        .login-form-container {
            padding: 4rem 3rem;
            background: white;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card login-card row flex-lg-row flex-column-reverse">
                    <div class="col-lg-5 login-banner">
                        <i class="bi bi-mortarboard-fill mb-3" style="font-size: 4rem;"></i>
                        <h2 class="fw-bold mb-3">Fatec Itapira</h2>
                        <p class="opacity-75">Portal de Oportunidades e Estágios. Conectando alunos ao mercado de trabalho.</p>
                    </div>
                    
                    <div class="col-lg-7 login-form-container">
                        <h3 class="fw-bold text-dark mb-1">Acessar Conta</h3>
                        <p class="text-muted mb-4">Insira suas credenciais para entrar no sistema.</p>
                        
                        <?php if (isset($_GET['erro'])): ?>
                            <div class="alert alert-danger py-2"><?= htmlspecialchars($_GET['erro']) ?></div>
                        <?php endif; ?>

                        <?php if (isset($_GET['sucesso'])): ?>
                            <div class="alert alert-success py-2"><?= htmlspecialchars($_GET['sucesso']) ?></div>
                        <?php endif; ?>

                        <form action="processa_login.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-medium">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light" placeholder="seu@email.com" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label fw-medium">Senha</label>
                                    <a href="#" class="text-primary text-decoration-none small">Esqueceu a senha?</a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="senha" class="form-control border-start-0 ps-0 bg-light" placeholder="••••••••" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-4">Entrar</button>
                            
                            <hr class="text-muted mb-4">
                            
                            <div class="text-center">
                               <p class="small text-muted mb-3">Não possui uma conta?</p>
                                <div class="d-flex justify-content-center gap-2 mb-4">
                                    <a href="registro_aluno.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Sou Aluno</a>
                                    <a href="registro_empresa.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Sou Empresa</a>
                                </div>
                                <p class="x-small text-muted" style="font-size: 0.75rem;">
                                    Dúvidas? Entre em contato com a coordenação:<br>
                                    <a href="mailto:contato@fatecitapira.edu.br" class="text-decoration-none">contato@fatecitapira.edu.br</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
