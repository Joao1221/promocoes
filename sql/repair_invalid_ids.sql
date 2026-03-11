USE capela_market;

START TRANSACTION;

DROP TABLE IF EXISTS usuarios_repaired;
CREATE TABLE usuarios_repaired (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(25) DEFAULT NULL,
    endereco_entrega TEXT DEFAULT NULL,
    role ENUM('admin', 'lojista', 'consumidor') NOT NULL DEFAULT 'consumidor',
    status ENUM('ativo', 'inativo', 'bloqueado') NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuarios_email (email),
    KEY idx_usuarios_role_status (role, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO usuarios_repaired (id, nome, email, senha, telefone, endereco_entrega, role, status, created_at, updated_at)
SELECT 1, nome, email, senha, telefone, endereco_entrega, 'admin', status, created_at, updated_at
FROM usuarios
WHERE email = 'admin@capelamarket.com'
ORDER BY created_at ASC
LIMIT 1;

INSERT INTO usuarios_repaired (id, nome, email, senha, telefone, endereco_entrega, role, status, created_at, updated_at)
VALUES
    (2, 'Loja Tem de Tudo', 'lojista-loja-tem-de-tudo@local.invalid', '$2y$10$UGLgqQ1Lk9r8RwcvCPHvieNkx.o0THnwnX.x5C9m2kRYCgl/PSJLa', '(79) 98888-8888', NULL, 'lojista', 'ativo', NOW(), NOW()),
    (3, 'Farmacia Popular', 'lojista-farmacia-popular@local.invalid', '$2y$10$UGLgqQ1Lk9r8RwcvCPHvieNkx.o0THnwnX.x5C9m2kRYCgl/PSJLa', '79 99838-4857', NULL, 'lojista', 'ativo', NOW(), NOW());

INSERT INTO usuarios_repaired (nome, email, senha, telefone, endereco_entrega, role, status, created_at, updated_at)
SELECT nome, email, senha, telefone, endereco_entrega, role, status, created_at, updated_at
FROM usuarios
WHERE email <> 'admin@capelamarket.com'
ORDER BY created_at ASC, email ASC;

DROP TABLE IF EXISTS lojas_repaired;
CREATE TABLE lojas_repaired (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    nome_loja VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL,
    descricao TEXT DEFAULT NULL,
    documento_tipo ENUM('CPF', 'CNPJ') NOT NULL,
    documento_numero VARCHAR(20) NOT NULL,
    telefone VARCHAR(25) NOT NULL,
    whatsapp VARCHAR(25) NOT NULL,
    vende_online TINYINT(1) NOT NULL DEFAULT 1,
    forma_pagamento VARCHAR(120) NOT NULL DEFAULT 'PIX',
    tem_delivery TINYINT(1) NOT NULL DEFAULT 0,
    instagram VARCHAR(80) DEFAULT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    banner VARCHAR(255) DEFAULT NULL,
    banner_mobile VARCHAR(255) DEFAULT NULL,
    cidade VARCHAR(120) NOT NULL DEFAULT 'Capela',
    bairro VARCHAR(120) NOT NULL,
    endereco VARCHAR(180) NOT NULL,
    horario_funcionamento VARCHAR(180) DEFAULT NULL,
    status ENUM('pendente', 'aprovada', 'rejeitada', 'suspensa') NOT NULL DEFAULT 'aprovada',
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_lojas_slug (slug),
    KEY idx_lojas_status_destaque (status, destaque),
    KEY idx_lojas_bairro (bairro),
    CONSTRAINT fk_lojas_repaired_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios_repaired(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO lojas_repaired
    (id, usuario_id, nome_loja, slug, descricao, documento_tipo, documento_numero, telefone, whatsapp, vende_online, forma_pagamento, tem_delivery, instagram, logo, banner, banner_mobile, cidade, bairro, endereco, horario_funcionamento, status, destaque, created_at, updated_at)
SELECT
    CASE WHEN l.id = 0 THEN 3 ELSE l.id END AS repaired_id,
    CASE
        WHEN l.id = 1 THEN 2
        WHEN l.id = 2 THEN 3
        ELSE (
            SELECT ur.id
            FROM usuarios_repaired ur
            WHERE ur.email NOT LIKE 'lojista-%@local.invalid'
              AND ur.role <> 'admin'
            ORDER BY (REPLACE(REPLACE(REPLACE(COALESCE(ur.telefone, ''), '(', ''), ')', ''), '-', '') = REPLACE(REPLACE(REPLACE(COALESCE(l.telefone, ''), '(', ''), ')', ''), '-', '')) DESC,
                     ur.created_at ASC,
                     ur.id ASC
            LIMIT 1
        )
    END AS repaired_usuario_id,
    l.nome_loja, l.slug, l.descricao, l.documento_tipo, l.documento_numero, l.telefone, l.whatsapp, l.vende_online, l.forma_pagamento, l.tem_delivery, l.instagram, l.logo, l.banner, l.banner_mobile, l.cidade, l.bairro, l.endereco, l.horario_funcionamento, l.status, l.destaque, l.created_at, l.updated_at
FROM lojas l
ORDER BY repaired_id ASC;

DROP TABLE IF EXISTS produtos_repaired;
CREATE TABLE produtos_repaired (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    loja_id BIGINT UNSIGNED NOT NULL,
    categoria_id BIGINT UNSIGNED NOT NULL,
    nome VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    preco_original DECIMAL(10,2) NOT NULL,
    preco_promocional DECIMAL(10,2) DEFAULT NULL,
    estoque INT NOT NULL DEFAULT 0,
    sku VARCHAR(80) NOT NULL,
    imagem_principal VARCHAR(255) DEFAULT NULL,
    status ENUM('pendente', 'aprovado', 'rejeitado') NOT NULL DEFAULT 'aprovado',
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    views INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_produtos_slug (slug),
    KEY idx_produtos_status_destaque (status, destaque),
    KEY idx_produtos_loja (loja_id),
    KEY idx_produtos_categoria (categoria_id),
    CONSTRAINT fk_produtos_repaired_loja FOREIGN KEY (loja_id) REFERENCES lojas_repaired(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO produtos_repaired
    (id, loja_id, categoria_id, nome, slug, descricao, preco_original, preco_promocional, estoque, sku, imagem_principal, status, destaque, views, created_at, updated_at)
SELECT
    CASE WHEN p.id = 0 THEN (SELECT COALESCE(MAX(id), 0) + 1 FROM produtos) ELSE p.id END AS repaired_id,
    CASE WHEN p.loja_id = 0 THEN 3 ELSE p.loja_id END AS repaired_loja_id,
    p.categoria_id, p.nome, p.slug, p.descricao, p.preco_original, p.preco_promocional, p.estoque, p.sku, p.imagem_principal, p.status, p.destaque, p.views, p.created_at, p.updated_at
FROM produtos p
ORDER BY repaired_id ASC;

DROP TABLE IF EXISTS pedidos_repaired;
CREATE TABLE pedidos_repaired (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    loja_id BIGINT UNSIGNED NOT NULL,
    nome_cliente VARCHAR(150) NOT NULL,
    telefone_cliente VARCHAR(25) NOT NULL,
    endereco_entrega TEXT NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    observacoes TEXT DEFAULT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('novo', 'em_preparo', 'enviado', 'concluido', 'cancelado') NOT NULL DEFAULT 'novo',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_pedidos_loja_status (loja_id, status),
    KEY idx_pedidos_usuario (usuario_id),
    CONSTRAINT fk_pedidos_repaired_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios_repaired(id) ON DELETE CASCADE,
    CONSTRAINT fk_pedidos_repaired_loja FOREIGN KEY (loja_id) REFERENCES lojas_repaired(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO pedidos_repaired
    (id, usuario_id, loja_id, nome_cliente, telefone_cliente, endereco_entrega, forma_pagamento, observacoes, total, status, created_at, updated_at)
SELECT
    (@pedido_seq := @pedido_seq + 1) AS repaired_id,
    (
        SELECT ur.id
        FROM usuarios_repaired ur
        WHERE ur.email NOT LIKE 'lojista-%@local.invalid'
          AND ur.role <> 'admin'
        ORDER BY (REPLACE(REPLACE(REPLACE(COALESCE(ur.telefone, ''), '(', ''), ')', ''), '-', '') = REPLACE(REPLACE(REPLACE(COALESCE(p.telefone_cliente, ''), '(', ''), ')', ''), '-', '')) DESC,
                 ur.created_at ASC,
                 ur.id ASC
        LIMIT 1
    ) AS repaired_usuario_id,
    p.loja_id,
    p.nome_cliente, p.telefone_cliente, p.endereco_entrega, p.forma_pagamento, p.observacoes, p.total, p.status, p.created_at, p.updated_at
FROM pedidos p
CROSS JOIN (SELECT @pedido_seq := 0) seq
ORDER BY p.created_at ASC, p.total ASC;

DROP TABLE IF EXISTS pedido_itens_repaired;
CREATE TABLE pedido_itens_repaired (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    produto_id BIGINT UNSIGNED NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_pedido_itens_pedido (pedido_id),
    KEY idx_pedido_itens_produto (produto_id),
    CONSTRAINT fk_pedido_itens_repaired_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos_repaired(id) ON DELETE CASCADE,
    CONSTRAINT fk_pedido_itens_repaired_produto FOREIGN KEY (produto_id) REFERENCES produtos_repaired(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO pedido_itens_repaired
    (id, pedido_id, produto_id, quantidade, preco_unitario, subtotal, created_at)
SELECT
    (@item_seq := @item_seq + 1) AS repaired_item_id,
    (@pedido_ref := @pedido_ref + 1) AS repaired_pedido_id,
    pi.produto_id,
    pi.quantidade,
    pi.preco_unitario,
    pi.subtotal,
    pi.created_at
FROM pedido_itens pi
CROSS JOIN (SELECT @item_seq := 0, @pedido_ref := 0) seq
ORDER BY pi.created_at ASC, pi.produto_id ASC;

RENAME TABLE usuarios TO usuarios_corrompidos_20260310,
             lojas TO lojas_corrompidas_20260310,
             produtos TO produtos_corrompidos_20260310,
             pedidos TO pedidos_corrompidos_20260310,
             pedido_itens TO pedido_itens_corrompidos_20260310,
             usuarios_repaired TO usuarios,
             lojas_repaired TO lojas,
             produtos_repaired TO produtos,
             pedidos_repaired TO pedidos,
             pedido_itens_repaired TO pedido_itens;

COMMIT;
