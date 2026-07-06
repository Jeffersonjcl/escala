-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/01/2025 às 01:39
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
-- Banco de dados: `clotheatacado`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `acessos`
--

CREATE TABLE `acessos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `chave` varchar(50) NOT NULL,
  `grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `acessos`
--

INSERT INTO `acessos` (`id`, `nome`, `chave`, `grupo`) VALUES
(1, 'Home', 'home', 0),
(2, 'Notas', 'notas', 0),
(3, 'Configurações', 'configuracoes', 0),
(4, 'Clientes', 'clientes', 1),
(5, 'Funcionários', 'funcionarios', 1),
(6, 'Usuários', 'usuarios', 1),
(7, 'Forncedores', 'fornecedores', 1),
(8, 'Cargos', 'cargos', 2),
(10, 'Frequencias', 'frequencias', 2),
(11, 'Formas de Pagamento', 'formas_pgto', 2),
(12, 'Grupos de Acesso', 'grupos', 2),
(13, 'Acessos', 'acessos', 2),
(19, 'Contas à Pagar', 'pagar', 4),
(20, 'Contas à Receber', 'receber', 4),
(25, 'Relatórios Financeiros', 'rel_financeiro', 4),
(26, 'Relatório Balanço Anual', 'rel_balanco', 4),
(43, 'minhas_comissoes', 'minhas_comissoes', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `arquivos`
--

CREATE TABLE `arquivos` (
  `id` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `arquivo` varchar(100) NOT NULL,
  `data_cad` date NOT NULL,
  `registro` varchar(50) NOT NULL,
  `id_reg` int(11) NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `cargos`
--

INSERT INTO `cargos` (`id`, `nome`) VALUES
(1, 'Administrador(a)'),
(2, 'Aux. Financeiro(a)');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `ativo` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `tipo_pessoa` varchar(15) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `profissao` varchar(50) DEFAULT NULL,
  `nacionalidade` varchar(50) DEFAULT NULL,
  `estado_civil` varchar(25) DEFAULT NULL,
  `data_cad` date NOT NULL,
  `senha` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `cpf`, `telefone`, `email`, `endereco`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `cep`, `tipo_pessoa`, `data_nasc`, `profissao`, `nacionalidade`, `estado_civil`, `data_cad`, `senha`) VALUES
(1, 'Leidiane Colares', '012.310.000-01', '(85) 99979-1957', 'leydylyma@gmail.com', 'Via Paisagística do Loteamento Itaperussú', '8', '', 'Itaperi', 'Fortaleza', 'CE', '60743-065', 'Física', '1981-12-12', 'Gastrônama', 'Brasileira', 'Casado(a)', '2025-01-14', '$2y$10$Crf0vFezepUGnzo88n/iS.MxK2tlNXvNjMELW67Os3RrDE7jaAUnW'),
(2, 'Regina Cláudia', '221.656.516-51', '(85) 21261-6516', 'regina@gmail.com', 'Rua Lívio Barreto', '15', '', 'Joaquim Távora', 'Fortaleza', 'CE', '60130-110', 'Física', '1980-05-10', '', '', '', '2025-01-19', '$2y$10$RA4aXfMNJJevvO2LKwYvxOY4bM.Yj1TOAVKHpNv1262ux82dMswtG'),
(3, 'Carla Dos Anzois', '15.654.565/6516-51', '(85) 88888-8888', 'carladosanzois@gmail.com', 'Rua Carolino de Aquino', '124', '', 'Fátima', 'Fortaleza', 'CE', '60050-140', 'Jurídica', '0000-00-00', '', '', '', '2025-01-21', '$2y$10$We6x78DCeKkIULChjLt9SeI6D98ZsfweDbI3TF7vLB4u6IMvOCwGW');

-- --------------------------------------------------------

--
-- Estrutura para tabela `config`
--

CREATE TABLE `config` (
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `icone` varchar(100) DEFAULT NULL,
  `logo_rel` varchar(100) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `validade_orcamento` int(11) DEFAULT NULL,
  `excluir_orcamentos` int(11) DEFAULT NULL,
  `comissao_geral` int(11) DEFAULT NULL,
  `api_whatsapp` varchar(5) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `instancia` varchar(100) DEFAULT NULL,
  `marca_dagua` varchar(5) NOT NULL,
  `chave_pix` varchar(50) DEFAULT NULL,
  `impressao_automatica` varchar(5) NOT NULL,
  `fonte_comprovante` int(11) NOT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `dias_comissao` int(11) DEFAULT NULL,
  `cobranca_auto` varchar(5) NOT NULL,
  `data_cobranca` date DEFAULT NULL,
  `duas_vias_os` varchar(5) DEFAULT NULL,
  `msg_rodape` text DEFAULT NULL,
  `garantia` longtext DEFAULT NULL,
  `termos` longtext DEFAULT NULL,
  `logo_painel` varchar(100) DEFAULT NULL,
  `ativo` varchar(5) DEFAULT NULL,
  `entrar_automatico` varchar(5) DEFAULT NULL,
  `mostrar_preloader` varchar(5) DEFAULT NULL,
  `mensagem_auto` varchar(5) DEFAULT NULL,
  `abertura_caixa` varchar(5) DEFAULT NULL,
  `contador` int(11) NOT NULL,
  `ano_atual` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `config`
--

INSERT INTO `config` (`nome`, `email`, `telefone`, `endereco`, `instagram`, `logo`, `icone`, `logo_rel`, `id`, `validade_orcamento`, `excluir_orcamentos`, `comissao_geral`, `api_whatsapp`, `token`, `instancia`, `marca_dagua`, `chave_pix`, `impressao_automatica`, `fonte_comprovante`, `cnpj`, `dias_comissao`, `cobranca_auto`, `data_cobranca`, `duas_vias_os`, `msg_rodape`, `garantia`, `termos`, `logo_painel`, `ativo`, `entrar_automatico`, `mostrar_preloader`, `mensagem_auto`, `abertura_caixa`, `contador`, `ano_atual`) VALUES
('Clothe Atacado', 'jeffersonjcl@gmail.com', '(85) 99985-5584', 'Rua Santos, 8, Passaré, Fortaleza-CE', 'jefferson.lima', 'logo.png', 'icone.png', 'logo.jpg', 1, 7, 60, 0, 'Não', '66e8af3f-cec0-4819-84f3-92141c2b5fdf', 'GPGBJCn0PZP4RP4HzM4PQoQXBFjt2VyCqQypSRCwLGCiObM4mP', 'Sim', '', 'Não', 12, '00.100.000/0001-00', 7, 'Não', '2025-01-24', 'Não', 'É UMA GRANDE SATISFAÇÃO TER VOCÊ COMO NOSSO CLIENTE, VOLTE SEMPRE!', 'Garantia                                                                                                                                                                                                                                                                                                ', 'Termos', 'logo_painel.png', 'Sim', 'Sim', 'Sim', 'Sim', 'Não', 36, '2025');

-- --------------------------------------------------------

--
-- Estrutura para tabela `formas_pgto`
--

CREATE TABLE `formas_pgto` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `formas_pgto`
--

INSERT INTO `formas_pgto` (`id`, `nome`) VALUES
(2, 'Cartão de Crédito'),
(3, 'Cartão de Débito'),
(5, 'Dinheiro'),
(7, 'Boleto'),
(16, 'Pix'),
(17, 'Cheque');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `telefone` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `pix` varchar(50) DEFAULT NULL,
  `data` date NOT NULL,
  `comissao` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`, `telefone`, `email`, `endereco`, `pix`, `data`, `comissao`) VALUES
(1, 'Clothe Atacado', '(85) 98753-5807', 'lia.colares.2011@gmail.com', 'Rua Seis, 8', '', '2025-01-15', 20.00),
(2, 'LeLiLiz Atacado', '(85) 99999-9999', 'lojateste@gmail.com', 'Rua Seis, 8', '', '2025-01-21', 25.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `frequencias`
--

CREATE TABLE `frequencias` (
  `id` int(11) NOT NULL,
  `frequencia` varchar(25) NOT NULL,
  `dias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `frequencias`
--

INSERT INTO `frequencias` (`id`, `frequencia`, `dias`) VALUES
(1, 'Diária', 1),
(2, 'Semanal', 7),
(3, 'Mensal', 30),
(4, 'Trimestral', 90),
(5, 'Semestral', 180),
(6, 'Anual', 365);

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupo_acessos`
--

CREATE TABLE `grupo_acessos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `grupo_acessos`
--

INSERT INTO `grupo_acessos` (`id`, `nome`) VALUES
(1, 'Pessoas'),
(2, 'Cadastros'),
(3, 'Produtos'),
(4, 'Financeiro'),
(5, 'Ordens de Serviços');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `numero_nota` varchar(15) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `cliente` int(11) NOT NULL,
  `funcionario` int(11) NOT NULL,
  `fornecedor` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `data_entrega` date NOT NULL,
  `nota` varchar(100) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `obs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `notas`
--

INSERT INTO `notas` (`id`, `numero_nota`, `descricao`, `cliente`, `funcionario`, `fornecedor`, `valor`, `data`, `hora`, `data_entrega`, `nota`, `status`, `obs`) VALUES
(1, '0030/2025', NULL, 1, 2, 1, 2560.00, '2025-01-26', '10:25:56', '2025-01-26', '26-01-2025-10-25-55-teste.xlsx', 'Faturada', 'Sem Observações'),
(2, '0031/2025', NULL, 1, 2, 1, 1780.00, '2025-01-26', '10:28:04', '2025-01-27', '26-01-2025-10-28-04-motoshow.pdf', 'Faturada', 'Sem Observações'),
(3, '0032/2025', NULL, 2, 4, 2, 2451.00, '2025-01-26', '10:30:37', '2025-01-27', '26-01-2025-10-30-36-notaTeste.png', 'Faturada', 'Sem Observações'),
(4, '0033/2025', NULL, 2, 4, 2, 987.00, '2025-01-26', '10:31:02', '2025-01-27', '26-01-2025-10-31-02-nota.png', 'Faturada', 'Sem Observações'),
(5, '0034/2025', NULL, 3, 5, 1, 3548.00, '2025-01-26', '10:33:12', '2025-01-28', '26-01-2025-10-33-12-finalizado.docx', 'Faturada', 'Sem Observações'),
(6, '0035/2025', NULL, 3, 5, 2, 1546.00, '2025-01-26', '10:36:17', '2025-01-28', '26-01-2025_10-36-17-tela_Bloqueio.rar', 'Faturada', 'Sem Observações'),
(7, '0036/2025', NULL, 1, 2, 1, 2145.00, '2025-01-26', '23:28:42', '2025-01-27', '26-01-2025_23-28-42-nota.png', 'Pendente', 'Sem Observações');

-- --------------------------------------------------------

--
-- Estrutura para tabela `os`
--

CREATE TABLE `os` (
  `id` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `veiculo` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `data_entrega` date NOT NULL,
  `dias_validade` int(11) DEFAULT NULL,
  `valor` decimal(8,2) DEFAULT NULL,
  `desconto` decimal(8,2) DEFAULT NULL,
  `tipo_desconto` varchar(10) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `total_produtos` decimal(8,2) DEFAULT NULL,
  `total_servicos` decimal(8,2) DEFAULT NULL,
  `funcionario` int(11) NOT NULL,
  `frete` decimal(8,2) DEFAULT NULL,
  `placa` varchar(10) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `atual_km` varchar(10) DEFAULT NULL,
  `laudo` varchar(1000) DEFAULT NULL,
  `mao_obra` decimal(8,2) DEFAULT NULL,
  `vall` decimal(8,2) DEFAULT NULL,
  `orcamento` int(11) DEFAULT NULL,
  `val_entrada` decimal(8,2) DEFAULT NULL,
  `dias_garantia` varchar(50) DEFAULT NULL,
  `pago` varchar(5) DEFAULT NULL,
  `forma_pgto` varchar(20) DEFAULT NULL,
  `mecanico` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagar`
--

CREATE TABLE `pagar` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `funcionario` int(11) NOT NULL,
  `fornecedor` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `data_lanc` date NOT NULL,
  `data_venc` date NOT NULL,
  `data_pgto` date NOT NULL,
  `usuario_lanc` int(11) NOT NULL,
  `usuario_pgto` int(11) NOT NULL,
  `frequencia` int(11) NOT NULL,
  `saida` varchar(50) DEFAULT NULL,
  `arquivo` varchar(100) DEFAULT NULL,
  `pago` varchar(5) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  `referencia` varchar(40) NOT NULL,
  `id_ref` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `pagar`
--

INSERT INTO `pagar` (`id`, `descricao`, `funcionario`, `fornecedor`, `cliente`, `valor`, `data_lanc`, `data_venc`, `data_pgto`, `usuario_lanc`, `usuario_pgto`, `frequencia`, `saida`, `arquivo`, `pago`, `obs`, `referencia`, `id_ref`, `quantidade`) VALUES
(1, 'Nota Nº 0030/2025', 0, 1, 0, 2048.00, '2025-01-26', '2025-01-26', '2025-01-26', 1, 1, 0, 'Pix', '26-01-2025-10-25-55-teste.xlsx', 'Sim', NULL, 'Nota', 1, NULL),
(2, 'Nota Nº 0032/2025', 0, 2, 0, 1838.25, '2025-01-26', '2025-01-27', '2025-01-26', 1, 1, 0, 'Pix', '26-01-2025-10-30-36-notaTeste.png', 'Sim', NULL, 'Nota', 3, NULL),
(3, 'Nota Nº 0034/2025', 0, 1, 0, 2838.40, '2025-01-26', '2025-01-28', '2025-01-26', 1, 1, 0, 'Pix', '26-01-2025-10-33-12-finalizado.docx', 'Sim', NULL, 'Nota', 5, NULL),
(4, 'Nota Nº 0031/2025', 0, 1, 0, 1424.00, '2025-01-26', '2025-01-27', '2025-01-29', 1, 1, 0, 'Cheque', '26-01-2025-10-28-04-motoshow.pdf', 'Sim', NULL, 'Nota', 2, NULL),
(5, 'Nota Nº 0035/2025', 0, 2, 0, 1159.50, '2025-01-26', '2025-01-28', '2025-01-29', 1, 1, 0, 'Cheque', '26-01-2025_10-36-17-tela_Bloqueio.rar', 'Sim', NULL, 'Nota', 6, NULL),
(6, 'Nota Nº 0033/2025', 0, 2, 0, 740.25, '2025-01-26', '2025-01-27', '2025-01-29', 1, 1, 0, 'Boleto', '26-01-2025-10-31-02-nota.png', 'Sim', NULL, 'Nota', 4, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `receber`
--

CREATE TABLE `receber` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `cliente` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `data_lanc` date NOT NULL,
  `data_venc` date NOT NULL,
  `data_pgto` date NOT NULL,
  `usuario_lanc` int(11) NOT NULL,
  `usuario_pgto` int(11) NOT NULL,
  `frequencia` int(11) NOT NULL,
  `saida` varchar(50) DEFAULT NULL,
  `arquivo` varchar(100) DEFAULT NULL,
  `pago` varchar(5) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  `referencia` varchar(40) NOT NULL,
  `id_ref` int(11) DEFAULT NULL,
  `desconto` decimal(8,2) DEFAULT NULL,
  `troco` decimal(8,2) DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `fornecedor` int(11) DEFAULT NULL,
  `comissao` decimal(8,2) DEFAULT NULL,
  `porc_comissao` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `receber`
--

INSERT INTO `receber` (`id`, `descricao`, `cliente`, `valor`, `data_lanc`, `data_venc`, `data_pgto`, `usuario_lanc`, `usuario_pgto`, `frequencia`, `saida`, `arquivo`, `pago`, `obs`, `referencia`, `id_ref`, `desconto`, `troco`, `hora`, `fornecedor`, `comissao`, `porc_comissao`) VALUES
(1, 'Nota Nº 0030/2025', 1, 2560.00, '2025-01-26', '2025-01-26', '2025-01-26', 1, 1, 0, 'Cheque', '26-01-2025-10-25-55-teste.xlsx', 'Sim', NULL, 'Nota', 1, NULL, NULL, '10:49:02', 1, 512.00, 20.00),
(2, 'Nota Nº 0032/2025', 2, 2451.00, '2025-01-26', '2025-01-27', '2025-01-26', 1, 1, 0, 'Cheque', '26-01-2025-10-30-36-notaTeste.png', 'Sim', NULL, 'Nota', 3, NULL, NULL, '16:31:13', 2, 612.75, 25.00),
(3, 'Nota Nº 0034/2025', 3, 3548.00, '2025-01-26', '2025-01-28', '2025-01-26', 1, 1, 0, 'Cheque', '26-01-2025-10-33-12-finalizado.docx', 'Sim', NULL, 'Nota', 5, NULL, NULL, '21:28:47', 1, 709.60, 20.00),
(4, 'Nota Nº 0031/2025', 1, 1780.00, '2025-01-26', '2025-01-27', '2025-01-28', 1, 1, 0, 'Cheque', '26-01-2025-10-28-04-motoshow.pdf', 'Sim', NULL, 'Nota', 2, NULL, NULL, '22:50:51', 1, 356.00, 20.00),
(5, 'Nota Nº 0035/2025', 3, 1546.00, '2025-01-26', '2025-01-28', '2025-01-29', 1, 1, 0, 'Cheque', '26-01-2025_10-36-17-tela_Bloqueio.rar', 'Sim', NULL, 'Nota', 6, NULL, NULL, '22:50:57', 2, 386.50, 25.00),
(6, 'Nota Nº 0033/2025', 2, 987.00, '2025-01-26', '2025-01-27', '2025-01-29', 1, 1, 0, 'Cheque', '26-01-2025-10-31-02-nota.png', 'Sim', NULL, 'Nota', 4, NULL, NULL, '22:51:05', 2, 246.75, 25.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `senha_crip` varchar(130) NOT NULL,
  `nivel` varchar(25) NOT NULL,
  `ativo` varchar(5) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `data` date NOT NULL,
  `comissao` int(11) DEFAULT NULL,
  `id_ref` int(11) NOT NULL,
  `chave_pix` varchar(100) DEFAULT NULL,
  `token` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `senha_crip`, `nivel`, `ativo`, `telefone`, `endereco`, `foto`, `data`, `comissao`, `id_ref`, `chave_pix`, `token`) VALUES
(1, 'Jefferson Lima', 'jeffersonjcl@gmail.com', '123', '$2y$10$n3zaY6MQIr9dxC8i4pYftON9c3IPB/P0v2l2ijAYTzWD8BQNwJ0Zi', 'Administrador', 'Sim', '(85) 99985-5584', 'Rua Seis, 8, Ap 404, Fortaleza-CE', '18-01-2025-10-28-19-jefferson.png', '2025-01-14', NULL, 0, NULL, NULL),
(2, 'Leidiane Colares', 'leydylima@gmail.com', '123', '$2y$10$hpFGkwQhDAakTshsYORc9ORxQ1okQuDfa7Vfc.a7lEa2mCJ3OuGwu', 'Cliente', 'Sim', '(85) 99979-1957', 'Via Paisagística do Loteamento Itaperussú', '21-01-2025-16-14-14-leydy.png', '2025-01-14', NULL, 1, NULL, NULL),
(3, 'Teste Auxiliar', 'auxiliar@gmail.com', '123', '$2y$10$B4OmThJi.iSsG4G/oBd9du.bp.wupM.l1UgpWD00RB8n5SVy0YHEW', 'Aux. Financeiro(a)', 'Sim', '(85) 98790-5873', 'Rua Teste, 145, Itaperi, Fortaleza-CE', 'sem-foto.jpg', '2025-01-16', 0, 0, '', NULL),
(4, 'Regina Cláudia', 'regina@gmail.com', '123', '$2y$10$A4n5bkdMjSHAqB5p3PUhfeioywvJE/sZ01D2uqN5i1O0JgGyb3k72', 'Cliente', 'Sim', '(85) 21261-6516', 'Rua Lívio Barreto', '21-01-2025-16-16-31-regina.png', '2025-01-19', NULL, 2, NULL, NULL),
(5, 'Carla Dos Anzois', '', '123', '$2y$10$hsmLa3EAPTb93wfplS27qe0.8B00fcrQc.GPSUyx7O4kvFEiN4L3W', 'Cliente', 'Sim', '(85) 88888-8888', 'Rua Carolino de Aquino', 'sem-foto.jpg', '2025-01-21', NULL, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_permissoes`
--

CREATE TABLE `usuarios_permissoes` (
  `id` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `permissao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `valor_parcial`
--

CREATE TABLE `valor_parcial` (
  `id` int(11) NOT NULL,
  `id_conta` int(11) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `data` date NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `acessos`
--
ALTER TABLE `acessos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `arquivos`
--
ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `formas_pgto`
--
ALTER TABLE `formas_pgto`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `frequencias`
--
ALTER TABLE `frequencias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `grupo_acessos`
--
ALTER TABLE `grupo_acessos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `os`
--
ALTER TABLE `os`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pagar`
--
ALTER TABLE `pagar`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `receber`
--
ALTER TABLE `receber`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios_permissoes`
--
ALTER TABLE `usuarios_permissoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `valor_parcial`
--
ALTER TABLE `valor_parcial`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acessos`
--
ALTER TABLE `acessos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `arquivos`
--
ALTER TABLE `arquivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `formas_pgto`
--
ALTER TABLE `formas_pgto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `frequencias`
--
ALTER TABLE `frequencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `grupo_acessos`
--
ALTER TABLE `grupo_acessos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `os`
--
ALTER TABLE `os`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagar`
--
ALTER TABLE `pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `receber`
--
ALTER TABLE `receber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios_permissoes`
--
ALTER TABLE `usuarios_permissoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `valor_parcial`
--
ALTER TABLE `valor_parcial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
