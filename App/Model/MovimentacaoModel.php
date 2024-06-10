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

            var_dump($atualizaInformacoesMovimentacao, $atualizaProdutosMovimentacao);
            exit;

            if($atualizaInformacoesMovimentacao || $atualizaProdutosMovimentacao) {
                return true;
            }

        } else {
            return false;
        }
    }

    public function updateInformacoesProdutoMovimentacao($idMovimentacao, $aProdutos, $acao)
    {   

        if($idMovimentacao) {

            $condWhere = $idMovimentacao['id_movimentacao'];
    
            foreach ($aProdutos as $item) {
                if($acao['acaoProduto'] == 'update') {
                  
                    $atualizaProdutosMovimentacao = $this->db->update("movimentacoes_itens", ['id_movimentacoes' => $condWhere, 'id_produtos' => $aProdutos[0]['id_produtos']], $item);

                    var_dump($aProdutos[0]['id_produtos']);
                    var_dump($acao['acaoProduto']);
                    var_dump($condWhere);
                    exit('opa');         
                } 

                // else if($acao['acaoProduto'] == 'insert'){
                //     $item['id_movimentacoes'] = $idMovimentacao['id_movimentacao'];

                //     $insereProdutosMovimentacao = $this->db->insert("movimentacoes_itens", $item);
                // } else {
                //     echo "erro";
                // }
            }

            if ((isset($atualizaProdutosMovimentacao) && $atualizaProdutosMovimentacao) || (isset($insereProdutosMovimentacao) && $insereProdutosMovimentacao)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    // public function deleteProdutoMovimentacao($idMovimentacao, $aProdutos, $acao)
    // {   

    //     $ProdutoModel = $this->loadModel("Produto");
    //     $dadosProduto['aProduto'] = $ProdutoModel->recuperaProduto($id_produto);

    //     $MovimentacaoItemModel = $this->loadModel("MovimentacaoItem");
    //     $dadosItensMovimentacao = $MovimentacaoItemModel->listaProdutos($id_movimentacao);

    //     var_dump($dadosProduto);
    //     exit;

    //     if ($dadosItensMovimentacao) {
    //         // recupera a quantidade atual do item na movimentação
    //         $quantidadeAtual = $dadosItensMovimentacao[0]['mov_itens_quantidade'];
    
    //         // Verifica se a quantidade a ser removida não ultrapassa a quantidade atual na comanda
    //         if ($quantidadeRemover <= $quantidadeAtual) {
    //             // Subtrai a quantidade a ser removida da quantidade atual na comanda
    //             $novaQuantidadeMovimentacao = $quantidadeAtual - $quantidadeRemover;
    
    //             // Atualiza a tabela movimetacao_itens com a nova quantidade
    //             $db->dbUpdate(
    //                 "UPDATE movimentacoes_itens SET quantidade = ? WHERE id_movimentacoes = ? AND id_produtos = ?",
    //                 [$novaQuantidadeMovimentacao, $id_movimentacao, $id_produto]
    //             );
    
    //             // Verifica se o produto existe
    //             if ($dadosProduto['aProduto']) {
    
    //                 $quantidadeProduto = $produto->quantidade;
    
    //                 if ($tipo_movimentacao == '1') {
    //                     $novaQuantidadeEstoque = ($quantidadeProduto - $quantidadeRemover);
    //                 } else if ($tipo_movimentacao == '2') {
    //                     $novaQuantidadeEstoque = ($quantidadeProduto + $quantidadeRemover);
    //                 } else {
    //                     exit;
    //                 }
    
    //                 // atualiza a quantidade em estoque
    //                 $db->dbUpdate(
    //                     "UPDATE produtos SET quantidade = ? WHERE id = ?",
    //                     [$novaQuantidadeEstoque, $id_produto]
    //                 );
    
    //                 // Remove os produtos com quantidade igual a zero da movimentação
    //                 $qtdZero = $db->dbDelete(
    //                     "DELETE FROM movimentacoes_itens
    //                     WHERE id_produtos = ? AND id_movimentacoes = ? AND QUANTIDADE = 0",
    //                     [$id_produto, $id_movimentacao]
    //                 );
    
    //                 header("Location: formMovimentacoes.php?acao=update&msgError=Erro ao deletar item&id_movimentacoes=$id_movimentacao");
    //             } else {
    //                 header("Location: formMovimentacoes.php?acao=update&msgError=Erro ao deletar item&id_movimentacoes=$id_movimentacao");
    //             }
    
    //             header("Location: formMovimentacoes.php?acao=update&msgSucesso=Quantidade do item deletado com sucesso&id_movimentacoes=$id_movimentacao");
    //         } else {
    //             header("Location: formMovimentacoes.php?acao=update&msgError=Quantidade maior que a da movimentação&id_movimentacoes=$id_movimentacao");
    //         }
    //     } else {
    //         header("Location: formMovimentacoes.php?acao=update&msgError=Produto não encontrado na movimentação&id_movimentacoes=$id_movimentacao");
    //     }
    //     var_dump($post);
    //     exit;

}