<?php

<<<<<<< HEAD
    require_once "library/protectUser.php";
=======
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
    require_once "library/Database.php";
    require_once "library/Funcoes.php";

    $db                 = new Database();

    $id_movimentacao    = (int)$_POST['id_movimentacao'];
    $quantidadeRemover  = (int)$_POST['quantidadeRemover'];
    $id_produto         = (int)$_POST['id_produto'];

<<<<<<< HEAD
=======
    var_dump($quantidadeRemover);
    var_dump($id_movimentacao);
    var_dump($id_produto);
    
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
    // Obtém o item da movimentacao relacionado ao produto
    $item_movimentacao = $db->dbSelect(
        "SELECT * FROM movimentacoes_itens WHERE id_movimentacoes = ? AND id_produtos = ?",
        'first',
        [$id_movimentacao, $id_produto]
    );

<<<<<<< HEAD
    if ($item_movimentacao) {
        // recupera a quantidade atual do item na movimentação
        $quantidadeAtual = $item_movimentacao->quantidade;

=======
    var_dump($item_movimentacao);

    

    if ($item_movimentacao) {

        $quantidadeAtual = $item_movimentacao->quantidade;

        var_dump($quantidadeAtual);

>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
        // Verifica se a quantidade a ser removida não ultrapassa a quantidade atual na comanda
        if ($quantidadeRemover <= $quantidadeAtual) {
            // Subtrai a quantidade a ser removida da quantidade atual na comanda
            $novaQuantidadeMovimentacao = $quantidadeAtual - $quantidadeRemover;

<<<<<<< HEAD
=======
            var_dump($novaQuantidadeMovimentacao);

>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
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

            // Adiciona a quantidade removida de volta ao estoque
            $novaQuantidadeEstoque = ($produto->quantidade) + ($quantidadeRemover);

<<<<<<< HEAD
            // Verifica se o produto existe
=======
            

            

            $db->dbUpdate(
                "UPDATE produtos SET quantidade = ? WHERE id = ?",
                [$novaQuantidadeEstoque, $id_produto]
            );

            var_dump($novaQuantidadeEstoque);

            
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
            if ($produto) {
                // Adiciona a quantidade removida de volta ao estoque
                $novaQuantidadeEstoque = $produto->quantidade + $quantidadeRemover;
    
<<<<<<< HEAD
                // atualiza a quantidade em estoque
=======
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
                $db->dbUpdate(
                    "UPDATE produtos SET quantidade = ? WHERE id = ?",
                    [$novaQuantidadeEstoque, $id_produto]
                );

<<<<<<< HEAD
                // Remove os produtos com quantidade igual a zero da movimentação
=======
                


                // Remova registros com quantidade igual a zero, se necessário
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
                $qtdZero = $db->dbDelete(
                    "DELETE FROM movimentacoes_itens
                    WHERE id_produtos = ? AND id_movimentacoes = ? AND QUANTIDADE = 0",
                    [$id_produto, $id_movimentacao]
                );

<<<<<<< HEAD
=======

>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
                header("Location: formMovimentacoes.php?acao=update&msgError=Erro ao deletar item&id_movimentacoes=$id_movimentacao");
            } else {
                header("Location: formMovimentacoes.php?acao=update&msgError=Erro ao deletar item&id_movimentacoes=$id_movimentacao");
            }

            header("Location: formMovimentacoes.php?acao=update&msgSucesso=Quantidade do item deletado com sucesso&id_movimentacoes=$id_movimentacao");
        } else {
<<<<<<< HEAD
            header("Location: formMovimentacoes.php?acao=update&msgError=Quantidade maior que a da movimentação&id_movimentacoes=$id_movimentacao");
        }
    } else {
        header("Location: formMovimentacoes.php?acao=update&msgError=Produto não encontrado na movimentaçãom&id_movimentacoes=$id_movimentacao");
=======
            echo "Quantidade a ser removida é maior que a quantidade atual na comanda.";
        }
    } else {
        echo "Produto não encontrado na comanda.";
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
    }
