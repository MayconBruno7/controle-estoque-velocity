-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 03/05/2024 às 18:50
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
-- Estrutura para tabela `cargo`
--

DROP TABLE IF EXISTS `cargo`;
CREATE TABLE IF NOT EXISTS `cargo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `statusRegistro` int DEFAULT '1' COMMENT '1 - Ativo    2 - Inativo',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `cargo`
--

INSERT INTO `cargo` (`id`, `nome`, `statusRegistro`) VALUES
(1, 'Analista de Sistemas', 1),
(2, 'Programador', 1),
(3, 'Técnico de Informatica', 1),
(4, 'Suporte Tecnico', 1),
(5, 'Desenvolvedor Web', 1),
(6, 'Gerente de TI', 1),
(7, 'DBA', 1),
(8, 'Analista de Suporte', 1),
(9, 'Arquiteto de Software', 1),
(10, 'Engenheiro de Software', 1),
(11, 'Analista de Seguranca', 1),
(12, 'Analista de Qualidade', 1),
(13, 'Scrum Master', 1),
(14, 'Product Owner', 1),
(15, 'UX Designer', 1),
(16, 'UI Designer', 1),
(17, 'DevOps Engineer', 1),
(18, 'Data Scientist', 1),
(19, 'Analista de BI', 1),
(20, 'Analista de Testes', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(144) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cnpj` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cidade` varchar(250) DEFAULT NULL,
  `estado` varchar(250) DEFAULT NULL,
  `bairro` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `numero` varchar(250) DEFAULT NULL,
  `telefone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1 - ativo     2 - inativo',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id`, `nome`, `cnpj`, `endereco`, `cidade`, `estado`, `bairro`, `numero`, `telefone`, `statusRegistro`) VALUES
