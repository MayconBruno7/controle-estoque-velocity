<?php

use App\Library\ModelMain;
use App\Library\Session;
use App\Library\ControllerMain;



Class MovimentacaoModel extends ModelMain
{
    public $table = "movimentacoes_itens";

    public $validationRules = [
    //     'descricao' => [
    //         'label' => 'Descrição',
    //         'rules' => 'required|min:3|max:50'
    //     ],
    //     'caracteristicas' => [
    //         'label' => 'Características',
    //         'rules' => 'required|min:5'
    //     ],
    //     'categoria_id' => [
    //         'label' => 'Categoria',
    //         'rules' => 'required|int'
    //     ],
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
       
        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes,
                    mi.id_produtos AS id_prod_mov_itens,
                    mi.quantidade AS mov_itens_quantidade,
                    mi.valor,
                    p.*
                FROM movimentacoes_itens mi
                INNER JOIN produtos p ON p.id = mi.id_produtos
                WHERE mi.id_movimentacoes = ?
                    OR mi.id_movimentacoes IS NULL
                ORDER BY p.descricao;
                ",
                'all',
                [isset($this->model->getById($this->getId()))) ? getId($_GET['id_movimentacoes']) :""];
        
                $this->model->getById($this->getId('id_movimentacoes'))

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