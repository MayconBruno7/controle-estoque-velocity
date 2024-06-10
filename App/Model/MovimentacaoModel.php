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

    public function idUltimaMovimentacao()
    {

        $rsc = $this->db->dbSelect("SELECT MAX(id) AS ultimo_id FROM movimentacoes");

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    /**
     * insertMovimentacao
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return void
     */
    public function insertMovimentacao($movimentacao, $aProdutos)
    {

        $ultimoRegistro = $this->db->insert($this->table, $movimentacao);

        if ($ultimoRegistro > 0) {

            foreach ($aProdutos as $item) {

                $item["id_movimentacoes"] = $ultimoRegistro;
                $this->db->insert("movimentacoes_itens", $item);

            }

            return true;

        } else {
            return false;
        }
    }

    /**
     * updateMovimentacao
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return void
     */
    public function updateMovimentacao($idMovimentacao, $movimentacao, $aProdutos)
    {

        if($idMovimentacao) {

            $condWhere = $idMovimentacao['id_movimentacao'];

            $atualizaInformacoesMovimentacao = $this->db->update($this->table, ['id' => $condWhere], $movimentacao);

            foreach ($aProdutos as $item) {
                $atualizaProdutosMovimentacao = $this->db->update("movimentacoes_itens", ['id_movimentacoes' => $condWhere], $item);
            }

            if($atualizaInformacoesMovimentacao || $atualizaProdutosMovimentacao) {
                return true;
            }

        } else {
            return false;
        }
    }

    // public function updateInformacoesProdutoMovimentacao($idMovimentacao, $movimentacao, $aProdutos)
    // {

    //     if($idMovimentacao) {

    //         $condWhere = $idMovimentacao['id_movimentacao'];

    //         $atualizaInformacoesMovimentacao = $this->db->update($this->table, ['id' => $condWhere], $movimentacao);

    //         foreach ($aProdutos as $item) {
    //             $atualizaProdutosMovimentacao = $this->db->update("movimentacoes_itens", ['id_movimentacoes' => $condWhere], $item);
    //         }

    //         if($atualizaInformacoesMovimentacao || $atualizaProdutosMovimentacao) {
    //             return true;
    //         }

    //     } else {
    //         return false;
    //     }
    // }


}