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
    public function insertMovimentacao($movimentacao, $aProdutos, $tipo_movimentacao)
    {

        $ultimoRegistro = $this->db->insert($this->table, $movimentacao);

        if ($ultimoRegistro > 0) {

            if($aProdutos[0]['id_produtos'] != '') {
                foreach ($aProdutos as $item) {

                    $item["id_movimentacoes"] = $ultimoRegistro;

                    $this->db->insert("movimentacoes_itens", $item);
                }

                $produto = $this->db->select(
                    "produtos",
                    "all",
                    [
                    "where" => ["id" => $aProdutos[0]["id_produtos"]]
                    ]
                );

                $quantidadeProduto = $produto[0]['quantidade'];

                if ($tipo_movimentacao == '1') {
                    $novaQuantidadeEstoque = ($quantidadeProduto + $aProdutos[0]['quantidade']);

                } else if ($tipo_movimentacao == '2') {
                    $novaQuantidadeEstoque = ($quantidadeProduto - $aProdutos[0]['quantidade']);
                } else {
                    exit;
                }

                //atualiza a quantidade em estoque
                $atualizaEstoqueProduto = $this->db->update("produtos", ['id' => $aProdutos[0]['id_produtos']], ['quantidade' => $novaQuantidadeEstoque]);

                // var_dump($atualizaEstoqueProduto);
                // var_dump($novaQuantidadeEstoque);
                // var_dump($tipo_movimentacao);
                // var_dump($quantidadeProduto);
                // var_dump($produto);
                // exit;
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

        $tipo_movimentacao = $movimentacao['tipo'];

        // var_dump($aProdutos);
        // exit;

        if($idMovimentacao) {

            $condWhere = $idMovimentacao['id_movimentacao'];

            $atualizaInformacoesMovimentacao = $this->db->update($this->table, ['id' => $condWhere], $movimentacao);

            foreach ($aProdutos as $item) {
                $atualizaProdutosMovimentacao = $this->db->update("movimentacoes_itens", ['id_movimentacoes' => $condWhere], $item);
            }

            $produto = $this->db->select(
                "produtos",
                "all",
                [
                "where" => ["id" => $aProdutos[0]["id_produtos"]]
                ]
            );

            $quantidadeProduto = $aProdutos[0]['quantidade'];

            // var_dump($quantidadeProduto);
            // exit;

            // if ($tipo_movimentacao == '1') {
            //     $novaQuantidadeEstoque = ($quantidadeProduto + $aProdutos[0]['quantidade']);

            // } else if ($tipo_movimentacao == '2') {
            //     $novaQuantidadeEstoque = ($quantidadeProduto - $aProdutos[0]['quantidade']);
            // } else {
            //     exit;
            // }

            //atualiza a quantidade em estoque
            $atualizaEstoqueProduto = $this->db->update("produtos", ['id' => $aProdutos[0]['id_produtos']], ['quantidade' => $quantidadeProduto]);

            if($atualizaInformacoesMovimentacao || $atualizaProdutosMovimentacao && $atualizaEstoqueProduto) {
                return true;
            }

        } else {
            return false;
        }
    }

    public function updateInformacoesProdutoMovimentacao($id_movimentacao, $aProdutos, $acao, $tipo_movimentacao)
    {

        $id_produto = $aProdutos[0]['id_produtos'];
        $quantidadeProduto = $aProdutos[0]['quantidade'];

        if($id_movimentacao) {
            $condWhere = $id_movimentacao['id_movimentacao'];

            foreach ($aProdutos as $item) {
                if($acao['acaoProduto'] == 'update') {
                    $atualizaProdutosMovimentacao = $this->db->update("movimentacoes_itens", ['id_movimentacoes' => $condWhere, 'id_produtos' => $id_produto], $item);
                    $atualizaEstoqueProduto = $this->db->update("produtos", ['id' => $id_produto], ['quantidade' => $quantidadeProduto]);
                }

                else if($acao['acaoProduto'] == 'insert'){
                    // var_dump($quantidadeProduto);
                    // exit('insert');

                    $item['id_movimentacoes'] = $id_movimentacao['id_movimentacao'];

                    $insereProdutosMovimentacao = $this->db->insert("movimentacoes_itens", $item);
                    $atualizaEstoqueProduto = $this->db->update("produtos", ['id' => $id_produto], ['quantidade' => $quantidadeProduto]);

                    // tentativa de retornar true
                    // if(rowCount($insereProdutosMovimentacao) > 0) {
                    //     $insereProdutosMovimentacao = true;
                    // } else {
                    //     $insereProdutosMovimentacao = false;
                    // }

                    // var_dump($insereProdutosMovimentacao);
                    // exit('opa');
                } else {
                    echo "erro";
                }
            }

            // if ((isset($atualizaProdutosMovimentacao) && $atualizaProdutosMovimentacao) || (isset($insereProdutosMovimentacao) && $insereProdutosMovimentacao)) {

            //     // $produto = $this->db->select(
            //     //     "produtos",
            //     //     "all",
            //     //     [
            //     //     "where" => ["id" => $aProdutos[0]["id_produtos"]]
            //     //     ]
            //     // );


            //     // if ($tipo_movimentacao == '1') {
            //     //     $novaQuantidadeEstoque = ($quantidadeProduto + $aProdutos[0]['quantidade']);

            //     // } else if ($tipo_movimentacao == '2') {
            //     //     $novaQuantidadeEstoque = ($quantidadeProduto - $aProdutos[0]['quantidade']);
            //     // } else {
            //     //     exit;
            //     // }

               

                
            //     // var_dump($id_produto);
            //     // var_dump($quantidadeProduto);
            //     // exit;
                
            //     //atualiza a quantidade em estoque
            //     // //  var_dump($produto);
            //     // var_dump($aProdutos[0]['quantidade']);
            //     // // var_dump($atualizaEstoqueProduto);
            //     // // var_dump($produto);
            //     // // var_dump($tipo_movimentacao);
            //     // // var_dump($novaQuantidadeEstoque);
            //     // exit('Ospras');
            //     if($atualizaEstoqueProduto) {
            //         return true;
            //     }

            // } else {
            //     return false;
            // }

        } else {
            return false;
        }
    }

    public function deleteInfoProdutoMovimentacao($id_movimentacao, $aProdutos, $tipo_movimentacao, $quantidadeRemover)
    {

        $item_movimentacao = $this->db->select(
            "movimentacoes_itens",
            "all",
            [
            "where" => ["id_movimentacoes" => $id_movimentacao, "id_produtos" => $aProdutos[0]["id"]]
            ]
        );

        if ($item_movimentacao) {

            // recupera a quantidade atual do item na movimentação
            $quantidadeAtual = $item_movimentacao[0]['quantidade'];

            // Verifica se a quantidade a ser removida não ultrapassa a quantidade atual na comanda
            if ($quantidadeRemover <= $quantidadeAtual) {
                // Subtrai a quantidade a ser removida da quantidade atual na comanda
                $novaQuantidadeMovimentacao = $quantidadeAtual - $quantidadeRemover;

                // Atualiza a tabela movimetacao_itens com a nova quantidade
                $atualizaInfoProdutosMovimentacao = $this->db->update("movimentacoes_itens", ['id_movimentacoes' => $id_movimentacao, 'id_produtos' => $item_movimentacao[0]['id_produtos']], ['quantidade' => $novaQuantidadeMovimentacao]);

                //Verifica se o produto existe
                if ($atualizaInfoProdutosMovimentacao) {

                    $produto_movimentacao = $this->db->select(
                        "produtos",
                        "first",
                        [
                        "where" => ["id" => $aProdutos[0]["id"]]
                        ]
                    );

                    $quantidadeProduto = $produto_movimentacao['quantidade'];
                    $novaQuantidadeEstoque = ($quantidadeProduto - $quantidadeRemover);

                    var_dump($quantidadeProduto);
                    var_dump($novaQuantidadeEstoque);
                    exit;

                    //atualiza a quantidade em estoque
                    $atualizaEstoqueProduto = $this->db->update("produtos", ['id' => $produto_movimentacao['id']], ['quantidade' => $novaQuantidadeEstoque]);

                    // Remove os produtos com quantidade igual a zero da movimentação
                    $qtdZero = $this->db->delete('movimentacoes_itens', ['id_movimentacoes' => $id_movimentacao, 'id_produtos' =>  $item_movimentacao[0]['id_produtos'], 'quantidade' => 0]);

                    if ($atualizaEstoqueProduto || $qtdZero) {
                        return true;
                    } else {
                        return true;
                    }

                } else {
                    return false;
                }

                return true;
            } else {
                return false;
                // header("Location: formMovimentacoes.php?acao=update&msgError=Quantidade maior que a da movimentação&id_movimentacoes=$id_movimentacao");
            }
        } else {
            return false;
        }
    }
}