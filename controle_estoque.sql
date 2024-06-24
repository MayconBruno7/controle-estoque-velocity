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
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `statusRegistro` int DEFAULT '1' COMMENT '1 - Ativo    2 - Inativo',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.cargo: ~0 rows (aproximadamente)
DELETE FROM `cargo`;

-- Copiando estrutura para tabela controle_estoque.cidade
CREATE TABLE IF NOT EXISTS `cidade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `codigo_municipio` varchar(10) NOT NULL,
  `estado` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `estado` (`estado`),
  CONSTRAINT `id_estado` FOREIGN KEY (`estado`) REFERENCES `estado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.cidade: ~0 rows (aproximadamente)
DELETE FROM `cidade`;

-- Copiando estrutura para tabela controle_estoque.estado
CREATE TABLE IF NOT EXISTS `estado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `sigla` varchar(2) NOT NULL,
  `regiao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.estado: ~0 rows (aproximadamente)
DELETE FROM `estado`;

-- Copiando estrutura para tabela controle_estoque.fornecedor
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(144) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cnpj` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cidade` int DEFAULT NULL,
  `estado` int DEFAULT NULL,
  `bairro` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `numero` varchar(250) DEFAULT NULL,
  `telefone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1 - ativo     2 - inativo',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `cidade` (`cidade`),
  KEY `estado` (`estado`),
  CONSTRAINT `cidade` FOREIGN KEY (`cidade`) REFERENCES `cidade` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `estado` FOREIGN KEY (`estado`) REFERENCES `estado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.fornecedor: ~0 rows (aproximadamente)
DELETE FROM `fornecedor`;

-- Copiando estrutura para tabela controle_estoque.funcionario
CREATE TABLE IF NOT EXISTS `funcionario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `cpf` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `setor` int DEFAULT '0',
  `salario` decimal(20,6) NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1 - ativo     2 - inativo',
  `cargo` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `setor_funcionarios` (`setor`) USING BTREE,
  KEY `cargo` (`cargo`),
  CONSTRAINT `id_cargo` FOREIGN KEY (`cargo`) REFERENCES `cargo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_setor` FOREIGN KEY (`setor`) REFERENCES `setor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.funcionario: ~0 rows (aproximadamente)
DELETE FROM `funcionario`;

-- Copiando estrutura para tabela controle_estoque.historico_produto
CREATE TABLE IF NOT EXISTS `historico_produto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_produtos` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `nome_produtos` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descricao_anterior` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `quantidade_anterior` int NOT NULL,
  `status_anterior` int NOT NULL,
  `statusItem_anterior` int NOT NULL,
  `dataMod` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_historico_itens_itens` (`id_produtos`) USING BTREE,
  KEY `fornecedor_id` (`fornecedor_id`),
  CONSTRAINT `fk_id_produto` FOREIGN KEY (`id_produtos`) REFERENCES `produto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.historico_produto: ~0 rows (aproximadamente)
DELETE FROM `historico_produto`;

-- Copiando estrutura para tabela controle_estoque.movimentacao
CREATE TABLE IF NOT EXISTS `movimentacao` (
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
  KEY `id_setor` (`id_setor`),
  CONSTRAINT `fk_id_fornecedor` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_id_setor` FOREIGN KEY (`id_setor`) REFERENCES `setor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.movimentacao: ~0 rows (aproximadamente)
DELETE FROM `movimentacao`;

-- Copiando estrutura para tabela controle_estoque.movimentacao_item
CREATE TABLE IF NOT EXISTS `movimentacao_item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_movimentacoes` int NOT NULL,
  `id_produtos` int NOT NULL,
  `quantidade` int DEFAULT NULL,
  `valor` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `id_movimentacoes` (`id_movimentacoes`) USING BTREE,
  KEY `id_produtos` (`id_produtos`) USING BTREE,
  CONSTRAINT `fk_id_movimentacoes` FOREIGN KEY (`id_movimentacoes`) REFERENCES `movimentacao` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_id_produtos` FOREIGN KEY (`id_produtos`) REFERENCES `produto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.movimentacao_item: ~0 rows (aproximadamente)
DELETE FROM `movimentacao_item`;

-- Copiando estrutura para tabela controle_estoque.produto
CREATE TABLE IF NOT EXISTS `produto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `quantidade` int DEFAULT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1=Ativo;2=Inativo',
  `condicao` int DEFAULT '1' COMMENT '1=Novo; 2=Usado',
  `dataMod` timestamp NULL DEFAULT NULL,
  `nome` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fornecedor` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_itens_fornecedor` (`fornecedor`) USING BTREE,
  CONSTRAINT `id_fornecedor` FOREIGN KEY (`fornecedor`) REFERENCES `fornecedor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Itens - Estoque';

-- Copiando dados para a tabela controle_estoque.produto: ~0 rows (aproximadamente)
DELETE FROM `produto`;

-- Copiando estrutura para tabela controle_estoque.setor
CREATE TABLE IF NOT EXISTS `setor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `responsavel` int DEFAULT '0',
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1 - Ativo      2 - Inativo',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `responsavel_setor` (`responsavel`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.setor: ~0 rows (aproximadamente)
DELETE FROM `setor`;

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
DELETE FROM `usuario`;

-- Copiando estrutura para tabela controle_estoque.usuariorecuperasenha
CREATE TABLE IF NOT EXISTS `usuariorecuperasenha` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `chave` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1=Ativo;2=Inativo',
  `created_at` datetime NOT NULL DEFAULT (concat(curdate(),_utf8mb4' ',curtime())),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK1_usuariorecuperacaosenha` (`usuario_id`) USING BTREE,
  CONSTRAINT `FK1_usuariorecuperacaosenha` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela controle_estoque.usuariorecuperasenha: ~0 rows (aproximadamente)
DELETE FROM `usuariorecuperasenha`;

-- Copiando estrutura para trigger controle_estoque.tg_Delete_BloqueiaMovimentacaoFinalSemana
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `tg_Delete_BloqueiaMovimentacaoFinalSemana` BEFORE DELETE ON `movimentacao` FOR EACH ROW BEGIN
   DECLARE dia_semana INT;
   SET dia_semana = DAYOFWEEK(CURDATE());

   IF dia_semana IN (1, 7) THEN
      SIGNAL SQLSTATE '45000' 
      SET MESSAGE_TEXT = 'Operações não são permitidas no final de semana';
   END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Copiando estrutura para trigger controle_estoque.tg_Insert_BloqueiaMovimentacaoFinalSemana
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `tg_Insert_BloqueiaMovimentacaoFinalSemana` BEFORE INSERT ON `movimentacao` FOR EACH ROW BEGIN
   DECLARE dia_semana INT;
   SET dia_semana = DAYOFWEEK(CURDATE());

   IF dia_semana IN (1, 7) THEN
      SIGNAL SQLSTATE '45000' 
      SET MESSAGE_TEXT = 'Operações não são permitidas no final de semana';
   END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Copiando estrutura para trigger controle_estoque.tg_Update_BloqueiaMovimentacaoFinalSemana
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `tg_Update_BloqueiaMovimentacaoFinalSemana` BEFORE UPDATE ON `movimentacao` FOR EACH ROW BEGIN
   DECLARE dia_semana INT;
   SET dia_semana = DAYOFWEEK(CURDATE());

   IF dia_semana IN (1, 7) THEN
      SIGNAL SQLSTATE '45000' 
      SET MESSAGE_TEXT = 'Operações não são permitidas no final de semana';
   END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
