CREATE TABLE movimentacao_audit (
  audit_id INT(10) NOT NULL AUTO_INCREMENT,
  id INT(10) NOT NULL,
  id_setor INT(10) NOT NULL,
  id_fornecedor INT(10) NOT NULL,
  statusRegistro INT(10) NOT NULL,
  tipo INT(10) NOT NULL,
  motivo VARCHAR(100) NOT NULL,
  data_pedido DATE NOT NULL,
  data_chegada DATE NULL DEFAULT NULL,
  deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_by INT(10),
  deleted_by_nome VARCHAR(150),
  PRIMARY KEY (audit_id)
) ENGINE=INNODB;

CREATE TABLE movimentacao_item_audit (
  audit_id INT(10) NOT NULL AUTO_INCREMENT,
  id INT(10) NOT NULL,
  id_movimentacoes INT(10) NOT NULL,
  id_produtos INT(10) NOT NULL,
  quantidade INT(10) NULL DEFAULT NULL,
  valor DOUBLE NOT NULL DEFAULT '0.00',
  deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_by INT(10),
  deleted_by_nome VARCHAR(150),
  PRIMARY KEY (audit_id)
) ENGINE=INNODB;


-- Tabela de auditoria para movimentacoes
CREATE TABLE movimentacao_audit (
  audit_id INT(10) NOT NULL AUTO_INCREMENT,
  id INT(10) NOT NULL,
  id_setor INT(10) NOT NULL,
  id_fornecedor INT(10) NOT NULL,
  statusRegistro INT(10) NOT NULL,
  tipo INT(10) NOT NULL,
  motivo VARCHAR(100) NOT NULL,
  data_pedido DATE NOT NULL,
  data_chegada DATE NULL DEFAULT NULL,
  deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_by INT(10),
  deleted_by_nome VARCHAR(150),
  PRIMARY KEY (audit_id)
) ENGINE=INNODB;

-- Trigger para registrar a movimentacao antes da exclusao
DELIMITER //

CREATE TRIGGER before_movimentacao_delete
BEFORE DELETE ON movimentacao
FOR EACH ROW
BEGIN
    -- Pegar o nome do usuário a partir da tabela usuario
    DECLARE user_nome VARCHAR(150);
    SELECT nome INTO user_nome FROM usuario WHERE id = @current_user_id;
    
    -- Inserir dados na tabela de auditoria
    INSERT INTO movimentacao_audit (id, id_setor, id_fornecedor, statusRegistro, tipo, motivo, data_pedido, data_chegada, deleted_by, deleted_by_nome)
    VALUES (OLD.id, OLD.id_setor, OLD.id_fornecedor, OLD.statusRegistro, OLD.tipo, OLD.motivo, OLD.data_pedido, OLD.data_chegada, @current_user_id, user_nome);
END //

DELIMITER ;

DELIMITER //

-- Trigger para deletar um item de movimentacao
CREATE TRIGGER delete_movimentacao_item
BEFORE DELETE ON movimentacao_item
FOR EACH ROW
BEGIN
    -- Obter os dados da movimentacao antes da exclusao
    DECLARE mov_id INT;
    DECLARE mov_id_setor INT;
    DECLARE mov_id_fornecedor INT;
    DECLARE mov_statusRegistro INT;
    DECLARE mov_tipo INT;
    DECLARE mov_motivo VARCHAR(100);
    DECLARE mov_data_pedido DATE;
    DECLARE mov_data_chegada DATE;
    DECLARE user_id INT;
    DECLARE user_nome VARCHAR(150);

    -- Obter o ID do usuário atual da variável de sessão
    SET user_id = @current_user_id;

    -- Selecionar dados da movimentacao antes de ser deletada
    SELECT id, id_setor, id_fornecedor, statusRegistro, tipo, motivo, data_pedido, data_chegada
    INTO mov_id, mov_id_setor, mov_id_fornecedor, mov_statusRegistro, mov_tipo, mov_motivo, mov_data_pedido, mov_data_chegada
    FROM movimentacao
    WHERE id = (SELECT id_movimentacoes FROM movimentacao_item WHERE id = OLD.id);

    -- Pegar o nome do usuário a partir da tabela usuario
    SELECT nome INTO user_nome FROM usuario WHERE id = user_id;

    -- Inserir dados na tabela movimentacao_item_audit
    INSERT INTO movimentacao_item_audit (id_movimentacoes, id_produtos, quantidade, valor, deleted_by, deleted_by_nome)
    VALUES (mov_id, OLD.id_produtos, OLD.quantidade, OLD.valor, user_id, user_nome);
END //

DELIMITER ;
