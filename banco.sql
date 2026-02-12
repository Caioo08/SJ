-- ============================================
-- BANCO DE DADOS REORGANIZADO - SISTEMA JURÍDICO
-- Com controle de acesso, admin e auditoria
-- ============================================

DROP DATABASE IF EXISTS juridico;
CREATE DATABASE juridico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE juridico;

-- ============================================
-- TABELA: Perfis de Acesso
-- ============================================
CREATE TABLE perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    descricao TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nome (nome)
) ENGINE=InnoDB;

INSERT INTO perfis (nome, descricao) VALUES
('administrador', 'Acesso total ao sistema, gerencia usuários'),
('advogado', 'Acesso completo aos próprios dados e funcionalidades');

-- ============================================
-- TABELA: UFs (Estados)
-- ============================================
CREATE TABLE ufs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sigla CHAR(2) NOT NULL UNIQUE,
    nome VARCHAR(50) NOT NULL,
    INDEX idx_sigla (sigla)
) ENGINE=InnoDB;

INSERT INTO ufs (sigla, nome) VALUES
('AC','Acre'), ('AL','Alagoas'), ('AP','Amapá'), ('AM','Amazonas'),
('BA','Bahia'), ('CE','Ceará'), ('DF','Distrito Federal'),
('ES','Espírito Santo'), ('GO','Goiás'), ('MA','Maranhão'),
('MT','Mato Grosso'), ('MS','Mato Grosso do Sul'),
('MG','Minas Gerais'), ('PA','Pará'), ('PB','Paraíba'),
('PR','Paraná'), ('PE','Pernambuco'), ('PI','Piauí'),
('RJ','Rio de Janeiro'), ('RN','Rio Grande do Norte'),
('RS','Rio Grande do Sul'), ('RO','Rondônia'), ('RR','Roraima'),
('SC','Santa Catarina'), ('SP','São Paulo'),
('SE','Sergipe'), ('TO','Tocantins');

-- ============================================
-- TABELA: Usuários (Advogados e Admin)
-- ============================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    oab VARCHAR(20),
    uf_id INT,

    -- Endereço do Escritório
    escritorio_cep VARCHAR(10),
    escritorio_endereco VARCHAR(200),
    escritorio_numero VARCHAR(10),
    escritorio_complemento VARCHAR(100),
    escritorio_bairro VARCHAR(100),
    escritorio_cidade VARCHAR(100),
    escritorio_uf CHAR(2),

    perfil_id INT NOT NULL DEFAULT 2,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_usuarios_uf FOREIGN KEY (uf_id) REFERENCES ufs(id),
    CONSTRAINT fk_usuarios_perfil FOREIGN KEY (perfil_id) REFERENCES perfis(id),

    INDEX idx_email (email),
    INDEX idx_perfil (perfil_id),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB;

-- ============================================
-- TABELA: Clientes
-- ============================================
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    cpf_cnpj VARCHAR(20),
    rg VARCHAR(20),
    nacionalidade VARCHAR(50) DEFAULT 'Brasileiro(a)',
    estado_civil ENUM('solteiro','casado','divorciado','viuvo','uniao_estavel'),
    email VARCHAR(150),
    senha_hash VARCHAR(255),
    telefone VARCHAR(20),
    celular VARCHAR(20),
    cep VARCHAR(10),
    endereco VARCHAR(200),
    numero VARCHAR(10),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    uf CHAR(2),
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_clientes_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,

    INDEX idx_usuario (usuario_id),
    INDEX idx_nome (nome),
    INDEX idx_cpf_cnpj (cpf_cnpj),
    INDEX idx_clientes_email (email)
) ENGINE=InnoDB;

-- ============================================
-- TABELA: Processos
-- ============================================
CREATE TABLE processos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    cliente_id INT,
    cliente_nome VARCHAR(150) NOT NULL,
    numero_processo VARCHAR(50),
    status ENUM('aberto','concluido','arquivado') DEFAULT 'aberto',
    descricao TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_processos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,

    CONSTRAINT fk_processos_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,

    INDEX idx_usuario (usuario_id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_status (status),
    INDEX idx_numero (numero_processo)
) ENGINE=InnoDB;



