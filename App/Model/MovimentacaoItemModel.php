<?php

use App\Library\ModelMain;

Class MovimentacaoItemModel extends ModelMain
{
    public $table = "movimentacoes_itens";

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function listaProdutos($id_movimentacao)
    {

        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes,
                    mi.id_produtos AS id_prod_mov_itens,
                    mi.quantidade AS mov_itens_quantidade,
                    mi.valor,
                    p.*
                FROM {$this->table} mi
                INNER JOIN produtos p ON p.id = mi.id_produtos
                WHERE mi.id_movimentacoes = ?
                    OR mi.id_movimentacoes IS NULL
                ORDER BY p.descricao;
                ",
                $id_movimentacao);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
    // /**
    //  * getProdutoCombobox
    //  *
    //  * @param int $categoria_id 
    //  * @return array
    //  */
    // public function getProdutoCombobox($categoria_id) 
    // {
    //     $rsc = $this->db->dbSelect("SELECT p.id, p.descricao 
    //                                 FROM {$this->table} as p
    //                                 INNER JOIN categoria as c ON c.id = p.categoria_id
    //                                 WHERE c.id = ?
    //                                 ORDER BY p.descricao",
    //                                 [$categoria_id]);

    //     if ($this->db->dbNumeroLinhas($rsc) > 0) {
    //         return $this->db->dbBuscaArrayAll($rsc);
    //     } else {
    //         return [];
    //     }
    // }
}