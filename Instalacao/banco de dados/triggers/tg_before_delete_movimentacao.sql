DELIMITER //

CREATE TRIGGER tg_before_delete_movimentacao
BEFORE DELETE ON movimentacao
FOR EACH ROW
BEGIN
    DECLARE v_tipo INT;
    DECLARE v_estoque_atual INT;

    -- Verifica se o tipo da movimentação excluída era 1 (Entrada) ou 2 (Saída)
    SET v_tipo = OLD.tipo;

    -- Verifica a quantidade total de produtos associados à movimentação que está sendo excluída
    IF v_tipo IN (1, 2) THEN
        SELECT SUM(mi.quantidade) INTO v_estoque_atual
        FROM movimentacao_item mi
        WHERE mi.id_movimentacoes = OLD.id;

        -- Para cada produto associado, verifica a quantidade atual em estoque
        IF EXISTS (
            SELECT 1
            FROM movimentacao_item mi
            JOIN produto p ON p.id = mi.id_produtos
            WHERE mi.id_movimentacoes = OLD.id
            AND (p.quantidade - v_estoque_atual) < 0
        ) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Não é permitido excluir esta movimentação: Estoque ficará negativo.';
        END IF;
    END IF;

    -- Atualiza a quantidade de produtos na tabela produto
    UPDATE produto p
    JOIN movimentacao_item mi ON p.id = mi.id_produtos
    SET p.quantidade = CASE
        WHEN v_tipo = 1 THEN p.quantidade - mi.quantidade
        WHEN v_tipo = 2 THEN p.quantidade + mi.quantidade
    END
    WHERE mi.id_movimentacoes = OLD.id;

END //

DELIMITER ;
