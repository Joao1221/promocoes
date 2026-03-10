CREATE DATABASE IF NOT EXISTS capela_market CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE capela_market;

CREATE TABLE IF NOT EXISTS usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(25) NULL,
    endereco_entrega TEXT NULL,
    role ENUM('admin', 'lojista', 'consumidor') NOT NULL DEFAULT 'consumidor',
    status ENUM('ativo', 'inativo', 'bloqueado') NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuarios_role_status (role, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS lojas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    nome_loja VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    descricao TEXT NULL,
    documento_tipo ENUM('CPF', 'CNPJ') NOT NULL,
    documento_numero VARCHAR(20) NOT NULL,
    telefone VARCHAR(25) NOT NULL,
    whatsapp VARCHAR(25) NOT NULL,
    vende_online TINYINT(1) NOT NULL DEFAULT 1,
    forma_pagamento VARCHAR(120) NOT NULL DEFAULT 'PIX',
    tem_delivery TINYINT(1) NOT NULL DEFAULT 0,
    instagram VARCHAR(80) NULL,
    logo VARCHAR(255) NULL,
    banner VARCHAR(255) NULL,
    banner_mobile VARCHAR(255) NULL,
    cidade VARCHAR(120) NOT NULL DEFAULT 'Capela',
    bairro VARCHAR(120) NOT NULL,
    endereco VARCHAR(180) NOT NULL,
    horario_funcionamento VARCHAR(180) NULL,
    status ENUM('pendente', 'aprovada', 'rejeitada', 'suspensa') NOT NULL DEFAULT 'aprovada',
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_lojas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_lojas_status_destaque (status, destaque),
    INDEX idx_lojas_bairro (bairro),
    INDEX idx_lojas_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categorias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    icone VARCHAR(80) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categorias_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS produtos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    loja_id BIGINT UNSIGNED NOT NULL,
    categoria_id BIGINT UNSIGNED NOT NULL,
    nome VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    descricao TEXT NOT NULL,
    preco_original DECIMAL(10,2) NOT NULL,
    preco_promocional DECIMAL(10,2) NULL,
    estoque INT NOT NULL DEFAULT 0,
    sku VARCHAR(80) NOT NULL,
    imagem_principal VARCHAR(255) NULL,
    status ENUM('pendente', 'aprovado', 'rejeitado') NOT NULL DEFAULT 'aprovado',
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    views INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_produtos_loja FOREIGN KEY (loja_id) REFERENCES lojas(id) ON DELETE CASCADE,
    CONSTRAINT fk_produtos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT,
    INDEX idx_produtos_status_destaque (status, destaque),
    INDEX idx_produtos_loja (loja_id),
    INDEX idx_produtos_categoria (categoria_id),
    INDEX idx_produtos_slug (slug),
    FULLTEXT INDEX ftx_produtos_nome_descricao (nome, descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS imagens_produtos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    produto_id BIGINT UNSIGNED NOT NULL,
    arquivo VARCHAR(255) NOT NULL,
    ordem INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_imagens_produtos_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    INDEX idx_imagens_produto_ordem (produto_id, ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pedidos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    loja_id BIGINT UNSIGNED NOT NULL,
    nome_cliente VARCHAR(150) NOT NULL,
    telefone_cliente VARCHAR(25) NOT NULL,
    endereco_entrega TEXT NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    observacoes TEXT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('novo', 'em_preparo', 'enviado', 'concluido', 'cancelado') NOT NULL DEFAULT 'novo',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_pedidos_loja FOREIGN KEY (loja_id) REFERENCES lojas(id) ON DELETE CASCADE,
    INDEX idx_pedidos_loja_status (loja_id, status),
    INDEX idx_pedidos_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pedido_itens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    produto_id BIGINT UNSIGNED NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedido_itens_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    CONSTRAINT fk_pedido_itens_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT,
    INDEX idx_pedido_itens_pedido (pedido_id),
    INDEX idx_pedido_itens_produto (produto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS favoritos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    produto_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_favoritos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_favoritos_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    UNIQUE KEY uq_favoritos_usuario_produto (usuario_id, produto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categorias (nome, slug, icone) VALUES
('Supermercado', 'supermercado', 'Carrinho'),
('Farmacia', 'farmacia', 'Saude'),
('Restaurantes', 'restaurantes', 'Prato'),
('Moda', 'moda', 'Camisa'),
('Eletronicos', 'eletronicos', 'Plug'),
('Construcao', 'construcao', 'Ferramenta'),
('Servicos', 'servicos', 'Agenda')
ON DUPLICATE KEY UPDATE nome = VALUES(nome), icone = VALUES(icone);

INSERT INTO usuarios (nome, email, senha, role, status)
VALUES ('Administrador', 'admin@capelamarket.com', '$2y$10$UGLgqQ1Lk9r8RwcvCPHvieNkx.o0THnwnX.x5C9m2kRYCgl/PSJLa', 'admin', 'ativo')
ON DUPLICATE KEY UPDATE nome = VALUES(nome);

ALTER TABLE lojas
    ADD COLUMN IF NOT EXISTS vende_online TINYINT(1) NOT NULL DEFAULT 1 AFTER whatsapp,
    ADD COLUMN IF NOT EXISTS forma_pagamento VARCHAR(120) NOT NULL DEFAULT 'PIX' AFTER vende_online,
    ADD COLUMN IF NOT EXISTS tem_delivery TINYINT(1) NOT NULL DEFAULT 0 AFTER forma_pagamento,
    ADD COLUMN IF NOT EXISTS documento_tipo ENUM('CPF', 'CNPJ') NOT NULL DEFAULT 'CPF' AFTER descricao,
    ADD COLUMN IF NOT EXISTS documento_numero VARCHAR(20) NOT NULL DEFAULT '' AFTER documento_tipo;

ALTER TABLE usuarios
    ADD COLUMN IF NOT EXISTS endereco_entrega TEXT NULL AFTER telefone;
