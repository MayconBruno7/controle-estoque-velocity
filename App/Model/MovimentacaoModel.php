<?php

use App\Library\ModelMain;
use App\Library\Session;

Class MovimentacaoModel extends ModelMain
{
    public $table = "movimentacoes";

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
        if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect("SELECT DISTINCT
                m.id AS id_movimentacao, 
                f.nome AS nome_fornecedor, 
                m.tipo AS tipo_movimentacao, 
                m.data_pedido, 
                m.data_chegada
            FROM 
                {$this->table} m 
            LEFT JOIN 
                fornecedor f ON f.id = m.id_fornecedor 
            LEFT JOIN 
                movimentacoes_itens mi ON mi.id_movimentacoes = m.id
            LEFT JOIN 
                produtos p ON p.id = mi.id_produtos");
            
        } else {
            $rsc = $this->db->dbSelect("SELECT DISTINCT
                m.id AS id_movimentacao, 
                f.nome AS nome_fornecedor, 
                m.tipo AS tipo_movimentacao, 
                m.data_pedido, 
                m.data_chegada
            FROM 
                {$this->table} m 
            LEFT JOIN 
                fornecedor f ON f.id = m.id_fornecedor 
            LEFT JOIN 
                movimentacoes_itens mi ON mi.id_movimentacoes = m.id
            LEFT JOIN 
                produtos p ON p.id = mi.id_produtos 
            WHERE 
                m.statusRegistro = 1;");
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