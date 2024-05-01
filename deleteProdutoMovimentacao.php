<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";
    require_once "library/Funcoes.php";

    $db                 = new Database();

    $id_movimentacao    = (int)$_POST['id_movimentacao'];
    $quantidadeRemover  = (int)$_POST['quantidadeRemover'];
    $id_produto         = (int)$_POST['id_produto'];
    $tipo_movimentacao  = (int)$_POST['tipo_movimentacoes'];

    // Obtém o item da movimentacao relacionado ao produto
    $item_movimentacao = $db->dbSelect(
        "SELECT * FROM movimentacoes_itens WHERE id_movimentacoes = ? AND id_produtos = ?",
        'first',
        [$id_movimentacao, $id_produto]
    );

    if ($item_movimentacao) {
        // recupera a quantidade atual do item na movimentação
        $quantidadeAtual = $item_movimentacao->quantidade;

        // Verifica se a quantidade a ser removida não ultrapassa a quantidade atual na comanda
        if ($quantidadeRemover <= $quantidadeAtual) {
            // Subtrai a quantidade a ser removida da quantidade atual na comanda
            $novaQuantidadeMovimentacao = $quantidadeAtual - $quantidadeRemover;


            // Atualiza a tabela movimetacao_itens com a nova quantidade
            $db->dbUpdate(
                "UPDATE movimentacoes_itens SET quantidade = ? WHERE id_movimentacoes = ? AND id_produtos = ?",
                [$novaQuantidadeMovimentacao, $id_movimentacao, $id_produto]
            );
        
            // Obtém informações do produto para atualizar o estoque
            $produto = $db->dbSelect(
                "SELECT * FROM produtos WHERE id = ?",
                'first',
                [$id_produto]
            );

            // Verifica se o produto existe
            if ($produto) {

                $quantidadeProduto = $produto->quantidade;

                if ($tipo_movimentacao == '1') {
                    $novaQuantidadeEstoque = ($quantidadeProduto - $quantidadeRemover);
                    // exit('Movimentação 1   ');
                } else if ($tipo_movimentacao == '2') {
                    $novaQuantidadeEstoque = ($quantidadeProduto + $quantidadeRemover);
                    // exit('opa   ');
                } else {
                    exit;
                }

                // atualiza a quantidade em estoque
                $db->dbUpdate(
                    "UPDATE produtos SET quantidade = ? WHERE id = ?",
                    [$novaQuantidadeEstoque, $id_produto]
                );

                // Remove os produtos com quantidade igual a zero da movimentação
                $qtdZero = $db->dbDelete(
                    "DELETE FROM movimentacoes_itens
                    WHERE id_produtos = ? AND id_movimentacoes = ? AND QUANTIDADE = 0",
                    [$id_produto, $id_movimentacao]
                );

                header("Location: formMovimentacoes.php?acao=update&msgError=Erro ao deletar item&id_movimentacoes=$id_movimentacao");
            } else {
                header("Location: formMovimentacoes.php?acao=update&msgError=Erro ao deletar item&id_movimentacoes=$id_movimentacao");
            }

            header("Location: formMovimentacoes.php?acao=update&msgSucesso=Quantidade do item deletado com sucesso&id_movimentacoes=$id_movimentacao");
        } else {
            header("Location: formMovimentacoes.php?acao=update&msgError=Quantidade maior que a da movimentação&id_movimentacoes=$id_movimentacao");
        }
    } else {
        header("Location: formMovimentacoes.php?acao=update&msgError=Produto não encontrado na movimentaçãom&id_movimentacoes=$id_movimentacao");
    }
