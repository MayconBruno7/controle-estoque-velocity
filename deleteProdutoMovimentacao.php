<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";
    require_once "library/Funcoes.php";

    $db                 = new Database();

    $id_movimentacao    = (int)$_POST['id_movimentacao'];
    $quantidadeRemover  = (int)$_POST['quantidadeRemover'];
    $id_produto         = (int)$_POST['id_produto'];
    $tipo_movimentacao  = (int)$_POST['tipo_movimentacoes'];

    // Verifica se a sessão 'movimentacao' existe e se contém produtos
    if (isset($_SESSION['movimentacao']) && isset($_SESSION['movimentacao'][0]['produtos'])) {
        // Loop sobre os produtos na sessão 'movimentacao'
        foreach ($_SESSION['movimentacao'][0]['produtos'] as $key => &$produto) {
            // Se encontrar o produto com o ID fornecido
            if ($produto['id_produto'] == $id_produto) {
                // Verifica se a quantidade a ser removida é menor ou igual à quantidade do produto na sessão
                if ($quantidadeRemover <= $produto['quantidade']) {
                    // Remove a quantidade do produto da sessão
                    $produto['quantidade'] -= $quantidadeRemover;

                    if ($produto['quantidade'] === 0) {
                        // Se encontrar o produto com o ID fornecido
                        if ($produto['id_produto'] == $id_produto) {
                            // Remove o produto da sessão
                            unset($_SESSION['movimentacao'][0]['produtos'][$key]);

                            header("Location: formMovimentacoes.php?acao=insert&msgSucesso=Quantidade do produto removida da movimentação com sucesso");

                            exit;
                        }
                    }
                    // Redireciona de volta para a página de movimentações
                    header("Location: formMovimentacoes.php?acao=insert&msgSucesso=Quantidade do produto removida da movimentação com sucesso");
                    exit;
                } else {
                    // Se a quantidade a ser removida for maior do que a quantidade disponível, redireciona com uma mensagem de erro
                    header("Location: formMovimentacoes.php?acao=insert&msgError=Quantidade a ser removida é maior do que a quantidade disponível na movimentação");
                    exit;
                }
            }
        }
        // Se o produto com o ID fornecido não foi encontrado na sessão
        header("Location: formMovimentacoes.php?acao=insert&msgError=Produto não encontrado na movimentação");
        exit;
    }

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
                } else if ($tipo_movimentacao == '2') {
                    $novaQuantidadeEstoque = ($quantidadeProduto + $quantidadeRemover);
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
        header("Location: formMovimentacoes.php?acao=update&msgError=Produto não encontrado na movimentação&id_movimentacoes=$id_movimentacao");
    }
