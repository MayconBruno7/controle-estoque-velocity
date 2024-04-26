-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.31 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para controle_estoque
CREATE DATABASE IF NOT EXISTS `controle_estoque` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `controle_estoque`;

-- Copiando estrutura para tabela controle_estoque.cargo
CREATE TABLE IF NOT EXISTS `cargo` (
  `id_cargo` int NOT NULL AUTO_INCREMENT,
  `nome_cargo` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_cargo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.cargo: 0 rows
/*!40000 ALTER TABLE `cargo` DISABLE KEYS */;
/*!40000 ALTER TABLE `cargo` ENABLE KEYS */;

-- Copiando estrutura para tabela controle_estoque.fornecedor
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id_fornecedor` int NOT NULL AUTO_INCREMENT,
  `nome_fornecedor` varchar(144) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cnpj_fornecedor` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco_fornecedor` varchar(250) DEFAULT NULL,
  `telefone_fornecedor` varchar(20) DEFAULT NULL,
  `status_fornecedor` int NOT NULL DEFAULT '1' COMMENT '1 - ativo     2 - inativo',
  PRIMARY KEY (`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.fornecedor: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela controle_estoque.funcionarios
CREATE TABLE IF NOT EXISTS `funcionarios` (
  `id_funcionarios` int NOT NULL AUTO_INCREMENT,
  `nome_funcionarios` varchar(80) NOT NULL DEFAULT '0',
  `cpf_funcionarios` varchar(20) NOT NULL DEFAULT '0',
  `telefone_funcionarios` varchar(20) DEFAULT NULL,
  `setor_funcionarios` int NOT NULL DEFAULT '0',
  `salario_funcionario` decimal(20,6) NOT NULL,
  `status_funcionarios` int NOT NULL DEFAULT '1' COMMENT '1 - ativo     2 - inativo',
  PRIMARY KEY (`id_funcionarios`),
  KEY `setor_funcionarios` (`setor_funcionarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.funcionarios: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela controle_estoque.historico_itens
CREATE TABLE IF NOT EXISTS `historico_itens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_item` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `setor_id` int DEFAULT NULL,
  `nome_item` varchar(50) DEFAULT NULL,
  `descricao_anterior` varchar(50) NOT NULL,
  `quantidade_anterior` int NOT NULL,
  `statusRegistro_anterior` int NOT NULL,
  `statusItem_anterior` int DEFAULT NULL,
  `dataMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_historico_itens_itens` (`id_item`),
  KEY `fk_setor_id` (`setor_id`),
  CONSTRAINT `fk_setor_id` FOREIGN KEY (`setor_id`) REFERENCES `setor` (`id_setor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.historico_itens: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela controle_estoque.itens
CREATE TABLE IF NOT EXISTS `itens` (
  `id_itens` int NOT NULL AUTO_INCREMENT,
  `descricao_itens` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `quantidade_itens` int NOT NULL,
  `statusRegistro_itens` int NOT NULL DEFAULT '1' COMMENT '1=Ativo;2=Inativo',
  `statusItem_itens` int DEFAULT '1' COMMENT '1=Novo; 2=Usado',
  `dataMod_itens` timestamp NULL DEFAULT NULL,
  `nome_itens` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `setor_itens` int DEFAULT NULL,
  `fornecedor_id` int DEFAULT NULL,
  PRIMARY KEY (`id_itens`) USING BTREE,
  KEY `fk_itens_fornecedor` (`fornecedor_id`),
  KEY `fk_itens_setor` (`setor_itens`) USING BTREE,
  CONSTRAINT `fk_itens_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id_fornecedor`),
  CONSTRAINT `fk_itens_setor` FOREIGN KEY (`setor_itens`) REFERENCES `setor` (`id_setor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Itens - Estoque';

-- Copiando dados para a tabela controle_estoque.itens: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela controle_estoque.setor
CREATE TABLE IF NOT EXISTS `setor` (
  `id_setor` int NOT NULL AUTO_INCREMENT,
  `nome_setor` varchar(100) NOT NULL,
  `responsavel_setor` varchar(50) NOT NULL DEFAULT '',
  `status_setor` int NOT NULL DEFAULT '1' COMMENT '1 - Ativo      2 - Inativo',
  PRIMARY KEY (`id_setor`),
  KEY `responsavel_setor` (`responsavel_setor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.setor: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela controle_estoque.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nivel` varchar(150) NOT NULL,
  `statusRegistro` int NOT NULL,
  `nome` varchar(150) NOT NULL,
  `senha` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(150) NOT NULL,
  `primeiroLogin` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.usuario: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