(1, 'Tech Supplier', '12345678000101', 'Rua das Tecnologias', 'Tecnopolis', 'Tecnolandia', 'Tech Park', '123', '00123456781', 1),
(2, 'InfoParts', '23456789000102', 'Avenida dos Componentes', 'Circuit City', 'Eletronville', 'Digital District', '456', '0023456789', 1),
(3, 'WebTech Solutions', '34567890000103', 'Rua dos Servidores', 'Webtown', 'Netland', 'Internet Ave', '789', '0034567890', 1),
(4, 'DataNet Corp', '45678901000104', 'Avenida da Rede', 'Dataspace', 'Cloud County', 'Cyber Center', '987', '0045678901', 1),
(5, 'SoftEdge Ltda', '56789012000105', 'Rua do Software', 'Coded City', 'Developerville', 'Code Central', '654', '0056789012', 1),
(6, 'BitWorks', '67890123000106', 'Avenida dos Bits', 'Bitburg', 'Digitaland', 'Binary Street', '321', '0067890123', 1),
(7, 'Microtech', '78901234000107', 'Rua das Micros', 'Microville', 'Chipland', 'Micro Center', '147', '0078901234', 1),
(8, 'TechHive', '89012345000108', 'Avenida da Tecnologia', 'Tech City', 'Infotech', 'Geek Plaza', '258', '0089012345', 1),
(9, 'CodeCrafters', '90123456000109', 'Rua do Codigo', 'Codeville', 'Scriptland', 'Syntax Lane', '369', '0090123456', 1),
(10, 'Logicware', '01234567000110', 'Avenida da Logica', 'Logic City', 'Algoland', 'Logic Square', '159', '0012345678', 1),
(11, 'CyberSolutions', '12345678000111', 'Rua da Ciberseguranca', 'Cyberburg', 'Cryptoland', 'Firewall Blvd', '357', '0012345678', 1),
(12, 'DataSphere', '23456789000112', 'Avenida dos Dados', 'Datascape', 'Infotown', 'Cloud Street', '753', '0023456789', 1),
(13, 'Connectech', '34567890000113', 'Rua da Conexao', 'Connectville', 'Interland', 'Link Lane', '159', '0034567890', 1),
(14, 'Smart Systems', '45678901000114', 'Avenida da Tecnologia', 'Tech City', 'Infotech', 'Geek Plaza', '258', '0045678901', 1),
(15, 'Cybermind', '56789012000115', 'Rua da Seguranca Digital', 'Digitopolis', 'Cryptoland', 'Firewall Street', '963', '0056789012', 1),
(16, 'TechGenius', '67890123000116', 'Avenida dos Gadgets', 'Gadgetown', 'Digitaland', 'Gizmo Avenue', '753', '0067890123', 1),
(17, 'SoftSolutions', '78901234000117', 'Rua da Programacao', 'Codecity', 'Developerville', 'Code Street', '159', '0078901234', 1),
(18, 'DataCloud', '89012345000118', 'Avenida da Nuvem', 'Cloudville', 'Infotown', 'Cloud Road', '357', '0089012345', 1),
(19, 'InfoTech', '90123456000119', 'Rua da Informatica', 'Infotown', 'Techland', 'Tech Street', '951', '0090123456', 1),
(20, 'TechWorld', '01234567000120', 'Avenida da Tecnologia', 'Tech City', 'Infotech', 'Tech Avenue', '357', '0012345678', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

DROP TABLE IF EXISTS `funcionarios`;
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `cpf`, `telefone`, `setor`, `salario`, `statusRegistro`) VALUES
(1, 'Joao Silva', '12345678901', '0012345678', 1, 3000.000000, 1),
(2, 'Maria Santos', '23456789012', '0023456789', 2, 3500.000000, 1),
(3, 'Pedro Oliveira', '34567890123', '0034567890', 3, 3200.000000, 1),
(4, 'Ana Pereira', '45678901234', '0045678901', 4, 2800.000000, 1),
(5, 'Carlos Souza', '56789012345', '0056789012', 5, 4000.000000, 1),
(6, 'Juliana Santos', '67890123456', '0067890123', 1, 3800.000000, 1),
(7, 'Lucas Lima', '78901234567', '0078901234', 4, 3300.000000, 1),
(8, 'Camila Oliveira', '89012345678', '0089012345', 4, 3100.000000, 1),
(9, 'Marcos Silva', '90123456789', '0090123456', 9, 3700.000000, 1),
(10, 'Patricia Costa', '01234567890', '0012345678', 10, 3400.000000, 1),
(11, 'Rafaela Nunes', '12345678901', '0023456789', 11, 3900.000000, 1),
(12, 'Fernando Souza', '23456789012', '0034567890', 2, 3600.000000, 1),
(13, 'Gabriel Lima', '34567890123', '0045678901', 1, 3200.000000, 1),
(14, 'Ana Claudia', '45678901234', '0056789012', 2, 2800.000000, 1),
(15, 'Pedro Henrique', '56789012345', '0067890123', 15, 4000.000000, 1),
(16, 'Marcela Oliveira', '67890123456', '0078901234', 16, 3300.000000, 1),
(17, 'Lucas Santos', '78901234567', '0089012345', 17, 3100.000000, 1),
(18, 'Aline Costa', '89012345678', '0090123456', 3, 3700.000000, 1),
(19, 'Rodrigo Silva', '90123456789', '0012345678', 19, 3400.000000, 1),
(20, 'Vanessa Nunes', '01234567890', '0023456789', 20, 3900.000000, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_produtos`
--

DROP TABLE IF EXISTS `historico_produtos`;
CREATE TABLE IF NOT EXISTS `historico_produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_produtos` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `setor_id` int DEFAULT NULL,
  `nome_produtos` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descricao_anterior` varchar(50) NOT NULL,
  `quantidade_anterior` int DEFAULT NULL,
  `status_anterior` int NOT NULL,
  `statusItem_anterior` int NOT NULL,
  `dataMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_setor_id` (`setor_id`),
  KEY `fk_historico_itens_itens` (`id_produtos`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `historico_produtos`
--

INSERT INTO `historico_produtos` (`id`, `id_produtos`, `fornecedor_id`, `setor_id`, `nome_produtos`, `descricao_anterior`, `quantidade_anterior`, `status_anterior`, `statusItem_anterior`, `dataMod`) VALUES
(1, 1, 1, NULL, 'Teclado', '20', 20, 1, 2, '2024-05-03 17:20:20'),
(2, 1, 1, NULL, 'Teclado', '20', 20, 1, 1, '2024-05-03 17:21:37'),
(3, 1, 1, NULL, 'Teclado aa', '20', 20, 1, 1, '2024-05-03 17:22:23'),
(4, 1, 2, NULL, 'Teclado', '20', 20, 1, 1, '2024-05-03 17:22:35'),
(5, 1, 2, NULL, 'Teclado', '20', 20, 1, 1, '2024-05-03 18:20:12'),
(6, 1, 2, NULL, 'Teclados', '20', 20, 1, 1, '2024-05-03 18:20:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes`
--

DROP TABLE IF EXISTS `movimentacoes`;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `movimentacoes`
--

INSERT INTO `movimentacoes` (`id`, `id_setor`, `id_fornecedor`, `statusRegistro`, `tipo`, `motivo`, `data_pedido`, `data_chegada`) VALUES
(1, 3, 1, 1, 1, '', '2024-05-10', '0000-00-00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes_itens`
--

DROP TABLE IF EXISTS `movimentacoes_itens`;
CREATE TABLE IF NOT EXISTS `movimentacoes_itens` (
  `id_movimentacoes` int NOT NULL,
  `id_produtos` int NOT NULL,
  `quantidade` int DEFAULT NULL,
  `valor` double(10,2) NOT NULL DEFAULT '0.00',
  KEY `id_movimentacoes` (`id_movimentacoes`) USING BTREE,
  KEY `id_produtos` (`id_produtos`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `movimentacoes_itens`
--

INSERT INTO `movimentacoes_itens` (`id_movimentacoes`, `id_produtos`, `quantidade`, `valor`) VALUES
(1, 1, 20, 10.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `quantidade` int DEFAULT NULL,
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1=Ativo;2=Inativo',
  `condicao` int DEFAULT '1' COMMENT '1=Novo; 2=Usado',
  `dataMod` timestamp NULL DEFAULT NULL,
  `nome` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fornecedor` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_itens_fornecedor` (`fornecedor`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COMMENT='Itens - Estoque';

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `descricao`, `quantidade`, `statusRegistro`, `condicao`, `dataMod`, `nome`, `fornecedor`) VALUES
(1, '<p>Teclado USB</p>', 20, 1, 1, '2024-05-03 18:20:26', 'Teclados', 2),
(2, 'Mouse Óptico', NULL, 1, 1, NULL, 'Mouse', 2),
(3, 'Monitor LED 24\"', NULL, 1, 1, NULL, 'Monitor', 3),
(4, 'Notebook Dell', NULL, 1, 1, NULL, 'Notebook', 4),
(5, 'Desktop HP', NULL, 1, 1, NULL, 'Desktop', 5),
(6, 'SSD 500GB', NULL, 1, 1, NULL, 'SSD', 6),
(7, 'HD Externo 1TB', NULL, 1, 1, NULL, 'HD Externo', 7),
(8, 'Roteador Wi-Fi', NULL, 1, 1, NULL, 'Roteador', 8),
(9, 'Cabo Ethernet', NULL, 1, 1, NULL, 'Cabo de Rede', 9),
(10, 'Fone de Ouvido', NULL, 1, 1, NULL, 'Fone de Ouvido', 10),
(11, 'Webcam HD', NULL, 1, 1, NULL, 'Webcam', 11),
(12, 'Placa de Vídeo', NULL, 1, 1, NULL, 'Placa de Vídeo', 12),
(13, 'Impressora Laser', NULL, 1, 1, NULL, 'Impressora', 13),
(14, 'Scanner Epson', NULL, 1, 1, NULL, 'Scanner', 14),
(15, 'Projetor Multimídia', NULL, 1, 1, NULL, 'Projetor', 15),
(16, 'Caixa de Som', NULL, 1, 1, NULL, 'Caixa de Som', 16),
(17, 'Pen Drive 32GB', NULL, 1, 1, NULL, 'Pen Drive', 17),
(18, 'Adaptador HDMI', NULL, 1, 1, NULL, 'Adaptador HDMI', 18),
(19, 'Estabilizador', NULL, 1, 1, NULL, 'Estabilizador', 19),
(20, 'Hub USB', NULL, 1, 1, NULL, 'Hub USB', 20);

-- --------------------------------------------------------

--
-- Estrutura para tabela `setor`
--

DROP TABLE IF EXISTS `setor`;
CREATE TABLE IF NOT EXISTS `setor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `responsavel` int NOT NULL DEFAULT (0),
  `statusRegistro` int NOT NULL DEFAULT '1' COMMENT '1 - Ativo      2 - Inativo',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `responsavel_setor` (`responsavel`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `setor`
--

INSERT INTO `setor` (`id`, `nome`, `responsavel`, `statusRegistro`) VALUES
(1, 'Administracao', 1, 1),
(2, 'Recursos Humanos', 2, 1),
(3, 'TI', 3, 1),
(4, 'Contabilidade', 4, 1),
(5, 'Compras', 5, 1);

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
(1, '1', 1, 'Administrador', '$2y$10$Qur9vFCHwHJZGW39K0spFeWO1zo.iyLy2xRDLEztoTVhJAcGR8Ml.', 'administrador@gmail.com', 1);

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `historico_produtos`
--
ALTER TABLE `historico_produtos`
  ADD CONSTRAINT `fk_setor_id` FOREIGN KEY (`setor_id`) REFERENCES `setor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
