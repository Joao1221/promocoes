-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/03/2026 às 23:20
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `capela_market`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(120) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `icone` varchar(80) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `slug`, `icone`, `created_at`, `updated_at`) VALUES
(1, 'Supermercado', 'supermercado', 'Carrinho', '2026-03-09 12:13:51', '2026-03-09 12:13:51'),
(2, 'Farmacia', 'farmacia', 'Saude', '2026-03-09 12:13:51', '2026-03-09 12:13:51'),
(3, 'Restaurantes', 'restaurantes', 'Prato', '2026-03-09 12:13:51', '2026-03-09 12:13:51'),
(4, 'Moda', 'moda', 'Camisa', '2026-03-09 12:13:51', '2026-03-09 12:13:51'),
(5, 'Eletronicos', 'eletronicos', 'Plug', '2026-03-09 12:13:51', '2026-03-09 12:13:51'),
(6, 'Construcao', 'construcao', 'Ferramenta', '2026-03-09 12:13:51', '2026-03-09 12:13:51'),
(7, 'Servicos', 'servicos', 'Agenda', '2026-03-09 12:13:51', '2026-03-09 12:13:51'),
(8, 'Açougue', 'acougue', 'Acougue', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(9, 'Frios', 'frios', 'Frios', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(10, 'Doces', 'doces', 'Doces', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(11, 'Frutas', 'frutas', 'Frutas', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(12, 'Verduras', 'verduras', 'Verduras', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(13, 'Padaria', 'padaria', 'Padaria', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(14, 'Bebidas', 'bebidas', 'Bebidas', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(15, 'Limpeza', 'limpeza', 'Limpeza', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(16, 'Higiene', 'higiene', 'Higiene', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(17, 'Pet Shop', 'pet-shop', 'PetShop', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(18, 'Papelaria', 'papelaria', 'Papelaria', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(19, 'Livraria', 'livraria', 'Livraria', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(20, 'Brinquedos', 'brinquedos', 'Brinquedos', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(21, 'Esportes', 'esportes', 'Esportes', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(22, 'Automotivo', 'automotivo', 'Automotivo', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(23, 'Ferramentas', 'ferramentas', 'Ferramentas', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(24, 'Jardim', 'jardim', 'Jardim', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(25, 'Móveis', 'moveis', 'Moveis', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(26, 'Decoração', 'decoracao', 'Decoracao', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(27, 'Informática', 'informatica', 'Informatica', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(28, 'Telefonia', 'telefonia', 'Telefonia', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(29, 'Ótica', 'otica', 'Otica', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(30, 'Relojoaria', 'relojoaria', 'Relojoaria', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(31, 'Artigos de Festa', 'artigos-de-festa', 'Festa', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(32, 'Cama, Mesa e Banho', 'cama-mesa-e-banho', 'CamaMesaBanho', '2026-03-09 12:37:20', '2026-03-09 12:58:18'),
(33, 'Posto de lavagem', 'posto-de-lavagem', 'Agenda', '2026-03-09 12:53:37', '2026-03-09 12:53:37'),
(34, 'Supermercado', 'supermercado', 'Carrinho', '2026-03-10 10:17:19', '2026-03-10 10:17:19'),
(35, 'Farmacia', 'farmacia', 'Saude', '2026-03-10 10:17:19', '2026-03-10 10:17:19'),
(36, 'Restaurantes', 'restaurantes', 'Prato', '2026-03-10 10:17:19', '2026-03-10 10:17:19'),
(37, 'Moda', 'moda', 'Camisa', '2026-03-10 10:17:19', '2026-03-10 10:17:19'),
(38, 'Eletronicos', 'eletronicos', 'Plug', '2026-03-10 10:17:19', '2026-03-10 10:17:19'),
(39, 'Construcao', 'construcao', 'Ferramenta', '2026-03-10 10:17:19', '2026-03-10 10:17:19'),
(40, 'Servicos', 'servicos', 'Agenda', '2026-03-10 10:17:19', '2026-03-10 10:17:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `produto_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens_produtos`
--

CREATE TABLE `imagens_produtos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `produto_id` bigint(20) UNSIGNED NOT NULL,
  `arquivo` varchar(255) NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lojas`
--

CREATE TABLE `lojas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `nome_loja` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `descricao` text DEFAULT NULL,
  `documento_tipo` enum('CPF','CNPJ') NOT NULL,
  `documento_numero` varchar(20) NOT NULL,
  `telefone` varchar(25) NOT NULL,
  `whatsapp` varchar(25) NOT NULL,
  `vende_online` tinyint(1) NOT NULL DEFAULT 1,
  `forma_pagamento` varchar(120) NOT NULL DEFAULT 'PIX',
  `tem_delivery` tinyint(1) NOT NULL DEFAULT 0,
  `instagram` varchar(80) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `banner_mobile` varchar(255) DEFAULT NULL,
  `cidade` varchar(120) NOT NULL DEFAULT 'Capela',
  `bairro` varchar(120) NOT NULL,
  `endereco` varchar(180) NOT NULL,
  `horario_funcionamento` varchar(180) DEFAULT NULL,
  `status` enum('pendente','aprovada','rejeitada','suspensa') NOT NULL DEFAULT 'aprovada',
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `lojas`
--

INSERT INTO `lojas` (`id`, `usuario_id`, `nome_loja`, `slug`, `descricao`, `documento_tipo`, `documento_numero`, `telefone`, `whatsapp`, `vende_online`, `forma_pagamento`, `tem_delivery`, `instagram`, `logo`, `banner`, `banner_mobile`, `cidade`, `bairro`, `endereco`, `horario_funcionamento`, `status`, `destaque`, `created_at`, `updated_at`) VALUES
(1, 2, 'Loja Tem de Tudo', 'loja-tem-de-tudo', 'Produtos reais para testes do marketplace local.', 'CNPJ', '11444777000161', '(79) 98888-8888', '79 98139-5097', 1, 'PIX', 1, '@ofertasreaiscapela', 'loja-tem-de-tudo-logo.png', 'loja-tem-de-tudo-banner-desktop.png', 'loja-tem-de-tudo-banner-mobile.png', 'Capela', 'Centro', 'Rua do Comercio, 100', '08:00-18:00', 'aprovada', 1, '2026-03-09 13:24:18', '2026-03-10 22:07:52'),
(2, 3, 'Farmácia Popular', 'farmacia-popular', 'Preço baixo de verdade', 'CNPJ', '22333444000105', '79 99838-4857', '79 98139-5097', 1, 'PIX', 0, '@farmaciapopular.capela', 'farmacia-popular-logo.png', 'farmacia-popular-banner-desktop.png', 'farmacia-popular-banner-mobile.png', 'Capela', 'Centro', 'Av. Coelho e Campos, 120', 'Seg-Sab 07:00-20:00', 'aprovada', 1, '2026-03-09 13:55:22', '2026-03-10 22:08:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `loja_id` bigint(20) UNSIGNED NOT NULL,
  `nome_cliente` varchar(150) NOT NULL,
  `telefone_cliente` varchar(25) NOT NULL,
  `endereco_entrega` text NOT NULL,
  `forma_pagamento` varchar(50) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('novo','em_preparo','enviado','concluido','cancelado') NOT NULL DEFAULT 'novo',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `loja_id`, `nome_cliente`, `telefone_cliente`, `endereco_entrega`, `forma_pagamento`, `observacoes`, `total`, `status`, `created_at`, `updated_at`) VALUES
(3, 8, 2, 'Elson Ribeiro Santos', '79 90000-0022', 'Rua da palmeira, 345', 'Dinheiro', '', 55.98, 'novo', '2026-03-10 21:25:41', '2026-03-10 21:25:41'),
(4, 8, 1, 'Elson Ribeiro Santos', '79 90000-0022', 'Rua da palmeira, 345', 'Negociar no WhatsApp', '', 123.44, 'novo', '2026-03-10 21:57:00', '2026-03-10 21:57:00'),
(5, 8, 1, 'Elson Ribeiro Santos', '79 90000-0022', 'Rua da palmeira, 345', 'Negociar no WhatsApp', '', 36.06, 'novo', '2026-03-10 22:05:58', '2026-03-10 22:05:58'),
(6, 8, 1, 'Elson Ribeiro Santos', '79 90000-0022', 'Rua da palmeira, 345', 'Negociar no WhatsApp', 'Sem salada no eggs-burguer, enviar catchup e maionese', 141.56, 'novo', '2026-03-10 22:09:30', '2026-03-10 22:09:30'),
(7, 8, 2, 'Elson Ribeiro Santos', '79 99924-8114', 'Rua da palmeira, 345', 'Negociar no WhatsApp', 'Enviei para mim 79 99924-8114', 59.22, 'novo', '2026-03-10 22:18:43', '2026-03-10 22:18:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_id` bigint(20) UNSIGNED NOT NULL,
  `produto_id` bigint(20) UNSIGNED NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pedido_itens`
--

INSERT INTO `pedido_itens` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`, `subtotal`, `created_at`) VALUES
(3, 3, 34, 1, 17.01, 17.01, '2026-03-10 21:25:41'),
(4, 3, 35, 1, 13.86, 13.86, '2026-03-10 21:25:41'),
(5, 3, 36, 1, 25.11, 25.11, '2026-03-10 21:25:41'),
(6, 4, 15, 1, 48.98, 48.98, '2026-03-10 21:57:00'),
(7, 4, 1, 2, 37.23, 74.46, '2026-03-10 21:57:00'),
(8, 5, 18, 1, 36.06, 36.06, '2026-03-10 22:05:58'),
(9, 6, 18, 1, 36.06, 36.06, '2026-03-10 22:09:30'),
(10, 6, 29, 1, 42.74, 42.74, '2026-03-10 22:09:30'),
(11, 6, 9, 1, 62.76, 62.76, '2026-03-10 22:09:30'),
(12, 7, 37, 1, 28.35, 28.35, '2026-03-10 22:18:43'),
(13, 7, 34, 1, 17.01, 17.01, '2026-03-10 22:18:43'),
(14, 7, 35, 1, 13.86, 13.86, '2026-03-10 22:18:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `loja_id` bigint(20) UNSIGNED NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(180) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `preco_original` decimal(10,2) NOT NULL,
  `preco_promocional` decimal(10,2) DEFAULT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(80) NOT NULL,
  `imagem_principal` varchar(255) DEFAULT NULL,
  `status` enum('pendente','aprovado','rejeitado') NOT NULL DEFAULT 'aprovado',
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `loja_id`, `categoria_id`, `nome`, `slug`, `descricao`, `preco_original`, `preco_promocional`, `estoque`, `sku`, `imagem_principal`, `status`, `destaque`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 'Cutelo Inox 07 Profissional Linha Premium - Corneta 7555407 Cor Cor: Cabo Branco', 'cutelo-inox-07-profissional-linha-premium-corneta-7555407-cor-cor-cabo-branco', 'Unidades por kit: 1. | Lâmina de aço inoxidável para durabilidade e resistência à corrosão. | Versátil, ideal para cortar carne, peixe, frango, coco e ossos. | Cabo de plástico branco, proporcionando um manuseio confortável. | Embalagem contém uma unidade, perfeita para uso profissional em açougues e frigoríficos.', 40.47, 37.23, 28, 'ACO-0001', 'real-acougue-01.webp', 'aprovado', 0, 2, '2026-03-09 13:24:21', '2026-03-10 21:57:00'),
(2, 1, 14, 'Whisky Johnnie Walker Red Label 1L', 'whisky-johnnie-walker-red-label-1l', 'Unidades por kit: 1. | Tipo de embalagem: Garrafa. | 40% de graduação alcoólica. | Manter temperatura ambiente. | Beba com moderação. A venda e o consumo de bebida alcóolica são proibidos para menores.', 72.80, 66.98, 46, 'BEB-0002', 'real-bebidas-02.webp', 'aprovado', 0, 0, '2026-03-09 13:24:23', '2026-03-09 13:24:23'),
(3, 1, 20, 'Caminhão Dinossauro Pista Com Carrinhos Maleta Transporte Cor Azul | Frete grátis', 'caminh-ao-dinossauro-pista-com-carrinhos-maleta-transporte-cor-azul-frete-gr-atis', 'Idade mínima recomendada:  3 anos. | Acessório incluído:  2 carrinhos.', 119.90, 110.31, 35, 'BRI-0003', 'real-brinquedos-03.webp', 'aprovado', 1, 0, '2026-03-09 13:24:26', '2026-03-09 13:24:26'),
(4, 1, 32, 'Jogo Lençol Casal 3 Peças Shopping Home 100% Algodão 400 Fios Rosa', 'jogo-lencol-casal-3-pecas-shopping-home-100-algod-ao-400-fios-rosa', 'Tamanho do colchão: Casal. | Unidades por kit: 1. | Desenho do tecido: Liso. | Lençol com elástico de 188 cm de comprimento. | Lençol plano de 2,40 m de comprimento. | Largura do lençol com elástico de 138 cm. | Composição 100% algodão para conforto. | Hipoalergênico, ideal para pele sensível. | Conjunto com duas fronhas de 50 cm x 70 cm.', 72.59, 66.78, 17, 'CAM-0004', 'real-cama-mesa-e-banho-04.webp', 'aprovado', 0, 0, '2026-03-09 13:24:30', '2026-03-09 13:24:30'),
(5, 1, 22, 'Radio Pioneer Bluetooth Automotivo Mvh-145br Usb Mp3 Fm | Frete grátis', 'radio-pioneer-bluetooth-automotivo-mvh-145br-usb-mp3-fm-frete-gr-atis', 'Unidades por kit: 1. | Potência nominal de 4 X 23W RMS para um som potente e envolvente. | Conexão Bluetooth 5.4 para streaming de músicas e chamadas. | Sintonizador de rádio FM com 18 estações favoritas. | Entrada USB frontal para fácil conexão de dispositivos. | Equalizador 2-bandas para personalização do som. | Iluminação dos botões na cor ciano para um toque moderno.', 326.77, 300.63, 38, 'AUT-0005', 'real-automotivo-05.webp', 'aprovado', 0, 0, '2026-03-09 13:24:33', '2026-03-09 13:24:33'),
(6, 1, 23, 'Chave De Impacto Furadeira Parafusadeira Several Importados Recarregável 48v Azul 2000mah | Frete grátis', 'chave-de-impacto-furadeira-parafusadeira-several-importados-recarreg-avel-48v-azul-2000mah-frete-gr-atis', 'Voltagem: 127/220V. | Parafusadeira elétrica de impacto. | É sem fio. | Iluminação LED para melhor visibilidade.', 290.37, 267.14, 38, 'FER-0006', 'real-ferramentas-06.webp', 'aprovado', 1, 0, '2026-03-09 13:24:36', '2026-03-09 13:24:36'),
(7, 1, 27, 'Kit Teclado e Mouse Sem Fio Multi (Multilaser) Recarregável Com Bateria 200mAh - TC250 | Frete grátis', 'kit-teclado-e-mouse-sem-fio-multi-multilaser-recarreg-avel-com-bateria-200mah-tc250-frete-gr-atis', 'Cor do mouse: Preto. | Kit composto por teclado TC250 e mouse TC250. | Bateria incluída. | Teclado com switch cherry mx black. | Idioma do teclado:  Português Brasil. | Mouse com sensor óptico e resolução de 1200 dpi. | Combinação perfeita e completa para diferentes atividades diárias.', 134.48, 123.72, 44, 'INF-0007', 'real-informatica-07.webp', 'aprovado', 0, 0, '2026-03-09 13:24:39', '2026-03-09 13:24:39'),
(8, 1, 18, 'Kit Papelaria Fofa Kawaii 25 Itens Presente Criativo Menina | Frete grátis', 'kit-papelaria-fofa-kawaii-25-itens-presente-criativo-menina-frete-gr-atis', 'Frete grátis com entrega no mesmo dia ✓ Compre online com segurança com Compra Garantida © Kit Papelaria Fofa Kawaii 25 Itens Presente Criativo Menina ❤', 106.55, 98.03, 53, 'PAP-0008', 'real-papelaria-08.webp', 'aprovado', 0, 0, '2026-03-09 13:24:42', '2026-03-09 13:24:42'),
(9, 1, 17, 'Suplemento Hemolipet Sticks Para Cães 30 Unidades - Avert', 'suplemento-hemolipet-sticks-para-c-aes-30-unidades-avert', 'Tamanho da raça: Todos os tamanhos. | Unidades por kit: 1. | Formato de venda: Unidade. | Peso da unidade: 7 g. | Tipo de embalagem: Frasco. | Peso líquido: 210 g. | Unidades por embalagem: 30. | Sabor: Palatável. | Suplemento com ferro aminoácido quelato e vitaminas do complexo B para suporte nutricional. | Indicado para cães de todas as idades. | Dosagem: 1 stick diário para cada 10 kg de peso. | Contém 30 unidades em pote plástico para fácil armazenamento.', 68.22, 62.76, 24, 'PET-0009', 'real-pet-shop-09.webp', 'aprovado', 1, 2, '2026-03-09 13:24:45', '2026-03-10 22:09:30'),
(10, 1, 15, 'Higienizador cremoso limpeza milagrosa original 250ml Cif', 'higienizador-cremoso-limpeza-milagrosa-original-250ml-cif', 'Unidades por kit: 1. | Volume líquido: 250 mL. | Peso líquido: 250 g. | Squeeze prático 250ml. | Limpeza multi-superfícies. | Fragrância Original. | Ação desengordurante.', 9.90, 9.11, 17, 'LIM-0010', 'real-limpeza-10.webp', 'aprovado', 0, 0, '2026-03-09 13:24:48', '2026-03-09 13:24:48'),
(11, 1, 21, 'Chuteira De Futsal Masculina Class Footballer Umbro | Parcelamento sem juros', 'chuteira-de-futsal-masculina-class-footballer-umbro-parcelamento-sem-juros', 'Sola de borracha. | Adequadas para usar em cimento. | Conforto e resistência para dominar cada partida.', 133.01, 122.37, 34, 'ESP-0011', 'real-esportes-11.webp', 'aprovado', 0, 0, '2026-03-09 13:24:51', '2026-03-09 13:24:51'),
(12, 1, 19, 'Código Limpo - Editora Alta Books', 'c-odigo-limpo-editora-alta-books', 'Ano de publicação: 2009. | Capa do livro: Mole. | Gênero: Informática e tecnologia. | Idade mínima recomendada: 10 anos. | Número de páginas: 425. | ISBN: 9788576082675, 8576082675.', 72.03, 66.27, 56, 'LIV-0012', 'real-livraria-12.webp', 'aprovado', 1, 0, '2026-03-09 13:24:54', '2026-03-09 13:24:54'),
(13, 1, 31, '50 Balão Coração Vermelho 10 Polegadas 22cm Balões Festa', '50-bal-ao-corac-ao-vermelho-10-polegadas-22cm-bal-oes-festa', 'Unidades por kit: 50. | Formato de venda: Kit. | Unidades por embalagem: 50. | Tamanho cheio: 22cm. | Balões de coração de 10 polegadas (22cm) para decoração de festas. | Material metalizado que proporciona brilho e elegância. | Adequado para uso com hélio, flutua facilmente. | Reutilizáveis, ideais para várias celebrações. | Perfeitos para eventos românticos e comemorações especiais.', 32.04, 29.48, 31, 'ART-0100', 'real-artigos-de-festa-100.webp', 'aprovado', 1, 0, '2026-03-09 13:32:15', '2026-03-09 13:32:15'),
(14, 1, 6, 'Fita Multiuso Autoadesiva Vedatudo 30cm Dryko', 'fita-multiuso-autoadesiva-vedatudo-30cm-dryko', 'Comprimento: 90 m. | Largura: 10 m. | Unidades por kit: 1. | Formato de venda: Unidade. | Espessura: 0.45 mm. | Manta Asfáltica Adesiva 10m X 30cm é ideal para impermeabilização de telhados, lajes e calhas. | Comprimento do rolo: 10m. | Largura do rolo: 30cm.', 48.90, 44.99, 17, 'CON-0101', 'real-construcao-101.webp', 'aprovado', 0, 0, '2026-03-09 13:32:25', '2026-03-09 13:32:25'),
(15, 1, 26, 'Presente Azulejo Lar Doce Lar Família Personalizad Casa Nova Cor Branco | Parcelamento sem juros', 'presente-azulejo-lar-doce-lar-fam-ilia-personalizad-casa-nova-cor-branco-parcelamento-sem-juros', 'Altura: 20 cm. | Largura: 20 cm. | Unidades por kit: 1. | Formato de venda: Unidade. | Cor da armação: Preto. | Com frases.', 53.24, 48.98, 25, 'DEC-0102', 'real-decoracao-102.webp', 'aprovado', 0, 1, '2026-03-09 13:32:35', '2026-03-10 21:57:00'),
(16, 1, 10, 'Nutella Creme de Avelã 1 uni 350g', 'nutella-creme-de-avel-a-1-uni-350g', 'Peso da unidade: 350 g. | Peso líquido: 350 g. | Sabor único: combinação equilibrada de avelãs torradas e cacau.  Textura cremosa: fácil de espalhar, perfeita para pães,', 23.02, 21.18, 36, 'DOC-0103', 'real-doces-103.webp', 'aprovado', 0, 0, '2026-03-09 13:32:45', '2026-03-09 13:32:45'),
(17, 1, 5, 'Galaxy Buds Core Preto | Frete grátis', 'galaxy-buds-core-preto-frete-gr-atis', 'Quantidade de pares: 1. | Possui tecnologia True Wireless. | Com estojo de carregamento. | Inclui microfone. | Resistentes à água.', 279.90, 257.51, 17, 'ELE-0104', 'real-eletronicos-104.webp', 'aprovado', 1, 0, '2026-03-09 13:32:57', '2026-03-09 13:32:57'),
(18, 1, 2, 'Frasqueira Medicamentos Nitron Branca 6,2L Alça Ergonômica Organizador Porta Remédios Plástico Resistente Multiuso Com Tampa Trava Ideal Farmácia Uso Doméstico Kit Primeiros Socorr', 'frasqueira-medicamentos-nitron-branca-6-2l-alca-ergon-omica-organizador-porta-rem-edios-pl-astico-resistente-multiuso-com-tampa-trava-ideal-farm-acia-uso-dom-estico-kit-primeiros-socorros-compacta', 'Sua capacidade de carga é 6.2kg. | Tem 2  compartimentos. | Medidas: 18.8cm de largura, 28cm de comprimento e 19.8cm de profundidade.   | Tem uma alça que facilita o transporte. | Ideal para organizar e proteger seus elementos de forma eficaz.', 39.20, 36.06, 47, 'FAR-0105', 'real-farmacia-105.webp', 'aprovado', 0, 2, '2026-03-09 13:33:06', '2026-03-10 22:09:30'),
(19, 1, 9, 'Gamela De Bambu Petisqueira Tabua Frios Churrasco Aperitivos', 'gamela-de-bambu-petisqueira-tabua-frios-churrasco-aperitivos', 'Diâmetro total: 27.5 cm. | Altura total: 1.5 cm. | Largura total: 17.5 cm. | Comprimento total: 27.5 cm. | Quantidade de cavidades: 4. | Unidades por embalagem: 1. | Feita em bambu, material resistente e durável para uso a longo prazo. | Possui quatro cavidades para servir uma variedade de petiscos. | Cor marrom que combina facilmente com qualquer decoração.', 26.90, 24.75, 34, 'FRI-0106', 'real-frios-106.webp', 'aprovado', 0, 0, '2026-03-09 13:33:16', '2026-03-09 13:33:16'),
(20, 1, 11, 'Mix De Fruta Seca Pacote Com 200g Brasil Frutt', 'mix-de-fruta-seca-pacote-com-200g-brasil-frutt', 'Unidades por embalagem: 1. | Condição de venda: Embalado. | Unidades por kit: 1.', 23.91, 22.00, 30, 'FRU-0107', 'real-frutas-107.webp', 'aprovado', 0, 0, '2026-03-09 13:33:25', '2026-03-09 13:33:25'),
(21, 1, 16, 'Pasta De Dentes Oral-b 1.2.3 Anticáries Creme Pacote 12 70g', 'pasta-de-dentes-oral-b-1-2-3-antic-aries-creme-pacote-12-70g', 'Unidades por kit: 12. | Volume líquido: 70 mL. | Peso líquido: 70 kg. | Sabor menta. | Benefícios: anticáries.', 28.80, 26.50, 58, 'HIG-0108', 'real-higiene-108.webp', 'aprovado', 1, 0, '2026-03-09 13:33:36', '2026-03-09 13:33:36'),
(22, 1, 24, 'Varal De Luzes Festao 40m Com 40 Lâmpadas Inclusas Voltagem 127v Iluminação Lâmpada Cordão Varal Luzes Decoração Prova D\'água Natal Casamento Jardim Bar | Parcelamento sem juros', 'varal-de-luzes-festao-40m-com-40-l-ampadas-inclusas-voltagem-127v-iluminac-ao-l-ampada-cord-ao-varal-luzes-decorac-ao-prova-d-agua-natal-casamento-jardim-bar-parcelamento-sem-juros', 'Cor das luzes: Incandescente. | Cor do cabo: Preto. | Apresentação do produto: cordão. | Possui luz incandescente. | Tipo de alimentação: tomada. | Para uso bar, casamento, exterior, interior, jardim, festa junina. | Possui um grau de proteção IP65.', 252.30, 232.12, 23, 'JAR-0109', 'real-jardim-109.webp', 'aprovado', 0, 0, '2026-03-09 13:33:50', '2026-03-09 13:33:50'),
(23, 1, 25, 'Poltrona Para Sala Decorativa Isa Suede Cappuccino Adonai | Frete grátis', 'poltrona-para-sala-decorativa-isa-suede-cappuccino-adonai-frete-gr-atis', 'Material dos pés: Madeira. | É reclinável: Não. | Material do estofamento: Suede. | Dimensões: 70cm de largura, 73cm de altura e 72cm de profundidade. | Estilo  moderno. | Dimensões da cama: 72cm de largura, 77cm de altura e 82cm de profundidade. | Inclui kit 4 pés trapézio.', 387.90, 356.87, 46, 'MOV-0110', 'real-moveis-110.webp', 'aprovado', 0, 0, '2026-03-09 13:34:07', '2026-03-09 13:34:07'),
(24, 1, 29, 'Óculos Esportivo Baixa Pace Ciclismo Bike Corrida Proteção Uv400 Pedal Beach Tennis Tenis Futevolei Ftv', 'oculos-esportivo-baixa-pace-ciclismo-bike-corrida-protec-ao-uv400-pedal-beach-tennis-tenis-futevolei-ftv', 'Material da haste: Acetato italiano. | Cor da haste: Preto. | Material da armação: Acetato italiano. | Material da lente: Acetato Italiano. | Cor da lente: Preto. | Cor da armação: Preto. | Gênero: sem gênero. | O formato do armação é esportiva. | Com proteção UV. | Acessórios incluídos: saquinho poliester.', 56.74, 52.20, 9, 'OTI-0111', 'real-otica-111.webp', 'aprovado', 0, 0, '2026-03-09 13:34:16', '2026-03-09 13:34:16'),
(25, 1, 13, 'Amassadeira Semi Rápida 3kg Ali03 Braesi | Frete grátis', 'amassadeira-semi-r-apida-3kg-ali03-braesi-frete-gr-atis', 'Número de velocidades: 1. | Potência: 220.5 W.', 1345.22, 1237.60, 44, 'PAD-0112', 'real-padaria-112.webp', 'aprovado', 1, 1, '2026-03-09 13:34:26', '2026-03-09 14:16:09'),
(26, 1, 33, 'Vonixx Blend Black Edition 500mL em spray', 'vonixx-blend-black-edition-500ml-em-spray', 'Fragrância: Carnaúba. | Tipo de embalagem: Garrafa. | Peso da unidade: 500 g. | Volume da unidade: 500 mL. | Acabamento: Brilho. | Formato da cera: Spray. | A base da cera é sintética. | A cor recomendada é escuro.', 44.29, 40.75, 6, 'POS-0113', 'real-posto-de-lavagem-113.webp', 'aprovado', 0, 0, '2026-03-09 13:34:33', '2026-03-09 13:34:33'),
(27, 1, 30, 'Relógio Technos Masculino Racer Prata - 2115twd/2p | Parcelamento sem juros', 'rel-ogio-technos-masculino-racer-prata-2115twd-2p-parcelamento-sem-juros', 'Cor do bisel: Preto. | Material da correia: Silicone. | Cor da correia: Preto. | Cor do fundo: Preto. | Design elegante em prata para adultos.', 359.00, 330.28, 26, 'REL-0114', 'real-relojoaria-114.webp', 'aprovado', 0, 0, '2026-03-09 13:34:48', '2026-03-09 13:34:48'),
(28, 1, 3, 'Fatiador De Tomates Manual Para Fatias De 5mm Zjh6000 | Frete grátis', 'fatiador-de-tomates-manual-para-fatias-de-5mm-zjh6000-frete-gr-atis', 'Corpo em alumínio fundido, leve e resistente. | Lâmina em alumínio fundido, durável e afiada. | Fatias uniformes de 5mm para apresentação ideal. | Design prático e fácil de usar, ideal para cozinhar.', 1309.90, 1205.11, 53, 'RES-0115', 'real-restaurantes-115.webp', 'aprovado', 0, 0, '2026-03-09 13:34:56', '2026-03-09 13:34:56'),
(29, 1, 7, 'Ferramentas Para Empreendedores, De Harvard Business School Press. Editora Record, Capa Mole Em Português', 'ferramentas-para-empreendedores-de-harvard-business-school-press-editora-record-capa-mole-em-portugu-es', 'Com índice: Sim. | Capa do livro: Mole. | Gênero: Negócios, finanças e economia. | Idade mínima recomendada: 18 anos. | Número de páginas: 294. | ISBN: 9788501074959.', 46.46, 42.74, 31, 'SER-0116', 'real-servicos-116.webp', 'aprovado', 1, 1, '2026-03-09 13:35:03', '2026-03-10 22:09:30'),
(30, 1, 1, 'Papel higiênico Neve Toque da Seda folha dupla 30 m de 32 un', 'papel-higi-enico-neve-toque-da-seda-folha-dupla-30-m-de-32-un', 'Tipo de folha: Folha dupla. | toque de seda. | Suavidade extra.', 56.90, 52.35, 60, 'SUP-0117', 'real-supermercado-117.webp', 'aprovado', 0, 0, '2026-03-09 13:35:13', '2026-03-09 13:35:13'),
(31, 1, 28, 'Smartphone Samsung Galaxy A36 5G 256GB 8GB RAM | Frete grátis', 'smartphone-samsung-galaxy-a36-5g-256gb-8gb-ram-frete-gr-atis', 'Dispositivo desbloqueado para que você escolha a companhia telefônica de sua preferência. | Compatível com redes 5G. | Tela de 6.7&quot;. | Tem 3 câmeras traseiras de 50mpx/8mpx/5mpx. | Câmeras frontais de 12Mpx. | Bateria de 5 Ah. | Memória interna de 256GB.  | Resistente à água. | Com reconhecimento facial e sensor de impressão digital. | Resistente à poeira.', 1799.00, 1655.08, 8, 'TEL-0118', 'real-telefonia-118.webp', 'aprovado', 0, 0, '2026-03-09 13:35:22', '2026-03-09 13:35:22'),
(32, 1, 12, '2 Tela Aramada Painel Jardim Vertical Horta Suspensa 60x80 Cor Da Estrutura Preto Cor Da Planta Preto', '2-tela-aramada-painel-jardim-vertical-horta-suspensa-60x80-cor-da-estrutura-preto-cor-da-planta-preto', 'Cor da planta: Preto. | Tela aramada com dimensões de 60x80 cm, ideal para otimizar espaços. | Produto artificial, sem necessidade de manutenção. | Estrutura na cor preta, adicionando elegância ao ambiente. | Adequado para ambientes internos e externos como cozinha, quarto e quintal.', 59.99, 55.19, 12, 'VER-0119', 'real-verduras-119.webp', 'aprovado', 0, 0, '2026-03-09 13:35:31', '2026-03-09 13:35:31'),
(33, 1, 4, 'Tênis Rainha Iate Iv | Frete grátis', 't-enis-rainha-iate-iv-frete-gr-atis', 'Desenho do tecido: Lisa. | Ano de lançamento: 2023. | Tem cadarços para um ajuste confortável. | Calcanhar curto. | Sola antiderrapante. | Design lisa. | Interior de tecido. | A entressola de borracha eva proporciona maior amortecimento e estabilidade na pisada. | Língua acolchoada. | Com talão acolchoado.', 124.39, 114.44, 60, 'MOD-7072', 'real-moda-33.webp', 'aprovado', 0, 0, '2026-03-09 13:39:25', '2026-03-09 13:39:25'),
(34, 2, 2, 'Anador 500mg 20 comprimidos', 'anador-500mg-20-comprimidos', 'Analgesico para alivio de dores e febre. Uso adulto conforme orientacao profissional.', 18.90, 17.01, 33, 'FAR-FP-0034', 'farmacia-anador.jpg', 'aprovado', 0, 4, '2026-03-09 14:25:56', '2026-03-10 22:18:43'),
(35, 2, 2, 'Aspirina 500mg 10 comprimidos', 'aspirina-500mg-10-comprimidos', 'Medicamento para alivio de dores leves e moderadas, com acao anti-inflamatoria.', 15.40, 13.86, 34, 'FAR-FP-0035', 'farmacia-aspirina.jpg', 'aprovado', 0, 3, '2026-03-09 14:25:56', '2026-03-10 22:18:43'),
(36, 2, 2, 'Pomada Dermatologica Reparadora 45g', 'pomada-dermatologica-reparadora-45g', 'Pomada de uso topico para cuidado da pele com acao calmante e hidratante.', 27.90, 25.11, 44, 'FAR-FP-0036', 'farmacia-pomada-1.webp', 'aprovado', 0, 1, '2026-03-09 14:25:56', '2026-03-10 21:25:41'),
(37, 2, 2, 'Pomada Antifungica 30g', 'pomada-antifungica-30g', 'Auxilia no tratamento topico de irritacoes e micoses superficiais da pele.', 31.50, 28.35, 60, 'FAR-FP-0037', 'farmacia-pomada-2.webp', 'aprovado', 1, 3, '2026-03-09 14:25:56', '2026-03-10 22:18:43'),
(38, 2, 2, 'Pomada Cicatrizante 60g', 'pomada-cicatrizante-60g', 'Cuidado diario para a pele sensibilizada, ajudando na recuperacao da barreira cutanea.', 34.20, 30.78, 16, 'FAR-FP-0038', 'farmacia-pomada-3.webp', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(39, 2, 2, 'Cha Funcional Akabe 200g', 'cha-funcional-akabe-200g', 'Blend herbal para rotina de bem-estar, sabor suave e preparo pratico.', 24.90, 22.41, 14, 'FAR-FP-0039', 'farmacia-cha-akabe.webp', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(40, 2, 16, 'Shampoo Anticaspa 400ml', 'shampoo-anticaspa-400ml', 'Limpeza profunda do couro cabeludo com formula de uso diario.', 22.90, 20.61, 15, 'HIG-FP-0040', 'farmacia-cha-akabe.webp', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(41, 2, 16, 'Shampoo Hidratacao Intensa 350ml', 'shampoo-hidratacao-intensa-350ml', 'Shampoo para cabelos secos com limpeza suave e hidratacao prolongada.', 19.80, 17.82, 50, 'HIG-FP-0041', 'farmacia-pomada-1.webp', 'aprovado', 1, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(42, 2, 16, 'Shampoo Infantil Suave 300ml', 'shampoo-infantil-suave-300ml', 'Formula suave, sem ardencia, indicada para higiene diaria infantil.', 17.90, 16.11, 24, 'HIG-FP-0042', 'farmacia-pomada-2.webp', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(43, 2, 16, 'Sabonete Liquido Neutro 500ml', 'sabonete-liquido-neutro-500ml', 'Sabonete liquido para limpeza delicada da pele, com pH balanceado.', 14.90, 13.41, 49, 'HIG-FP-0043', 'farmacia-pomada-3.webp', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(44, 2, 16, 'Creme Dental Protecao Total 90g', 'creme-dental-protecao-total-90g', 'Creme dental com fluor para higiene bucal completa e refrescancia.', 8.90, 8.01, 31, 'HIG-FP-0044', 'farmacia-aspirina.jpg', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(45, 2, 16, 'Enxaguante Bucal 500ml', 'enxaguante-bucal-500ml', 'Enxaguante para complementar a higiene bucal diaria e controle de placa.', 16.40, 14.76, 68, 'HIG-FP-0045', 'farmacia-anador.jpg', 'aprovado', 1, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(46, 2, 16, 'Desodorante Aerosol 150ml', 'desodorante-aerosol-150ml', 'Protecao antitranspirante com fragrancia suave e secagem rapida.', 13.90, 12.51, 12, 'HIG-FP-0046', 'farmacia-anador.jpg', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(47, 2, 16, 'Perfume Feminino Floral 100ml', 'perfume-feminino-floral-100ml', 'Fragrancia floral com notas adocicadas e fixacao prolongada para uso diario.', 69.90, 62.91, 19, 'HIG-FP-0047', 'farmacia-pomada-2.webp', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(48, 2, 16, 'Perfume Masculino Amadeirado 100ml', 'perfume-masculino-amadeirado-100ml', 'Fragrancia amadeirada com toque citrico, ideal para uso casual e noturno.', 74.90, 67.41, 13, 'HIG-FP-0048', 'farmacia-pomada-3.webp', 'aprovado', 0, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56'),
(49, 2, 16, 'Kit Higiene Pessoal Viagem 5 Itens', 'kit-higiene-pessoal-viagem-5-itens', 'Kit com itens essenciais para rotina de higiene em casa ou viagem.', 29.90, 26.91, 46, 'HIG-FP-0049', 'farmacia-cha-akabe.webp', 'aprovado', 1, 0, '2026-03-09 14:25:56', '2026-03-09 14:25:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(25) DEFAULT NULL,
  `endereco_entrega` text DEFAULT NULL,
  `role` enum('admin','lojista','consumidor') NOT NULL DEFAULT 'consumidor',
  `status` enum('ativo','inativo','bloqueado') NOT NULL DEFAULT 'ativo',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `telefone`, `endereco_entrega`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'João Rezende', 'admin@capelamarket.com', '$2y$10$UGLgqQ1Lk9r8RwcvCPHvieNkx.o0THnwnX.x5C9m2kRYCgl/PSJLa', '(79) 99924-8114', 'Rua Quirino, 1100', 'admin', 'ativo', '2026-03-10 10:17:19', '2026-03-10 10:18:58'),
(2, 'Loja Tem de Tudo', 'lojista-loja-tem-de-tudo@local.invalid', '$2y$10$UGLgqQ1Lk9r8RwcvCPHvieNkx.o0THnwnX.x5C9m2kRYCgl/PSJLa', '(79) 98139-5097', NULL, 'lojista', 'ativo', '2026-03-10 20:57:52', '2026-03-10 22:05:03'),
(3, 'Farmacia Popular', 'lojista-farmacia-popular@local.invalid', '$2y$10$UGLgqQ1Lk9r8RwcvCPHvieNkx.o0THnwnX.x5C9m2kRYCgl/PSJLa', '79 98139-5097', NULL, 'lojista', 'ativo', '2026-03-10 20:57:52', '2026-03-10 22:05:20'),
(8, 'Elson Ribeiro Santos', 'elson@gmail.com', '$2y$10$A3OdKrTJ1M9K3KljA6sZEuZy7.xqi2gINABY/SDMe3nomtWTmYMEG', '79 99924-8114', 'Rua da palmeira, 345', 'consumidor', 'ativo', '2026-03-10 21:05:26', '2026-03-10 22:18:43');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `imagens_produtos`
--
ALTER TABLE `imagens_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `lojas`
--
ALTER TABLE `lojas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_lojas_slug` (`slug`),
  ADD KEY `idx_lojas_status_destaque` (`status`,`destaque`),
  ADD KEY `idx_lojas_bairro` (`bairro`),
  ADD KEY `fk_lojas_repaired_usuario` (`usuario_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pedidos_loja_status` (`loja_id`,`status`),
  ADD KEY `idx_pedidos_usuario` (`usuario_id`);

--
-- Índices de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pedido_itens_pedido` (`pedido_id`),
  ADD KEY `idx_pedido_itens_produto` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_produtos_slug` (`slug`),
  ADD KEY `idx_produtos_status_destaque` (`status`,`destaque`),
  ADD KEY `idx_produtos_loja` (`loja_id`),
  ADD KEY `idx_produtos_categoria` (`categoria_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuarios_email` (`email`),
  ADD KEY `idx_usuarios_role_status` (`role`,`status`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imagens_produtos`
--
ALTER TABLE `imagens_produtos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `lojas`
--
ALTER TABLE `lojas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `lojas`
--
ALTER TABLE `lojas`
  ADD CONSTRAINT `fk_lojas_repaired_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_repaired_loja` FOREIGN KEY (`loja_id`) REFERENCES `lojas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pedidos_repaired_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `fk_pedido_itens_repaired_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pedido_itens_repaired_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_repaired_loja` FOREIGN KEY (`loja_id`) REFERENCES `lojas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
