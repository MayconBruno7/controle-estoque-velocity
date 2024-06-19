<?php

use App\Library\ModelMain;

Class HistoricoProdutoModel extends ModelMain
{
    public $table = "historico_produtos";

    public function historicoProduto($orderBy = 'id_produtos', $idProduto)
    {
        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id_produtos = ? ORDER BY {$orderBy}", [$idProduto]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}