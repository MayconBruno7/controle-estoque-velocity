<?php

    require_once "library/Database.php";
    require_once "library/Funcoes.php";

    $db                 = new Database();

    $id_movimentacao    = (int)$_POST['id_movimentacao'];
    $quantidadeRemover  = (int)$_POST['quantidadeRemover'];
    $id_produto         = (int)$_POST['id_produto'];

    // Obtém o item da comanda relacionado ao produto
    $item_movimentacao = $db->dbSelect(
        "SELECT * FROM movimentacoes_itens WHERE id_movimentacoes = ? AND id_produtos = ?",
        'first',
        [$id_movimentacao, $id_produto]
    );

    if ($item_movimentacao) {

        $quantidadeAtual = $item_movimentacao->quantidade;

        // Verifica se a quantidade a ser removida não ultrapassa a quantidade atual na comanda
        if ($quantidadeRemover <= $quantidadeAtual) {
            // Subtrai a quantidade a ser removida da quantidade atual na comanda
            $novaQuantidadeComanda = $quantidadeAtual - $quantidadeRemover;

            // Atualiza a tabela movimetacao_itens com a nova quantidade
            $db->dbUpdate(
                "UPDATE movimetacao_itens SET quantidade = ? WHERE id_movimentacoes = ? AND id_produtos = ?",
                [$novaQuantidadeComanda, $id_movimentacao, $id_produto]
            );

            // Obtém informações do produto para atualizar o estoque
            $produto = $db->dbSelect(
                "SELECT * FROM produtos WHERE id = ?",
                'first',
                [$id_produto]
            );

            // Adiciona a quantidade removida de volta ao estoque
            $novaQuantidadeEstoque = ($produto->quantidade) + ($quantidadeRemover);

            $db->dbUpdate(
                "UPDATE produto SET quantidade = ? WHERE ID_PRODUTOS = ?",
                [$novaQuantidadeEstoque, $id_produto]
            );

            if ($produto) {
                // Adiciona a quantidade removida de volta ao estoque
                $novaQuantidadeEstoque = $produto->quantidade + $quantidadeRemover;
    
                $db->dbUpdate(
                    "UPDATE produto SET quantidade = ? WHERE ID_PRODUTOS = ?",
                    [$novaQuantidadeEstoque, $id_produto]
                );

                // Remova registros com quantidade igual a zero, se necessário
                $qtdZero = $db->dbDelete(
                    "DELETE FROM movimetacao_itens
                    WHERE id_produtos = ? AND id_movimentacoes = ? AND QUANTIDADE = 0",
                    [$id_produto, $id_movimentacao]
                );

                header("Location: visualizarItensComanda.php?id_movimentacao=$id_movimentacao");
            } else {
                echo "Produto não encontrado na comanda.";
            }

            header("Location: visualizarItensComanda.php?id_movimentacao=$id_movimentacao");
        } else {
            echo "Quantidade a ser removida é maior que a quantidade atual na comanda.";
        }
    } else {
        echo "Produto não encontrado na comanda.";
    }
