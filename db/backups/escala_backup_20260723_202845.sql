-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: escala
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acessos`
--

DROP TABLE IF EXISTS `acessos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acessos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `chave` varchar(50) NOT NULL,
  `grupo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acessos`
--

LOCK TABLES `acessos` WRITE;
/*!40000 ALTER TABLE `acessos` DISABLE KEYS */;
INSERT INTO `acessos` VALUES (1,'Home','home',0),(2,'Configurações','configuracoes',0),(3,'Usuários','usuarios',3),(4,'Grupos de Acesso','grupos',3),(5,'Acessos','acessos',3),(6,'Policiais','policiais',1),(7,'Funções','funcoes',1),(8,'Criar Escala','escalas',2),(9,'Ver Escalas','escalas_listagem',2),(10,'Assinar como Escalante','assinar_escalante',2),(11,'Assinar como Comandante','assinar_comandante',2),(12,'Postos/Graduações','postos',1);
/*!40000 ALTER TABLE `acessos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arquivos`
--

DROP TABLE IF EXISTS `arquivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arquivos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(70) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `arquivo` varchar(100) NOT NULL,
  `data_cad` date NOT NULL,
  `registro` varchar(50) NOT NULL,
  `id_reg` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arquivos`
--

LOCK TABLES `arquivos` WRITE;
/*!40000 ALTER TABLE `arquivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `arquivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `icone` varchar(100) DEFAULT NULL,
  `logo_rel` varchar(100) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `ano_atual` year(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES ('3ª Cia / 1º BPRAIO - RAIO MESSEJANA','jeffersonjcl@gmail.com','(85) 99985-5584','Rua Paulo Setubal, 297, Messejana, Fortaleza-CE','','logo.png','icone.png','logo.jpg',1,0,0,0,'Não','','','Sim','','Não',12,'',0,'Não',NULL,'Não','','','','logo_painel.png','Sim','Sim','Sim','Não','Não',0,2026);
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escala_equipes`
--

DROP TABLE IF EXISTS `escala_equipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escala_equipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `escala_id` int(11) NOT NULL,
  `nome_equipe` varchar(50) NOT NULL,
  `viatura` varchar(30) DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_fim` time DEFAULT NULL,
  `area_atuacao` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_equipes_escala` (`escala_id`),
  CONSTRAINT `fk_equipes_escala` FOREIGN KEY (`escala_id`) REFERENCES `escalas_diarias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escala_equipes`
--

LOCK TABLES `escala_equipes` WRITE;
/*!40000 ALTER TABLE `escala_equipes` DISABLE KEYS */;
INSERT INTO `escala_equipes` VALUES (4,4,'Raio 01','',NULL,NULL,NULL),(5,5,'Raio 01','',NULL,NULL,NULL);
/*!40000 ALTER TABLE `escala_equipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escala_membros`
--

DROP TABLE IF EXISTS `escala_membros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escala_membros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equipe_id` int(11) NOT NULL,
  `policial_id` int(11) NOT NULL,
  `funcao_na_escala_id` int(11) NOT NULL,
  `ciente` tinyint(1) NOT NULL DEFAULT 0,
  `data_ciencia` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_membros_equipe` (`equipe_id`),
  KEY `fk_membros_policial` (`policial_id`),
  KEY `fk_membros_funcao` (`funcao_na_escala_id`),
  CONSTRAINT `fk_membros_equipe` FOREIGN KEY (`equipe_id`) REFERENCES `escala_equipes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_membros_funcao` FOREIGN KEY (`funcao_na_escala_id`) REFERENCES `funcoes` (`id`),
  CONSTRAINT `fk_membros_policial` FOREIGN KEY (`policial_id`) REFERENCES `policiais` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escala_membros`
--

LOCK TABLES `escala_membros` WRITE;
/*!40000 ALTER TABLE `escala_membros` DISABLE KEYS */;
INSERT INTO `escala_membros` VALUES (4,4,18,3,0,NULL),(5,4,56,6,0,NULL),(6,4,88,7,0,NULL),(7,4,75,8,0,NULL);
/*!40000 ALTER TABLE `escala_membros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escalas_diarias`
--

DROP TABLE IF EXISTS `escalas_diarias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escalas_diarias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_escala` date NOT NULL,
  `turno` enum('A','B') NOT NULL,
  `grupo` enum('Alpha','Bravo') DEFAULT NULL,
  `status` enum('Rascunho','Publicada') NOT NULL DEFAULT 'Rascunho',
  `assinado_escalante` tinyint(1) NOT NULL DEFAULT 0,
  `escalante_id` int(11) DEFAULT NULL,
  `data_assinatura_escalante` datetime DEFAULT NULL,
  `assinado_comandante` tinyint(1) NOT NULL DEFAULT 0,
  `comandante_id` int(11) DEFAULT NULL,
  `data_assinatura_comandante` datetime DEFAULT NULL,
  `criado_por` int(11) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_data_turno` (`data_escala`,`turno`),
  KEY `fk_escalas_escalante` (`escalante_id`),
  KEY `fk_escalas_comandante` (`comandante_id`),
  KEY `fk_escalas_criador` (`criado_por`),
  CONSTRAINT `fk_escalas_comandante` FOREIGN KEY (`comandante_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_escalas_criador` FOREIGN KEY (`criado_por`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_escalas_escalante` FOREIGN KEY (`escalante_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escalas_diarias`
--

LOCK TABLES `escalas_diarias` WRITE;
/*!40000 ALTER TABLE `escalas_diarias` DISABLE KEYS */;
INSERT INTO `escalas_diarias` VALUES (4,'2026-07-24','A','Alpha','Rascunho',0,NULL,NULL,0,NULL,NULL,4,'2026-07-23 23:24:49'),(5,'2026-07-24','B','Alpha','Rascunho',0,NULL,NULL,0,NULL,NULL,4,'2026-07-23 23:24:49');
/*!40000 ALTER TABLE `escalas_diarias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funcoes`
--

DROP TABLE IF EXISTS `funcoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funcoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_funcao_nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funcoes`
--

LOCK TABLES `funcoes` WRITE;
/*!40000 ALTER TABLE `funcoes` DISABLE KEYS */;
INSERT INTO `funcoes` VALUES (7,'1º Patrulheiro'),(10,'2º Homem'),(8,'2º Patrulheiro'),(2,'3º Homem'),(5,'5º Homem'),(12,'Auxiliar do P1'),(13,'Auxiliar do P4'),(3,'Comandante'),(19,'Escalante'),(4,'Garupa'),(6,'Motorista'),(11,'Permanente'),(18,'Reserva de Armamento'),(14,'SAI'),(1,'Sub Comandante');
/*!40000 ALTER TABLE `funcoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_acessos`
--

DROP TABLE IF EXISTS `grupo_acessos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupo_acessos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_acessos`
--

LOCK TABLES `grupo_acessos` WRITE;
/*!40000 ALTER TABLE `grupo_acessos` DISABLE KEYS */;
INSERT INTO `grupo_acessos` VALUES (1,'Efetivo'),(2,'Escala'),(3,'Administração');
/*!40000 ALTER TABLE `grupo_acessos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `policiais`
--

DROP TABLE IF EXISTS `policiais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `policiais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_policiais_funcao` (`funcao_id`),
  KEY `fk_policiais_posto` (`posto_id`),
  CONSTRAINT `fk_policiais_funcao` FOREIGN KEY (`funcao_id`) REFERENCES `funcoes` (`id`),
  CONSTRAINT `fk_policiais_posto` FOREIGN KEY (`posto_id`) REFERENCES `postos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `policiais`
--

LOCK TABLES `policiais` WRITE;
/*!40000 ALTER TABLE `policiais` DISABLE KEYS */;
INSERT INTO `policiais` VALUES (16,'GILSON','FRANCISCO GILSON DA SILVA FERREIRA','10367913',NULL,0,NULL,3,7,'','(85)99772-3783','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(17,'AVELINO','VAGNER AVELINO DA SILVA','11889611','Alpha',0,'A',3,6,'','(85)98602-5459','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(18,'ADRIANO','FRANCISCO ADRIANO GON?ALVES BRITO','1107541X','Alpha',0,'A',3,6,'','(85)98659-6685','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(19,'BEZERRA','MARCONDES BEZERRA COLARES','10932416',NULL,0,NULL,3,6,'','(85)98843-8575','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(20,'CAVALCANTE','FRANCISCO JOS? CAVALCANTE DOS SANTOS','1102291X',NULL,0,NULL,3,6,'','(85)98825-9860','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(21,'EDNEY','EDNEY DIAS GOMES','11886418','Alpha',0,'B',3,6,'','(85)99921-3274','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(22,'DANIEL','M?RCIO DANIEL SILVA DE SOUSA','12566913',NULL,0,NULL,12,6,'','(85)98564-8642','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(23,'GIRÃO','JOS? DOS SANTOS GIRÃO DE OLIVEIRA NETO','12739419',NULL,0,NULL,3,6,'','(85)99702-3015','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(24,'C ROCHA','CRISTIANO OLIVEIRA DA ROCHA','12741014',NULL,0,NULL,3,6,'','(85)99919-2327','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(25,'RONDINELLY','RONDINELLY FRAN?A LEIT?O','1343431X',NULL,0,NULL,3,5,'19607','(85)98918-9596','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(26,'UBIRATAN','UBIRATAN DE ARA?JO MENDES','13526117',NULL,0,NULL,3,6,'','(85)99664-7702','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(27,'LINCOLN','LINCOLN BARBOSA SANTOS','13483213',NULL,0,NULL,3,5,'20077','(85)98833-0806','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(28,'ROBERTO TAVARES','FRANCISCO ROBERTO TAVARES SABINO','13512310',NULL,0,NULL,3,5,'20092','(85)99695-4373','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(29,'THIAGO MATOS','THIAGO MATOS DE FREITAS','13511616',NULL,0,NULL,3,5,'20164','(85)98542-0952','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(30,'IVANIZIO','IVANIZIO RAIMUNDO DE SOUZA','1347771X',NULL,0,NULL,3,5,'20174','(85)98833-7805','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(31,'EVERARDO','EVERARDO DA SILVA MELO','13473412',NULL,0,NULL,3,6,'','(85)98672-5094','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(32,'R LIMA','LUIZ ROG?RIO DE LIMA SOUSA','1360021X','Bravo',0,'B',3,6,'','(85)99190-3131','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(33,'GONÇALVES','FRANCISCO PEREIRA GONÇALVES','13588619',NULL,0,NULL,3,5,'20941','(85)99933-4682','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(34,'HUDSON','HUDSON JOS? SANTOS DE SOUSA','13588910',NULL,0,NULL,3,5,'21034','(85)98804-8227','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(35,'NILO','FRANCISCO NILO CASTELO NETO','13636710',NULL,0,NULL,6,5,'21296','(85)98841-8830','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(36,'MOTA','CAMILO GOMES DA MOTA','30428714',NULL,0,NULL,2,3,'25570','(85)98962-0977','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(37,'ALMEIDA','ANT?NIO WAGNER OLIVEIRA DE ALMEIDA','30073614',NULL,0,NULL,3,4,'22341','(85)99222-3052','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(38,'PONTES','PAULO MAUR?CIO LOURO PONTES','3020381X',NULL,0,NULL,11,4,'22827','(85)98105-9842','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(39,'THIAGO VIEIRA','THIAGO VIEIRA DE AGUIAR SILVA','3023801X',NULL,0,NULL,12,4,'22845','(85)98884-1375','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(40,'JODELSON','JODELSON MONTEIRO DA SILVA','30239814',NULL,0,NULL,2,4,'22863','(85)98200-2110','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(41,'JONHNY','FRANCISCO JONHNY CARNEIRO RODRIGUES','30151410',NULL,0,NULL,3,4,'23036','(85)99938-8753','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(42,'ALCEU','FRANCISCO ALCEU PINTO PINHEIRO','30239113',NULL,0,NULL,3,4,'23124','(85)99101-5221','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(43,'C HENRIQUE','CARLOS HENRIQUE PINHO DOS SANTOS','30187318',NULL,0,NULL,3,4,'23428','(85)99871-5087','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(44,'ALEXANDRO','ALEXSANDRO DE LIMA CAVALCANTE','30196511',NULL,0,NULL,3,4,'23640','(85)99681-4100','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(45,'MOREIRA','JARDEL MOREIRA RODRIGUES','30142519',NULL,0,NULL,3,4,'23768','(85)98783-2166','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(46,'FERNANDES','DIEGO FERNANDES DA SILVA','3027721X',NULL,0,NULL,2,4,'24009','(85)99620-0323','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(47,'B FARIAS','WESLLEY BARROS FARIAS','30150317',NULL,0,NULL,3,4,'24043','(85)98562-9229','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(48,'F LIMA','F?BIO LIMA DA SILVA','30142012',NULL,0,NULL,12,4,'24080','(85)98698-9746','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(49,'R VICTOR','FRANCISCO RELRY VICTOR OLIVEIRA','30142411',NULL,0,NULL,12,4,'24082','(85)99727-0809','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(50,'GARCIAS','ANDERSON ALVES PONTES GARCIAS','30287312',NULL,0,NULL,11,4,'24195','(85)99105-3599','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(51,'PEDRO JÚNIOR','PEDRO SILVA COSTA JÚNIOR','30320514','Bravo',0,'B',2,3,'24488','(85)98666-3925','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(52,'AURELIANO','AURELIANO DA SILVA TEIXEIRA','30329511',NULL,0,NULL,2,4,'24578','(85)98844-1849','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(53,'MARCELO','FRANCISCO MARCELO DE FREITAS FILHO','30330315',NULL,0,NULL,3,4,'24586','(85)98708-0710','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(54,'ALVES','LU?Z PAULO ALVES DA SILVA','30381718',NULL,0,NULL,14,4,'24601','(85)98606-3127','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(55,'F VIANA','ANT?NIO FELIPE VIANA MATOS','30338715',NULL,0,NULL,10,4,'24670','(85)99818-8155','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(56,'A LIMA','ANDERSON DE SOUSA LIMA','30339010','Alpha',0,'A',3,4,'24673','(85)98820-7777','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(57,'ULISSES','ULISSES MOREIRA DE MENEZES J?NIOR','30341813',NULL,0,NULL,19,4,'24701','(85)98182-4680','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(58,'JEFFERSON','JEFFERSON COLARES LIMA','3034541X',NULL,0,NULL,13,4,'24737','(85)99985-5584','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(59,'MEDEIROS','JOS? ANDERSON DE ALMEIDA MEDEIROS','3035821X',NULL,0,NULL,10,4,'24865','(85)99274-9341','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(60,'ED?ZIO','JOS? ED?ZIO MARQUES DE LIMA','30383915',NULL,0,NULL,11,4,'25122','(85)99910-8333','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(61,'DOS SANTOS','RAFAEL SILVA DOS SANTOS','30386019',NULL,0,NULL,12,3,'25143','(85)99758-1347','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(62,'GEORGE','FRANCISCO GEORGE SANTANA PINHEIRO','30395611',NULL,0,NULL,10,3,'25239','(85)98641-8633','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(63,'SOUTO','ELISON BARBOSA SOUTO','30402111',NULL,0,NULL,3,3,'25304','(85)98729-7249','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(64,'ERIVAN','ANT?NIO ERIVAN JACINTO DA SILVA','30410211',NULL,0,NULL,10,4,'25385','(85)98878-9388','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(65,'CARLOS','ANT?NIO CARLOS GALDINO DA SILVA','30433513','Bravo',1,'A',2,4,'25618','(85)99266-3775','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(66,'REIS','FRANCISCO GERLANO DOS REIS SILVA','30440013',NULL,0,NULL,18,3,'25683','(85)98536-7121','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(67,'LIMA','FRANCISCO ADRIANO LIMA','30443012',NULL,0,NULL,2,4,'25713','(85)99267-7894','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(68,'WESLEY','FRANCISCO WESLEY LOPES DOS SANTOS','3044731X',NULL,0,NULL,2,3,'25756','(85)99929-9416','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(69,'WILKER','JOS? WILKER DA ROCHA OLIVEIRA','30485211',NULL,0,NULL,2,3,'26055','(85)98746-2071','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(70,'ISRAEL','ISRAEL DO NASCIMENTO COSTA','30508513',NULL,0,NULL,10,3,'26095','(85)98583-4580','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(71,'J DIAS','JULIANO BELARMINO DIAS','58717614',NULL,0,NULL,4,3,'26324','(85)99205-0034','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(72,'OLIVEIRA','JOSMAN DA SILVA OLIVEIRA','58740616',NULL,0,NULL,10,3,'26459','(85)98939-6980','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(73,'RODRIGUES','ROGEBERTO RODRIGUES RIBEIRO','58784516',NULL,0,NULL,10,2,'26796','(85)99739-9709','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(74,'W. FERNANDES','WELLINGTON ALEXANDRE FERNANDES','58748412',NULL,0,NULL,2,3,'26842','(85)98884-4954','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(75,'BENICIO','RONALDO BENICIO MELO','58792217','Alpha',0,'B',2,2,'26925','(85)99791-8487','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(76,'DUARTE','DANIEL DUARTE DA SILVA','58767913',NULL,0,NULL,10,2,'26955','(85)98645-3385','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(77,'HERBETH','HERBETH BARROS LIMA','58734411',NULL,0,NULL,4,3,'26988','(85)99296-9301','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(78,'SILVA','FL?VIO MOREIRA GOMES DA SILVA','30793714',NULL,0,NULL,4,2,'30325','(85)98808-9482','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(79,'DAVI VALE','DAVI GOMES DO VALE','58801615','Alpha',0,'B',4,2,'27165','(85)98660-9891','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(80,'S FILHO','JO?O SILVA OLIVEIRA FILHO','58788716',NULL,0,NULL,4,3,'27223','(85)99639-3730','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(81,'ARTHUR SANTOS','ARTHUR ARA?JO DOS SANTOS','58768413',NULL,0,NULL,2,3,'27249','(85)99431-3678','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(82,'GUILHERME','BRUNO BEZERRA GUILHERME','30501810',NULL,0,NULL,2,2,'27458','(85)99836-0281','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(83,'LAURIANO','?TALO LAURIANO DA COSTA','30562313',NULL,0,NULL,2,2,'27784','(85)98232-9444','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(84,'MENDON?A','THIAGO MENDON?A DA SILVA','30573013',NULL,0,NULL,4,2,'27986','(85)99786-9297','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(85,'BRASILEIRO','JO?O CL?UDIO DE LIMA BRASILEIRO','30020413',NULL,0,NULL,4,2,'28034','(85)99744-9219','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(86,'ANTÔNIO VICTOR','ANTÔNIO VICTOR MARTINS DE ALMEIDA','30578511',NULL,0,NULL,10,2,'28141','(85)98518-4270','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(87,'DA SILVA','MARCOS ALEXANDRE OLIVEIRA DA SILVA','30547519',NULL,0,NULL,4,2,'28194','(85)98915-6960','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(88,'ARAGÃO','FRANCISCO ANDR? ARAGÃO DA SILVA','30492412','Alpha',0,'B',10,2,'28197','(85)98886-8579','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(89,'ERDESSON','ERDESSON DIEGO FONTELES DA CUNHA','30009215',NULL,0,NULL,2,2,'28218','(85)98182-8344','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(90,'F.MONTEIRO','FRANCISCO IGOR SILVA MONTEIRO','30531213',NULL,0,NULL,4,2,'28240','(85)99932-4158','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(91,'AZEVEDO','FRANCISCO DE AZEVEDO FILHO','30564715',NULL,0,NULL,4,2,'28256','(85)98853-8872','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(92,'VIEIRA','RUDILENO VIEIRA ARRUDA','30487419',NULL,0,NULL,12,3,'28270','(85)98722-9953','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(93,'P.FILHO','ELIAS PEREIRA DE SOUSA FILHO','30599411',NULL,0,NULL,10,2,'28346','(85)99192-0045','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(94,'VINICIUS','VINICIUS ARA?JO BRAGA','30666712',NULL,0,NULL,2,2,'28416','(85)99646-5586','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(95,'R JÚNIOR','CILFARNEI ROQUE DO NASCIMENTO JÚNIOR','30592417',NULL,0,NULL,4,2,'28440','(85)92182-7219','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(96,'ALVES','ANTONIO HERBERT ALVES GONDIM','30586611',NULL,0,NULL,2,2,'28473','(85)99927-5473','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(97,'MENDES','TIAGO MENDES GADELHA','30664515',NULL,0,NULL,2,2,'28721','(85)98706-0709','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(98,'HELCIO','HELCIO GERALDO DE OLIVEIRA FILHO','30618513',NULL,0,NULL,4,2,'28889','(85)98756-0343','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(99,'SAMPAIO','ANTONIO JORDANIO OLIVEIRA SAMPAIO','30586913',NULL,0,NULL,4,2,'28987','(85)98633-8272','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(100,'XIMENES','EDNALDO XIMENES DE OLIVEIRA','30598318',NULL,0,NULL,4,2,'29015','(85)99609-2257','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(101,'W BATISTA','WASHINGTON BATISTA DA SILVA','30669118',NULL,0,NULL,4,2,'29038','(85)98775-8841','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(102,'G MATOS','GLAUBER MATOS SILVA','30617010',NULL,0,NULL,2,2,'29048','(85)99110-7645','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(103,'CALIXTO','JAMERSON BEZERRA CALIXTO','3062341X',NULL,0,NULL,2,2,'29058','(85)99214-9036','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(104,'MONTEIRO','G?LISON OLIVEIRA MONTEIRO','30615417',NULL,0,NULL,13,2,'29121','(85)98836-7835','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(105,'HOLANDA','ANDERSON TEODOSIO HOLANDA','30704517',NULL,0,NULL,4,2,'29366','(85)99131-0303','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(106,'DOS ANJOS','JOAO PAULO DOS ANJOS','30689917',NULL,0,NULL,2,2,'29521','(85)99697-5921','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(107,'R SOUSA','ROBERTO DE SOUSA','30682017','Alpha',0,'B',2,2,'29587','(85)99626-6546','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(108,'W GUIMARÃES','WAGNER GUIMARÃES PONTES','30691318',NULL,0,NULL,2,2,'29890','(85)99192-6189','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(109,'DIAS','RAFAEL DIAS DOS SANTOS','30750810','Bravo',0,'A',4,2,'29938','(85)98808-3911','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(110,'CLAUDIO','CLAUDIO DIEGO DE LIMA LEITE','30772911',NULL,0,NULL,4,2,'29997','(85)99917-9724','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(111,'GONÇALVES','THIBERIO ROCHA GONÇALVES','30835719',NULL,0,NULL,4,2,'30430','(85)98769-0480','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(112,'JENILSON','JENILSON VIANA DA COSTA','3082301X',NULL,0,NULL,4,2,'30565','(85)98778-7601','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(113,'R COSTA','ANT?NIO ROBSON COSTA DA SILVA','30812212',NULL,0,NULL,4,2,'30571','(85)98791-9208','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(114,'DE BRITO','ISRAEL DOS SANTOS DE BRITO','30873556',NULL,0,NULL,4,2,'30779','(85)99720-9573','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(115,'JUNIOR','FRANCISCO RAIMUNDO MORAIS DA SILVA J?NIOR','30867785',NULL,0,NULL,4,2,'31188','(85)98608-1479','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(116,'BRUNO','FRANCISCO BRUNO FERREIRA DA SILVA','30868315',NULL,0,NULL,4,2,'31298','(85)99942-0138','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(117,'BARRETO','ALEXANDRE ARAUJO BARRETO DE LIMA','30877705',NULL,0,NULL,2,2,'31423','(85)99686-9038','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(118,'BEZERRA','THIAGO GOMES BEZERRA','30872657',NULL,0,NULL,4,2,'31483','(85)98585-9475','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(119,'KLEDSON','KLEDSON AZEVEDO DE CARVALHO','30869893',NULL,0,NULL,2,2,'31508','(85)99787-0601','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(120,'R ANDRADE','ROBSON VIEIRA DE ANDRADE','30872401',NULL,0,NULL,4,2,'31723','(85)98928-1764','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(121,'MARCOS VINICIUS','MARCOS VINICIUS OLIVEIRA SOUZA','30873467',NULL,0,NULL,2,2,'31804','(85)98764-4532','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(122,'THIAGO','THIAGO SOARES DA SILVA','30873718',NULL,0,NULL,4,2,'31832','(85)99712-8590','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(123,'RAILSON','RAILSON BERNARDO DE SOUSA CAMPOS','30871545',NULL,0,NULL,4,2,'31868','(85)99238-0043','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(124,'AURÉLIO','MARCOS AURÉLIO FREITAS DA SILVA','30873254',NULL,0,NULL,2,2,'31996','(85)99749-3110','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(125,'LEAL','BENJAMIN LEAL MARQUES','30893360',NULL,0,NULL,2,2,'32258','(85)99866-9850','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(126,'COSMO','DOUGLAS DE OLIVEIRA COSMO','30890450',NULL,0,NULL,2,2,'32287','(85)99803-1813','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(127,'MESQUITA','FRANCISCO SINVAL DE MESQUITA JUNIOR','3088647X',NULL,0,NULL,4,2,'32336','(85)98502-5775','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(128,'GUSTAVO','GUSTAVO LIMA LOPES','30888472',NULL,0,NULL,2,2,'32577','(85)99795-5561','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(129,'DOUGLAS','DOUGLAS FERREIRA DE ARA?JO SILVA','30890469',NULL,0,NULL,4,2,'32641','(85)98177-5203','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(130,'TAFAREL','TAFAREL ABREU DE MARANGUAPE','3088762X',NULL,0,NULL,14,2,'32732','(85)98844-2044','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(131,'RODRIGO','RODRIGO DOS SANTOS SILVA','3088583X',NULL,0,NULL,2,2,'32865','(85)98758-9668','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(132,'COSTA LIMA','YURI COSTA DE LIMA','3088972X',NULL,0,NULL,4,2,'32931','(85)99133-0334','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(133,'CELSO','CELSO ADRIANO DE SOUSA RODRIGUES','30880617',NULL,0,NULL,4,2,'32985','(85)98870-8398','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(134,'FERREIRA','GLEBER GOMES DA SILVA FERREIRA','30888561',NULL,0,NULL,10,2,'33223','(85)99623-0485','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(135,'OLÍVIO','FRANCISCO OLÍVIO MARTINS DOS SANTOS J?NIOR','30906985',NULL,0,NULL,2,2,'34109','(85)99749-5540','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(136,'FELIPE','MARCOS FELIPE DE OLIVEIRA GOMES','30404416',NULL,0,NULL,10,4,'25327','(85)98760-6808','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(137,'SÍLVIO','S?LVIO GABRIEL PONTES ALVES','30123816',NULL,0,NULL,3,4,'22651','(85)99901-5533','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(138,'Z BARROS','ZENOBIO FELIZARDO CHAVES BARROS FILHO','30887271',NULL,0,NULL,4,2,'33121','(85)99940-8094','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(139,'DANIEL','FRANCISCO DANIEL LIMA DIAS','30887220',NULL,0,NULL,4,1,'33172','(85)99707-4434','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(140,'SHERLIAN','SHERLIAN RODRIGUES DA SILVA','30873297',NULL,0,NULL,4,2,'31705','(85)99615-6046','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(141,'MAIA','GERARDO DE SOUSA MAIA FILHO','84397784',NULL,0,NULL,3,8,'','(85)99209-8557','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(142,'FELIPE LIMA','DUAN FELIPE LIMA TEIXEIRA','30210719',NULL,0,NULL,11,4,'22854','(85)98625-3565','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(143,'RAFAEL SILVA','FRANCISCO RAFAEL DA SILVA','30170512','Bravo',0,'B',10,4,'23573','(85)98660-4759','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(144,'GOMES','BRUNO ARA?JO GOMES','30906748',NULL,0,NULL,4,2,'33593','(85)99708-3852','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(145,'DAVI SILVA','DAVI DA SILVA BARBOSA','30893689',NULL,0,NULL,4,2,'33066','(85)99273-3003','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(146,'MAIA','RAFAEL RODRIGUES MAIA','30554612',NULL,0,NULL,10,2,'27581','(85)98596-5167','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(147,'DAMASCENO','RODRIGO DAMASCENO BARBOSA','30917553',NULL,0,NULL,4,1,'34826','(85)99686-9418','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(148,'HEMANUEL','FRANCISCO HEMANUEL DO NASCIMENTO MOTA ARA?JO','3057091X',NULL,0,NULL,11,2,'28129','(85)98870-5765','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(149,'JARDEL','JARDEL MOREIRA OLIVEIRA','30881907',NULL,0,NULL,4,2,'32127','(00)00000-0000','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(150,'C SOUZA','JOTANOEL TOME CANABARRO DE SOUZA','58794910',NULL,0,NULL,14,2,'26710','(85)99799-5748','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(151,'SANTOS','LUCAS LIMA DOS SANTOS','30884809',NULL,0,NULL,4,1,'32494','(85)99225-5197','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51'),(152,'MAIA','JO?O VICTOR DE JESUS MAIA','30011457',NULL,0,NULL,2,1,'36405','(85)98738-2839','sem-foto.jpg',1,NULL,NULL,NULL,NULL,'2026-07-23 22:02:51');
/*!40000 ALTER TABLE `policiais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `policiais_drso_dias`
--

DROP TABLE IF EXISTS `policiais_drso_dias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `policiais_drso_dias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policial_id` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_policial_dia` (`policial_id`,`ano`,`mes`,`dia`),
  CONSTRAINT `fk_drso_dias_policial` FOREIGN KEY (`policial_id`) REFERENCES `policiais` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `policiais_drso_dias`
--

LOCK TABLES `policiais_drso_dias` WRITE;
/*!40000 ALTER TABLE `policiais_drso_dias` DISABLE KEYS */;
INSERT INTO `policiais_drso_dias` VALUES (14,65,2026,7,24);
/*!40000 ALTER TABLE `policiais_drso_dias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postos`
--

DROP TABLE IF EXISTS `postos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_posto_nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postos`
--

LOCK TABLES `postos` WRITE;
/*!40000 ALTER TABLE `postos` DISABLE KEYS */;
INSERT INTO `postos` VALUES (5,'1º Sargento PM'),(8,'1º Tenente PM'),(4,'2º Sargento PM'),(7,'2º Tenente PM'),(3,'3º Sargento PM'),(2,'Cabo PM'),(9,'Capitão PM'),(12,'Coronel PM'),(10,'Major PM'),(1,'Soldado PM'),(6,'SubTenente PM'),(11,'Tenente Coronel PM');
/*!40000 ALTER TABLE `postos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `token` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Jefferson Lima','jeffersonjcl@gmail.com','123','$2y$10$1WhObU0vlmPlDOLERth3juDhPSbTxXxOHp2es0m3cz4omjkayz0U6','Administrador','Sim','(85) 99985-5584','Rua E, 329, Cajazeiras, Fortaleza-CE','sem-foto.jpg','2026-07-06',NULL,0,NULL,NULL),(3,'Sd PM Garupa','garupa@gmail.com','123','$2y$10$sE8/NP4giHWPTSLk7RJ/3OG2w273j.rAwAL6GU7XyCmvc46JbOQGi','Policial','Sim','(85) 98855-5444',NULL,'sem-foto.jpg','2026-07-17',NULL,4,NULL,NULL),(4,'Ulisses Júnior','cgkdojo@gmail.com','123','$2y$10$pUWf.yuFyOxZxTIrk6gs2eWLtEOPhuPyc8ZwgB4veaggt7VClW9Ky','Escalante','Sim','(85) 98182-4680','','sem-foto.jpg','2026-07-23',NULL,0,NULL,NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_permissoes`
--

DROP TABLE IF EXISTS `usuarios_permissoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios_permissoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` int(11) NOT NULL,
  `permissao` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_permissoes`
--

LOCK TABLES `usuarios_permissoes` WRITE;
/*!40000 ALTER TABLE `usuarios_permissoes` DISABLE KEYS */;
INSERT INTO `usuarios_permissoes` VALUES (2,4,1),(3,4,2),(4,4,3),(7,4,6),(8,4,7),(9,4,8),(10,4,9),(11,4,10),(13,4,12);
/*!40000 ALTER TABLE `usuarios_permissoes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-23 20:28:45
