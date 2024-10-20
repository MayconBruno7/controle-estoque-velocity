DELIMITER $$

CREATE TRIGGER tg_after_delete_movimentacao
BEFORE DELETE ON movimentacao
FOR EACH ROW
BEGIN
    DECLARE v_tipo INT;

    -- Verifica se o tipo da movimentação excluída era 1 (Entrada) ou 2 (Saída)
    SET v_tipo = OLD.tipo;
    
	  -- Atualiza a quantidade de produtos na tabela produto
    -- Para cada item associado à movimentação excluída
    UPDATE produto p
    JOIN movimentacao_item mi ON p.id = mi.id_produtos
    SET p.quantidade = CASE
        WHEN v_tipo = 1 THEN p.quantidade - mi.quantidade
        WHEN v_tipo = 2 THEN p.quantidade + mi.quantidade
    END
    WHERE mi.id_movimentacoes = OLD.id;
    
END$$

DELIMITER ;
