<?php

use App\Library\ModelMain;
use App\Library\Session;

Class SetorModel extends ModelMain
{
    public $table = "setor";

    public $validationRules = [
        'nome' => [
            'label' => 'Nome',
            'rules' => 'required|min:3|max:100'
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
            $rsc = $this->db->dbSelect("SELECT s.*, f.nome as nomeResponsavel FROM {$this->table} as s LEFT JOIN funcionario as f ON s.responsavel = f.id ORDER BY {$orderBy}");
            
        } else {
            $rsc = $this->db->dbSelect("SELECT s.*, f.nome as nomeResponsavel FROM {$this->table} as s LEFT JOIN funcionario as f ON s.responsavel = f.id WHERE s.statusRegistro = 1 ORDER BY {$orderBy}");            
        }

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
    /**
     * getProdutoCombobox
     *
     * @param int $categoria_id 
     * @return array
     */
    public function getProdutoCombobox($categoria_id) 
    {
        $rsc = $this->db->dbSelect("SELECT p.id, p.descricao 
                                    FROM {$this->table} as p
                                    INNER JOIN categoria as c ON c.id = p.categoria_id
                                    WHERE c.id = ?
                                    ORDER BY p.descricao",
                                    [$categoria_id]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}