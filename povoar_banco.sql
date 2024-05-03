-- Popula a tabela cargo
INSERT INTO cargo (nome, statusRegistro) VALUES 
('Analista de Sistemas', 1),
('Programador', 1),
('Técnico de Informatica', 1),
('Suporte Tecnico', 1),
('Desenvolvedor Web', 1),
('Gerente de TI', 1),
('DBA', 1),
('Analista de Suporte', 1),
('Arquiteto de Software', 1),
('Engenheiro de Software', 1),
('Analista de Seguranca', 1),
('Analista de Qualidade', 1),
('Scrum Master', 1),
('Product Owner', 1),
('UX Designer', 1),
('UI Designer', 1),
('DevOps Engineer', 1),
('Data Scientist', 1),
('Analista de BI', 1),
('Analista de Testes', 1);

-- Popula a tabela fornecedor
INSERT INTO fornecedor (nome, cnpj, endereco, cidade, estado, bairro, numero, telefone, statusRegistro) VALUES 
('Tech Supplier', '12345678000101', 'Rua das Tecnologias', 'Tecnopolis', 'Tecnolandia', 'Tech Park', '123', '0012345678', 1),
('InfoParts', '23456789000102', 'Avenida dos Componentes', 'Circuit City', 'Eletronville', 'Digital District', '456', '0023456789', 1),
('WebTech Solutions', '34567890000103', 'Rua dos Servidores', 'Webtown', 'Netland', 'Internet Ave', '789', '0034567890', 1),
('DataNet Corp', '45678901000104', 'Avenida da Rede', 'Dataspace', 'Cloud County', 'Cyber Center', '987', '0045678901', 1),
('SoftEdge Ltda', '56789012000105', 'Rua do Software', 'Coded City', 'Developerville', 'Code Central', '654', '0056789012', 1),
('BitWorks', '67890123000106', 'Avenida dos Bits', 'Bitburg', 'Digitaland', 'Binary Street', '321', '0067890123', 1),
('Microtech', '78901234000107', 'Rua das Micros', 'Microville', 'Chipland', 'Micro Center', '147', '0078901234', 1),
('TechHive', '89012345000108', 'Avenida da Tecnologia', 'Tech City', 'Infotech', 'Geek Plaza', '258', '0089012345', 1),
('CodeCrafters', '90123456000109', 'Rua do Codigo', 'Codeville', 'Scriptland', 'Syntax Lane', '369', '0090123456', 1),
('Logicware', '01234567000110', 'Avenida da Logica', 'Logic City', 'Algoland', 'Logic Square', '159', '0012345678', 1),
('CyberSolutions', '12345678000111', 'Rua da Ciberseguranca', 'Cyberburg', 'Cryptoland', 'Firewall Blvd', '357', '0012345678', 1),
('DataSphere', '23456789000112', 'Avenida dos Dados', 'Datascape', 'Infotown', 'Cloud Street', '753', '0023456789', 1),
('Connectech', '34567890000113', 'Rua da Conexao', 'Connectville', 'Interland', 'Link Lane', '159', '0034567890', 1),
('Smart Systems', '45678901000114', 'Avenida da Tecnologia', 'Tech City', 'Infotech', 'Geek Plaza', '258', '0045678901', 1),
('Cybermind', '56789012000115', 'Rua da Seguranca Digital', 'Digitopolis', 'Cryptoland', 'Firewall Street', '963', '0056789012', 1),
('TechGenius', '67890123000116', 'Avenida dos Gadgets', 'Gadgetown', 'Digitaland', 'Gizmo Avenue', '753', '0067890123', 1),
('SoftSolutions', '78901234000117', 'Rua da Programacao', 'Codecity', 'Developerville', 'Code Street', '159', '0078901234', 1),
('DataCloud', '89012345000118', 'Avenida da Nuvem', 'Cloudville', 'Infotown', 'Cloud Road', '357', '0089012345', 1),
('InfoTech', '90123456000119', 'Rua da Informatica', 'Infotown', 'Techland', 'Tech Street', '951', '0090123456', 1),
('TechWorld', '01234567000120', 'Avenida da Tecnologia', 'Tech City', 'Infotech', 'Tech Avenue', '357', '0012345678', 1);

