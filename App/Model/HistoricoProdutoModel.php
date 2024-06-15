<?php

use App\Library\ModelMain;
use App\Library\ControllerMain;

Class HistoricoProdutoModel extends ModelMain
{
    public $table = "historico_produtos";

    // public $validationRules = [
    //     'descricao' => [
    //         'label' => 'Descrição',
    //         'rules' => 'required|min:3|max:50'
    //     ],
    //     'statusRegistro' => [
    //         'label' => 'Status',
    //         'rules' => 'required|int'
    //     ]
    // ];

    public function historicoProduto($orderBy = 'dataMod', $idProduto)
    {
        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id_produtos = ? ORDER BY {$orderBy}", [$idProduto]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}