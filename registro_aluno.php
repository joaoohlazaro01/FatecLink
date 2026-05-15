<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatec Itapira | Cadastro de Aluno</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }
        .login-banner {
            background: linear-gradient(135deg, #004d40, #00251a);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            text-align: center;
        }
        .login-form-container {
            padding: 3rem;
            background: white;
        }
        .form-label {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card login-card row flex-lg-row flex-column-reverse">
                    <div class="col-lg-4 login-banner">
                        <i class="bi bi-person-badge-fill mb-3" style="font-size: 4rem;"></i>
                        <h2 class="fw-bold mb-3">Sou Aluno</h2>
                        <p class="opacity-75">Crie sua conta para se candidatar às melhores vagas de estágio e impulsionar sua carreira.</p>
                        <a href="login.php" class="btn btn-outline-light mt-4 rounded-pill px-4">Já tenho conta</a>
                    </div>
                    
                    <div class="col-lg-8 login-form-container">
                        <h3 class="fw-bold text-dark mb-1">Criar Conta de Aluno</h3>
                        <p class="text-muted mb-4">Preencha seus dados acadêmicos e pessoais.</p>
                        
                        <?php if (isset($_GET['erro'])): ?>
                            <div class="alert alert-danger py-2"><?= htmlspecialchars($_GET['erro']) ?></div>
                        <?php endif; ?>

                        <form action="processa_registro.php" method="POST">
                            <input type="hidden" name="perfil" value="ALUNO">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Nome Completo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                        <input type="text" name="nome" class="form-control border-start-0 ps-0 bg-light" placeholder="Seu nome" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">E-mail Institucional</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light" placeholder="usuario@fatec.sp.gov.br" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">RA (Registro Acadêmico)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-text"></i></span>
                                        <input type="text" name="ra" class="form-control border-start-0 ps-0 bg-light" placeholder="13 dígitos" maxlength="13" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Telefone / WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp"></i></span>
                                        <input type="text" name="telefone" class="form-control border-start-0 ps-0 bg-light" placeholder="(19) 90000-0000" maxlength="15" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-medium">Curso</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-book"></i></span>
                                        <select name="curso" class="form-select border-start-0 ps-0 bg-light" required>
                                            <option value="" selected disabled>Selecione seu curso</option>
                                            <option value="DSM">Desenvolvimento de Software Multiplataforma</option>
                                            <option value="GE">Gestão Empresarial</option>
                                            <option value="GPI">Gestão de Produção Industrial</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-medium">Semestre</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-layers"></i></span>
                                        <input type="number" name="semestre" min="1" max="6" class="form-control border-start-0 ps-0 bg-light" placeholder="1" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-medium">Senha de Acesso</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="senha" class="form-control border-start-0 ps-0 bg-light" placeholder="••••••••" required>
                                </div>
                                <div class="form-text">Mínimo de 6 caracteres.</div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="termos" id="checkTermos" required>
                                    <label class="form-check-label small text-muted" for="checkTermos">
                                        Eu li e aceito os <a href="termos.php" target="_blank" class="text-decoration-none fw-bold">Termos de Uso e Política de Privacidade</a>.
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3" style="background-color: #004d40; border: none;">Cadastrar Agora</button>
                            
                            <p class="text-center small text-muted">
                                Problemas com o cadastro? <a href="mailto:suporte@fatec.sp.gov.br" class="text-decoration-none">Contate o suporte</a>.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('input[name="ra"]').mask('0000000000000');
            $('input[name="telefone"]').mask('(00) 00000-0000');
        });
    </script>
</body>
</html>