-- Popula a tabela funcionarios
INSERT INTO funcionarios (nome, cpf, telefone, setor, salario, statusRegistro) VALUES 
('Joao Silva', '12345678901', '0012345678', 1, 3000.00, 1),
('Maria Santos', '23456789012', '0023456789', 2, 3500.00, 1),
('Pedro Oliveira', '34567890123', '0034567890', 3, 3200.00, 1),
('Ana Pereira', '45678901234', '0045678901', 4, 2800.00, 1),
('Carlos Souza', '56789012345', '0056789012', 5, 4000.00, 1),
('Juliana Santos', '67890123456', '0067890123', 6, 3800.00, 1),
('Lucas Lima', '78901234567', '0078901234', 7, 3300.00, 1),
('Camila Oliveira', '89012345678', '0089012345', 8, 3100.00, 1),
('Marcos Silva', '90123456789', '0090123456', 9, 3700.00, 1),
('Patricia Costa', '01234567890', '0012345678', 10, 3400.00, 1),
('Rafaela Nunes', '12345678901', '0023456789', 11, 3900.00, 1),
('Fernando Souza', '23456789012', '0034567890', 12, 3600.00, 1),
('Gabriel Lima', '34567890123', '0045678901', 13, 3200.00, 1),
('Ana Claudia', '45678901234', '0056789012', 14, 2800.00, 1),
('Pedro Henrique', '56789012345', '0067890123', 15, 4000.00, 1),
('Marcela Oliveira', '67890123456', '0078901234', 16, 3300.00, 1),
('Lucas Santos', '78901234567', '0089012345', 17, 3100.00, 1),
('Aline Costa', '89012345678', '0090123456', 18, 3700.00, 1),
('Rodrigo Silva', '90123456789', '0012345678', 19, 3400.00, 1),
('Vanessa Nunes', '01234567890', '0023456789', 20, 3900.00, 1);

-- Popula a tabela produtos
INSERT INTO produtos (descricao, statusRegistro, condicao, nome, fornecedor) VALUES 
('Teclado USB',  1, 1, 'Teclado', 1),
('Mouse Óptico',  1, 1, 'Mouse', 2),
('Monitor LED 24"',  1, 1, 'Monitor', 3),
('Notebook Dell',  1, 1, 'Notebook', 4),
('Desktop HP',  1, 1, 'Desktop', 5),
('SSD 500GB', 1, 1, 'SSD', 6),
('HD Externo 1TB',  1, 1, 'HD Externo', 7),
('Roteador Wi-Fi',  1, 1, 'Roteador', 8),
('Cabo Ethernet',  1, 1, 'Cabo de Rede', 9),
('Fone de Ouvido',  1, 1, 'Fone de Ouvido', 10),
('Webcam HD',  1, 1, 'Webcam', 11),
('Placa de Vídeo',  1, 1, 'Placa de Vídeo', 12),
('Impressora Laser',  1, 1, 'Impressora', 13),
('Scanner Epson',  1, 1, 'Scanner', 14),
('Projetor Multimídia',  1, 1, 'Projetor', 15),
('Caixa de Som',  1, 1, 'Caixa de Som', 16),
('Pen Drive 32GB',  1, 1, 'Pen Drive', 17),
('Adaptador HDMI',  1, 1, 'Adaptador HDMI', 18),
('Estabilizador',  1, 1, 'Estabilizador', 19),
('Hub USB',  1, 1, 'Hub USB', 20);

-- Popula a tabela setor
INSERT INTO setor (nome, responsavel, statusRegistro) VALUES 
('Administracao', 1, 1),
('Recursos Humanos', 2, 1),
('TI', 3, 1),
('Contabilidade', 4, 1),
('Compras', 5, 1);
