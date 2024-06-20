<?php

use App\Library\ModelMain;
use App\Library\Session;
use App\Library\ControllerMain;


Class ProdutoModel extends ModelMain
{

    public $table = "produtos";

    public $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required|min:3|max:50'
        ],
        'condicao' => [
            'label' => 'Condição',
            'rules' => 'required'
        ],
        'nome' => [
            'label' => 'Nome',
            'rules' => 'required'
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
            $rsc = $this->db->dbSelect(

                "SELECT 
                        produtos.*, 
                        (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
                    FROM 
                    {$this->table}"
            
            );
            
        } else {

            $rsc = $this->db->dbSelect("SELECT 
                    produtos.*, 
                    (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
                FROM 
                {$this->table}
                WHERE statusRegistro = 1 AND quantidade > 0");
  
        }
        
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
    public function listaDeleteProduto($id_produto)
    {

        if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect(
                "SELECT 
                    produtos.*, 
                    (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
                FROM 
                    {$this->table}
                WHERE 
                    produtos.id = ?", [$id_produto]
            );
            
        } else {

            $rsc = $this->db->dbSelect(
                "SELECT 
                    produtos.*, 
                    (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
                FROM 
                    {$this->table}
                WHERE 
                    produtos.statusRegistro = 1 AND produtos.id = ?", [$id_produto]
            );
  
        }
        
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
    /**
     * recuperaProduto
     *
     * @param  mixed $idProduto
     * @return void
     */
    public function recuperaProduto($idProduto)
    {

        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id = ?", [$idProduto]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    /**
     * updateProduto
     *
     * @param  mixed $id_produto
     * @param  mixed $id_movimentacao
     * @param  mixed $tipo_movimentacao
     * @param  mixed $infoProduto
     * @return void
     */
    public function updateProduto($id_produto, $id_movimentacao, $tipo_movimentacao, $infoProduto)
    {

        if($id_produto) {

            $condWhere = $id_produto;

            $produto = $this->recuperaProduto($id_produto);
            $quantidadeAtual = $produto[0]['quantidade'];

            if($tipo_movimentacao == 1) {
                $attquantidade = ($quantidadeAtual + $infoProduto['quantidade']);
            } else if ($tipo_movimentacao == 2) {
                $attquantidade = ($quantidadeAtual - $infoProduto['quantidade']);
            }

            $atualizaInformacoesProduto = $this->db->update($this->table, ['id' => $condWhere], ["quantidade" => $attquantidade]);

            foreach ($infoProduto as $item) {
                
                $atualizaProdutosMovimentacao = $this->db->update("movimentacoes_itens", ['id_movimentacoes' => $id_movimentacao, 'id_produtos' => $id_produto], ['quantidade' => $item]);
                var_dump($atualizaProdutosMovimentacao);
                var_dump([$item]);
                exit;
            }

            if($atualizaInformacoesProduto || $atualizaProdutosMovimentacao) {
                return true;
            }

        } else {
            return false;
        }
    }
}