<?php

use App\Library\ModelMain;

Class HistoricoProdutomovimentacaoModel extends ModelMain
{
    public $table = "movimentacoes_itens";

    public function historico_produto_movimentacao($id_produto)
    {
        $rsc = $this->db->dbSelect("SELECT m.id as id_mov, 
                f.nome as nome_fornecedor, 
                m.tipo, m.data_pedido, 
                m.data_chegada, 
                (SELECT SUM(movi.quantidade) FROM $this->table movi WHERE movi.id_movimentacoes = m.id) AS Quantidade, 
                (SELECT SUM(movi.valor) FROM $this->table movi WHERE movi.id_movimentacoes = m.id) AS Valor 
            FROM movimentacoes m JOIN fornecedor f ON (f.id = m.id_fornecedor) 
            WHERE m.id IN (SELECT id_movimentacoes FROM $this->table WHERE id_produtos = ?)", 
                [$id_produto]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}