-- ============================================
-- TABELA: Eventos de Processo (Timeline)
-- ============================================
CREATE TABLE processo_eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    processo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    titulo VARCHAR(180) NOT NULL,
    descricao TEXT,
    tipo ENUM('criacao','atualizacao','status','comentario','sistema') DEFAULT 'sistema',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_eventos_processo
        FOREIGN KEY (processo_id) REFERENCES processos(id) ON DELETE CASCADE,

    CONSTRAINT fk_eventos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,

    INDEX idx_eventos_processo (processo_id),
    INDEX idx_eventos_criado_em (criado_em)
) ENGINE=InnoDB;

-- ============================================
-- TABELA: Compromissos
-- ============================================
CREATE TABLE compromissos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME,
    local VARCHAR(200),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_compromissos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,

    INDEX idx_usuario (usuario_id),
    INDEX idx_data_inicio (data_inicio)
) ENGINE=InnoDB;

-- ============================================
-- TABELA: Documentos
-- ============================================
CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome_original VARCHAR(255) NOT NULL,
    nome_arquivo VARCHAR(255) NOT NULL,
    tipo VARCHAR(100),
    tamanho INT,
    categoria ENUM('processo','cliente','contrato','outros') DEFAULT 'outros',
    cliente_id INT,
    visivel_cliente BOOLEAN DEFAULT FALSE,
    descricao TEXT,
    caminho VARCHAR(500) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_documentos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,

    CONSTRAINT fk_documentos_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,

    INDEX idx_usuario (usuario_id),
    INDEX idx_categoria (categoria),
    INDEX idx_documentos_cliente (cliente_id),
    INDEX idx_documentos_visivel_cliente (visivel_cliente)
) ENGINE=InnoDB;



-- ============================================
-- TABELA: Prazos Processuais
-- ============================================
CREATE TABLE prazos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    processo_id INT,
    titulo VARCHAR(180) NOT NULL,
    descricao TEXT,
    data_limite DATETIME NOT NULL,
    prioridade ENUM('baixa','media','alta') DEFAULT 'media',
    concluido BOOLEAN DEFAULT FALSE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_prazos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,

    CONSTRAINT fk_prazos_processo
        FOREIGN KEY (processo_id) REFERENCES processos(id) ON DELETE SET NULL,

    INDEX idx_prazos_usuario (usuario_id),
    INDEX idx_prazos_data_limite (data_limite),
    INDEX idx_prazos_concluido (concluido)
) ENGINE=InnoDB;



-- ============================================
-- TABELA: Prazos Processuais
-- ============================================
CREATE TABLE prazos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    processo_id INT,
    titulo VARCHAR(180) NOT NULL,
    descricao TEXT,
    data_limite DATETIME NOT NULL,
    prioridade ENUM('baixa','media','alta') DEFAULT 'media',
    concluido BOOLEAN DEFAULT FALSE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_prazos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,

    CONSTRAINT fk_prazos_processo
        FOREIGN KEY (processo_id) REFERENCES processos(id) ON DELETE SET NULL,

    INDEX idx_prazos_usuario (usuario_id),
    INDEX idx_prazos_data_limite (data_limite),
    INDEX idx_prazos_concluido (concluido)
) ENGINE=InnoDB;

-- ============================================
-- TABELA: Log de Auditoria
-- ============================================
CREATE TABLE logs_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(100) NOT NULL,
    tabela VARCHAR(50),
    registro_id INT,
    detalhes TEXT,
    ip_address VARCHAR(45),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_logs_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,

    INDEX idx_usuario (usuario_id),
    INDEX idx_acao (acao),
    INDEX idx_criado_em (criado_em)
) ENGINE=InnoDB;

-- ============================================
-- VERIFICAÇÃO FINAL
-- ============================================
SHOW TABLES;
SELECT 'Banco de dados criado com sucesso!' AS status;
