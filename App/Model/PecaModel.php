<?php

use App\Library\ModelMain;

Class PecaModel extends ModelMain
{

    public $validationRules = [
        'statusRegistro' => [
            'label' => 'Status',
            'rules' => 'required|int'
        ]
    ];

    public $table = "pecas";

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function listaPeca($id_ordem_servico)
    {

        $rsc = $this->db->dbSelect("SELECT osp.id_ordem_servico,
                    osp.id_peca,
                    osp.quantidade AS quantidade_peca_ordem,
                    p.*
                FROM {$this->table} p
                INNER JOIN ordens_servico_pecas osp ON p.id = osp.id_peca
                WHERE osp.id_ordem_servico = ?
                    OR osp.id_ordem_servico IS NULL
                ORDER BY p.id;
                ",
                $id_ordem_servico);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function recuperaPeca($idPeca)
    {

        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id = ?", [$idPeca]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

     /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function listaDeletePeca($id_peca)
    {

        // if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect(
                "SELECT 
                    *
                FROM 
                    {$this->table}
                WHERE 
                    id = ?", [$id_peca]
            );
            
        // } 
        
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

}