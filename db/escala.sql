-- Sistema de Escala Operacional - 1ºPel/1ªCia/1ºBPRAIO
-- Schema completo (substitui o dump antigo do boilerplate de CRM/financeiro)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Infraestrutura reaproveitada (login, RBAC, config, anexos)
-- --------------------------------------------------------

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
-- nivel: Administrador | Escalante | Comandante | Policial
-- id_ref: quando nivel = 'Policial', aponta para policiais.id (mesmo padrão que já
-- ligava usuarios.id_ref -> clientes.id no boilerplate original)

ALTER TABLE `usuarios` ADD PRIMARY KEY (`id`);
ALTER TABLE `usuarios` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- Tabela propositalmente vazia: o próprio index.php cria o primeiro usuário
-- Administrador automaticamente no primeiro acesso.

-- --------------------------------------------------------

CREATE TABLE `grupo_acessos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `grupo_acessos` (`id`, `nome`) VALUES
(1, 'Efetivo'),
(2, 'Escala'),
(3, 'Administração');

ALTER TABLE `grupo_acessos` ADD PRIMARY KEY (`id`);
ALTER TABLE `grupo_acessos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- --------------------------------------------------------

CREATE TABLE `acessos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `chave` varchar(50) NOT NULL,
  `grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `acessos` (`id`, `nome`, `chave`, `grupo`) VALUES
(1, 'Home', 'home', 0),
(2, 'Configurações', 'configuracoes', 0),
(3, 'Usuários', 'usuarios', 3),
(4, 'Grupos de Acesso', 'grupos', 3),
(5, 'Acessos', 'acessos', 3),
(6, 'Policiais', 'policiais', 1),
(7, 'Funções', 'funcoes', 1),
(8, 'Criar Escala', 'escalas', 2),
(9, 'Ver Escalas', 'escalas_listagem', 2),
(10, 'Assinar como Escalante', 'assinar_escalante', 2),
(11, 'Assinar como Comandante', 'assinar_comandante', 2),
(12, 'Postos/Graduações', 'postos', 1);

ALTER TABLE `acessos` ADD PRIMARY KEY (`id`);
ALTER TABLE `acessos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

-- --------------------------------------------------------

CREATE TABLE `usuarios_permissoes` (
  `id` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `permissao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `usuarios_permissoes` ADD PRIMARY KEY (`id`);
ALTER TABLE `usuarios_permissoes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

CREATE TABLE `config` (
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `icone` varchar(100) DEFAULT NULL,
  `logo_rel` varchar(100) DEFAULT NULL,
  `logo_rel2` varchar(100) DEFAULT NULL,
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

INSERT INTO `config` (`nome`, `email`, `telefone`, `endereco`, `instagram`, `logo`, `icone`, `logo_rel`, `logo_rel2`, `id`, `validade_orcamento`, `excluir_orcamentos`, `comissao_geral`, `api_whatsapp`, `token`, `instancia`, `marca_dagua`, `chave_pix`, `impressao_automatica`, `fonte_comprovante`, `cnpj`, `dias_comissao`, `cobranca_auto`, `data_cobranca`, `duas_vias_os`, `msg_rodape`, `garantia`, `termos`, `logo_painel`, `ativo`, `entrar_automatico`, `mostrar_preloader`, `mensagem_auto`, `abertura_caixa`, `contador`, `ano_atual`) VALUES
('1º BPRAIO - 1ª Cia - 1º Pelotão', 'jeffersonjcl@gmail.com', '(85) 99985-5584', 'Sobral-CE', '', 'logo.png', 'icone.png', 'logo.jpg', 'logo_rel2.jpg', 1, 0, 0, 0, 'Não', '', '', 'Sim', '', 'Não', 12, '', 0, 'Não', NULL, 'Não', '', '', '', 'logo_painel.png', 'Sim', 'Sim', 'Sim', 'Não', 'Não', 0, '2026');

ALTER TABLE `config` ADD PRIMARY KEY (`id`);
ALTER TABLE `config` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------

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

ALTER TABLE `arquivos` ADD PRIMARY KEY (`id`);
ALTER TABLE `arquivos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Domínio: Escala Operacional
-- --------------------------------------------------------

CREATE TABLE `funcoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `funcoes` (`id`, `nome`) VALUES
(1, 'Comandante de Equipe'),
(2, 'Piloto'),
(3, 'Atirador'),
(4, 'Rancheiro');

ALTER TABLE `funcoes` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uq_funcao_nome` (`nome`);
ALTER TABLE `funcoes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- --------------------------------------------------------

CREATE TABLE `postos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `postos` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uq_posto_nome` (`nome`);
ALTER TABLE `postos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

