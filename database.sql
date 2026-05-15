-- =========================================
-- CRIAÇÃO DO BANCO
-- =========================================
CREATE DATABASE IF NOT EXISTS fatec_estagios;
USE fatec_estagios;

-- =========================================
-- TABELA USUÁRIOS
-- =========================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('ALUNO', 'EMPRESA', 'ADMIN') NOT NULL,
    status ENUM('ATIVO', 'INATIVO') DEFAULT 'ATIVO',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- TABELA ALUNOS
-- =========================================
CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    ra VARCHAR(20) UNIQUE NOT NULL,
    curso VARCHAR(100),
    semestre INT,
    telefone VARCHAR(20),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- =========================================
-- TABELA EMPRESAS
-- =========================================
CREATE TABLE IF NOT EXISTS empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    cnpj VARCHAR(20) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    endereco VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- =========================================
-- TABELA VAGAS
-- =========================================
CREATE TABLE IF NOT EXISTS vagas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    requisitos TEXT,
    local VARCHAR(100),
    bolsa DECIMAL(10,2),
    status ENUM('Ativa', 'Inativa') DEFAULT 'Ativa',
    dataCadastro DATE,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
);

-- =========================================
-- TABELA CANDIDATURAS
-- =========================================
CREATE TABLE IF NOT EXISTS candidaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    vaga_id INT NOT NULL,
    dataCandidatura DATE,
    status ENUM('Pendente', 'Em Análise', 'Aprovado', 'Reprovado') DEFAULT 'Pendente',
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (vaga_id) REFERENCES vagas(id) ON DELETE CASCADE
);

-- =========================================
-- USUÁRIOS
-- =========================================
INSERT INTO usuarios (id, nome, email, senha, perfil) VALUES 
(1, 'João Silva (Aluno)', 'aluno@fatec.sp.gov.br', 'senha123', 'ALUNO'),
(2, 'TechCorp RH (Empresa)', 'empresa@techcorp.com', 'senha123', 'EMPRESA'),
(3, 'Coordenador (Admin)', 'admin@fatec.sp.gov.br', 'admin123', 'ADMIN'),

(4, 'Maria Oliveira', 'maria@fatec.sp.gov.br', '123456', 'ALUNO'),
(5, 'Carlos Souza', 'carlos@fatec.sp.gov.br', '123456', 'ALUNO'),
(6, 'Ana Lima', 'ana@fatec.sp.gov.br', '123456', 'ALUNO'),
(7, 'Pedro Henrique', 'pedro@fatec.sp.gov.br', '123456', 'ALUNO'),
(8, 'Juliana Martins', 'juliana@fatec.sp.gov.br', '123456', 'ALUNO'),
(9, 'Lucas Ferreira', 'lucas@fatec.sp.gov.br', '123456', 'ALUNO'),
(10, 'Fernanda Costa', 'fernanda@fatec.sp.gov.br', '123456', 'ALUNO'),

(11, 'Inova Tech RH', 'contato@inovatech.com', '123456', 'EMPRESA'),
(12, 'DevSolutions RH', 'rh@devsolutions.com', '123456', 'EMPRESA'),
(13, 'CodeWave RH', 'vagas@codewave.com', '123456', 'EMPRESA'),
(14, 'NextGen Sistemas', 'rh@nextgen.com', '123456', 'EMPRESA'),
(15, 'SmartIT Consultoria', 'contato@smartit.com', '123456', 'EMPRESA');

-- =========================================
-- ALUNOS
-- =========================================
INSERT INTO alunos (id, usuario_id, ra, curso, semestre, telefone) VALUES 
(1, 1, '123456789', 'Desenvolvimento de Software Multiplataforma', 3, '(19) 99999-9999'),

(2, 4, '202600001', 'DSM', 1, '(19) 99999-0001'),
(3, 5, '202600002', 'DSM', 2, '(19) 99999-0002'),
(4, 6, '202600003', 'DSM', 3, '(19) 99999-0003'),
(5, 7, '202600004', 'DSM', 1, '(19) 99999-0004'),
(6, 8, '202600005', 'DSM', 4, '(19) 99999-0005'),
(7, 9, '202600006', 'DSM', 2, '(19) 99999-0006'),
(8, 10, '202600007', 'DSM', 5, '(19) 99999-0007');

-- =========================================
-- EMPRESAS
-- =========================================
INSERT INTO empresas (id, usuario_id, cnpj, telefone, endereco) VALUES 
(1, 2, '12.345.678/0001-90', '(19) 3863-0000', 'Rua Tech, 123 - Itapira/SP'),

(2, 11, '11.111.111/0001-11', '(19) 4000-1001', 'Rua A, 100 - Itapira/SP'),
(3, 12, '22.222.222/0001-22', '(19) 4000-1002', 'Rua B, 200 - Mogi Guaçu/SP'),
(4, 13, '33.333.333/0001-33', '(19) 4000-1003', 'Rua C, 300 - Campinas/SP'),
(5, 14, '44.444.444/0001-44', '(19) 4000-1004', 'Rua D, 400 - Jaguariúna/SP'),
(6, 15, '55.555.555/0001-55', '(19) 4000-1005', 'Rua E, 500 - São Paulo/SP');

-- =========================================
-- VAGAS
-- =========================================
INSERT INTO vagas (id, empresa_id, titulo, descricao, requisitos, local, bolsa, status, dataCadastro) VALUES 

(1, 1, 'Estágio em Front-end', 'Desenvolvimento Web', 'HTML, CSS, React', 'Híbrido', 1200.00, 'Ativa', '2026-05-01'),

(2, 2, 'Estágio Back-end PHP', 'Desenvolvimento de APIs e sistemas web', 'PHP, MySQL, Laravel', 'Remoto', 1500.00, 'Ativa', '2026-05-03'),

(3, 3, 'Estágio UI/UX Design', 'Criação de interfaces modernas', 'Figma, Design Responsivo', 'Híbrido', 1300.00, 'Ativa', '2026-05-04'),

(4, 4, 'Desenvolvedor Front-end Jr', 'Manutenção de aplicações React', 'HTML, CSS, JavaScript, React', 'Presencial', 1800.00, 'Ativa', '2026-05-05'),

(5, 5, 'Estágio Suporte TI', 'Atendimento e suporte técnico', 'Redes, Hardware, Windows', 'Presencial', 1100.00, 'Ativa', '2026-05-05'),

(6, 6, 'Estágio Mobile Flutter', 'Desenvolvimento mobile multiplataforma', 'Flutter, Dart', 'Remoto', 1600.00, 'Ativa', '2026-05-06');

-- =========================================
-- CANDIDATURAS
-- =========================================
INSERT INTO candidaturas (id, aluno_id, vaga_id, dataCandidatura, status) VALUES 

(1, 1, 1, '2026-05-02', 'Pendente'),

(2, 2, 2, '2026-05-06', 'Pendente'),
(3, 3, 2, '2026-05-06', 'Em Análise'),
(4, 4, 3, '2026-05-06', 'Pendente'),
(5, 5, 4, '2026-05-07', 'Aprovado'),
(6, 6, 5, '2026-05-07', 'Pendente'),
(7, 7, 6, '2026-05-07', 'Reprovado'),
(8, 8, 3, '2026-05-07', 'Em Análise');