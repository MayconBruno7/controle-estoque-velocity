-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.2.0 - MySQL Community Server - GPL
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
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `statusRegistro` int DEFAULT '1' COMMENT '1 - Ativo    2 - Inativo',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela controle_estoque.fornecedor
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(144) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cnpj` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1 - ativo     2 - inativo',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela controle_estoque.funcionarios
CREATE TABLE IF NOT EXISTS `funcionarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `cpf` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `setor` int NOT NULL DEFAULT '0',
  `salario` decimal(20,6) NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1 - ativo     2 - inativo',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `setor_funcionarios` (`setor`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela controle_estoque.historico_produtos
CREATE TABLE IF NOT EXISTS `historico_produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_produtos` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `setor_id` int DEFAULT NULL,
  `nome_produtos` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `descricao_anterior` varchar(50) NOT NULL,
  `quantidade_anterior` int NOT NULL,
  `status_anterior` int NOT NULL,
  `statusItem_anterior` int DEFAULT NULL,
  `dataMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_setor_id` (`setor_id`),
  KEY `fk_historico_itens_itens` (`id_produtos`) USING BTREE,
  CONSTRAINT `fk_setor_id` FOREIGN KEY (`setor_id`) REFERENCES `setor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela controle_estoque.movimentacoes
CREATE TABLE IF NOT EXISTS `movimentacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_setor` int NOT NULL,
  `id_fornecedor` int NOT NULL,
  `statusRegistro` int NOT NULL COMMENT '1 - Ativo   2 - Inativo',
  `tipo` int NOT NULL COMMENT '1 - Entrada    2 - Saida',
  `motivo` varchar(100) NOT NULL,
  `data_pedido` date NOT NULL,
  `data_chegada` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_fornecedor` (`id_fornecedor`),
  KEY `id_setor` (`id_setor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela controle_estoque.movimentacoes_itens
CREATE TABLE IF NOT EXISTS `movimentacoes_itens` (
  `id_movimentacoes` int NOT NULL,
  `id_produtos` int NOT NULL,
  `quantidade` int NOT NULL DEFAULT '0',
  `valor` double(10,2) NOT NULL DEFAULT (0),
  KEY `id_movimentacoes` (`id_movimentacoes`),
  KEY `id_produtos` (`id_produtos`),
  KEY `quantidade` (`quantidade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela controle_estoque.produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `quantidade` int NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1=Ativo;2=Inativo',
  `condicao` int DEFAULT '1' COMMENT '1=Novo; 2=Usado',
  `dataMod` timestamp NULL DEFAULT NULL,
  `nome` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fornecedor` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_itens_fornecedor` (`fornecedor`) USING BTREE,
  KEY `fk_quantidade_item` (`quantidade`),
  CONSTRAINT `fk_itens_fornecedor` FOREIGN KEY (`fornecedor`) REFERENCES `fornecedor` (`id`),
  CONSTRAINT `FK_quantidade_itens` FOREIGN KEY (`quantidade`) REFERENCES `movimentacoes_itens` (`quantidade`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='Itens - Estoque';

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela controle_estoque.setor
CREATE TABLE IF NOT EXISTS `setor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `responsavel` int NOT NULL DEFAULT (0),
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1 - Ativo      2 - Inativo',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `responsavel_setor` (`responsavel`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
