<?php

use App\Library\ModelMain;
use App\Library\Session;

Class CargoModel extends ModelMain
{
    public $table = "cargo";

    public $validationRules = [
        'nome' => [
            'label' => 'nome',
            'rules' => 'required|min:3|max:50'
        ],
        'statusRegistro' => [
            'label' => 'Status',
            'rules' => 'required|int'
        ]
    ];

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function lista($orderBy = 'id')
    {
        if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect("SELECT * FROM cargo ORDER BY {$orderBy}");
            
        } else {
            $rsc = $this->db->dbSelect("SELECT * FROM cargo ORDER BY {$orderBy} WHERE statusRegistro = 1");
            
        }

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