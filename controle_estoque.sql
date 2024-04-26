-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 26/03/2024 às 19:09
-- Versão do servidor: 8.2.0
-- Versão do PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `controle_estoque`
--
CREATE DATABASE IF NOT EXISTS `controle_estoque` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `controle_estoque`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id_fornecedor` int NOT NULL AUTO_INCREMENT,
  `nome_fornecedor` varchar(144) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cnpj_fornecedor` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco_fornecedor` varchar(250) DEFAULT NULL,
  `telefone_fornecedor` varchar(20) DEFAULT NULL,
  `status_fornecedor` int NOT NULL DEFAULT '1' COMMENT '1 - ativo     2 - inativo',
  PRIMARY KEY (`id_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id_fornecedor`, `nome_fornecedor`, `cnpj_fornecedor`, `endereco_fornecedor`, `telefone_fornecedor`, `status_fornecedor`) VALUES
(1, 'MD COPIADORA LTDA', '44556350000104', ' JUIZ DE FORA, MG, CEP: 36032010, SANTA EFIGENIA, 855 ', '32999197525 ', 1),
(2, 'New Time', '1651561', 'Muriae safira', '14561656', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

DROP TABLE IF EXISTS `funcionarios`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id_funcionarios`, `nome_funcionarios`, `cpf_funcionarios`, `telefone_funcionarios`, `setor_funcionarios`, `salario_funcionario`, `status_funcionarios`) VALUES
(1, 'Maycon Bruno Gomes Luz de Morais', '123.456.789.01', '32999197525', 1, 1000.000000, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_itens`
--

DROP TABLE IF EXISTS `historico_itens`;
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
  KEY `fk_setor_id` (`setor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `historico_itens`
--

INSERT INTO `historico_itens` (`id`, `id_item`, `fornecedor_id`, `setor_id`, `nome_item`, `descricao_anterior`, `quantidade_anterior`, `statusRegistro_anterior`, `statusItem_anterior`, `dataMod`) VALUES
(42, 2, 1, 1, 'Ultimo teste', '<p>Ultimo teste de historico</p>', 20, 1, 2, '2024-03-26 18:45:38'),
(43, 2, 2, 1, 'Ultimo teste', '<p>Ultimo teste de historico</p>', 20, 1, 2, '2024-03-26 18:48:07'),
(44, 2, 2, 2, 'Ultimo teste', '<p>Ultimo teste de historico</p>', 20, 1, 2, '2024-03-26 19:05:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

DROP TABLE IF EXISTS `itens`;
CREATE TABLE IF NOT EXISTS `itens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `quantidade` int NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1=Ativo;2=Inativo',
  `statusItem` int DEFAULT '1' COMMENT '1=Novo; 2=Usado',
  `dataMod` timestamp NULL DEFAULT NULL,
  `nomeItem` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `setor_item` int DEFAULT NULL,
  `fornecedor_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_itens_setor` (`setor_item`),
  KEY `fk_itens_fornecedor` (`fornecedor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Itens - Estoque';

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `descricao`, `quantidade`, `statusRegistro`, `statusItem`, `dataMod`, `nomeItem`, `setor_item`, `fornecedor_id`) VALUES
(1, '<p>Cartucho de toner da impressora pantum</p>', 14, 1, 1, '2024-03-26 17:32:45', 'Cartucho de toner Pantum P2500W - Compatíveldsas', 2, 1),
(2, '<p>Ultimo teste de historico</p>', 20, 1, 2, '2024-03-26 19:05:12', 'Ultimo teste', 2, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `setor`
--

DROP TABLE IF EXISTS `setor`;
CREATE TABLE IF NOT EXISTS `setor` (
  `id_setor` int NOT NULL AUTO_INCREMENT,
  `nome_setor` varchar(100) NOT NULL,
  `responsavel_setor` varchar(50) NOT NULL DEFAULT '',
  `status_setor` int NOT NULL DEFAULT '1' COMMENT '1 - Ativo      2 - Inativo',
  PRIMARY KEY (`id_setor`),
  KEY `responsavel_setor` (`responsavel_setor`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `setor`
--

INSERT INTO `setor` (`id_setor`, `nome_setor`, `responsavel_setor`, `status_setor`) VALUES
(1, 'Administração ', 'Thamiris', 1),
(2, 'Saúde', 'Isonel', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
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

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nivel`, `statusRegistro`, `nome`, `senha`, `email`, `primeiroLogin`) VALUES
(1, '1', 1, 'Administrador', '$2y$10$nLGzXIjwVIqmR1Tos2iWke4AqKHEQj.5uCCFo1WgPMDxezOKmAcMC', 'administrador@gmail.com', 1);

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `historico_itens`
--
ALTER TABLE `historico_itens`
  ADD CONSTRAINT `fk_setor_id` FOREIGN KEY (`setor_id`) REFERENCES `setor` (`id_setor`);

--
-- Restrições para tabelas `itens`
--
ALTER TABLE `itens`
  ADD CONSTRAINT `fk_itens_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id_fornecedor`),
  ADD CONSTRAINT `fk_itens_setor` FOREIGN KEY (`setor_item`) REFERENCES `setor` (`id_setor`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