CREATE TABLE `policiais` (
  `id` int(11) NOT NULL,
  `nome_guerra` varchar(100) NOT NULL,
  `nome_completo` varchar(150) DEFAULT NULL,
  `matricula` varchar(30) DEFAULT NULL,
  `grupo` enum('Alpha','Bravo') DEFAULT NULL,
  `drso` tinyint(1) NOT NULL DEFAULT 0,
  `turno_padrao` enum('A','B') DEFAULT NULL,
  `funcao_id` int(11) NOT NULL,
  `posto_id` int(11) DEFAULT NULL,
  `numeral` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `foto` varchar(150) DEFAULT 'sem-foto.jpg',
  `disponivel` tinyint(1) NOT NULL DEFAULT 1,
  `motivo_indispo` text DEFAULT NULL,
  `data_inicio_indispo` date DEFAULT NULL,
  `dias_indispo` int(11) DEFAULT NULL,
  `data_fim_indispo` date DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `policiais` ADD PRIMARY KEY (`id`), ADD KEY `fk_policiais_funcao` (`funcao_id`), ADD KEY `fk_policiais_posto` (`posto_id`);
ALTER TABLE `policiais` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `policiais` ADD CONSTRAINT `fk_policiais_funcao` FOREIGN KEY (`funcao_id`) REFERENCES `funcoes` (`id`);
ALTER TABLE `policiais` ADD CONSTRAINT `fk_policiais_posto` FOREIGN KEY (`posto_id`) REFERENCES `postos` (`id`);

-- --------------------------------------------------------

CREATE TABLE `policiais_drso_dias` (
  `id` int(11) NOT NULL,
  `policial_id` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `dia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `policiais_drso_dias` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uq_policial_dia` (`policial_id`,`ano`,`mes`,`dia`);
ALTER TABLE `policiais_drso_dias` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `policiais_drso_dias` ADD CONSTRAINT `fk_drso_dias_policial` FOREIGN KEY (`policial_id`) REFERENCES `policiais` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

CREATE TABLE `escalas_diarias` (
  `id` int(11) NOT NULL,
  `data_escala` date NOT NULL,
  `grupo` enum('Alpha','Bravo') NOT NULL,
  `status` enum('Rascunho','Publicada') NOT NULL DEFAULT 'Rascunho',
  `assinado_escalante` tinyint(1) NOT NULL DEFAULT 0,
  `escalante_id` int(11) DEFAULT NULL,
  `data_assinatura_escalante` datetime DEFAULT NULL,
  `assinado_comandante` tinyint(1) NOT NULL DEFAULT 0,
  `comandante_id` int(11) DEFAULT NULL,
  `data_assinatura_comandante` datetime DEFAULT NULL,
  `criado_por` int(11) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `escalas_diarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_data_escala` (`data_escala`),
  ADD KEY `fk_escalas_escalante` (`escalante_id`),
  ADD KEY `fk_escalas_comandante` (`comandante_id`),
  ADD KEY `fk_escalas_criador` (`criado_por`);
ALTER TABLE `escalas_diarias` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `escalas_diarias`
  ADD CONSTRAINT `fk_escalas_escalante` FOREIGN KEY (`escalante_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_escalas_comandante` FOREIGN KEY (`comandante_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_escalas_criador` FOREIGN KEY (`criado_por`) REFERENCES `usuarios` (`id`);

-- --------------------------------------------------------

CREATE TABLE `escala_equipes` (
  `id` int(11) NOT NULL,
  `escala_id` int(11) NOT NULL,
  `turno` enum('A','B') NOT NULL,
  `nome_equipe` varchar(50) NOT NULL,
  `viatura` varchar(30) DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_fim` time DEFAULT NULL,
  `area_atuacao` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `escala_equipes` ADD PRIMARY KEY (`id`), ADD KEY `fk_equipes_escala` (`escala_id`);
ALTER TABLE `escala_equipes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `escala_equipes` ADD CONSTRAINT `fk_equipes_escala` FOREIGN KEY (`escala_id`) REFERENCES `escalas_diarias` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

CREATE TABLE `escala_membros` (
  `id` int(11) NOT NULL,
  `equipe_id` int(11) NOT NULL,
  `policial_id` int(11) NOT NULL,
  `funcao_na_escala_id` int(11) NOT NULL,
  `ciente` tinyint(1) NOT NULL DEFAULT 0,
  `data_ciencia` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `escala_membros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_membros_equipe` (`equipe_id`),
  ADD KEY `fk_membros_policial` (`policial_id`),
  ADD KEY `fk_membros_funcao` (`funcao_na_escala_id`);
ALTER TABLE `escala_membros` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `escala_membros`
  ADD CONSTRAINT `fk_membros_equipe` FOREIGN KEY (`equipe_id`) REFERENCES `escala_equipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_membros_policial` FOREIGN KEY (`policial_id`) REFERENCES `policiais` (`id`),
  ADD CONSTRAINT `fk_membros_funcao` FOREIGN KEY (`funcao_na_escala_id`) REFERENCES `funcoes` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
