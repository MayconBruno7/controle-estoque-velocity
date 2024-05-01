<?php

    require_once "library/Database.php";

    $db = new Database();

    if (isset($_POST['id_movimentacoes'], $_POST['quantidade'], $_POST['id_produto'], $_POST['valor'], $_POST['tipo_movimentacoes'])) {
        $id_movimentacoes = (int)$_POST['id_movimentacoes'];
        $quantidade = (int)$_POST['quantidade'];
        $id_produtos = (int)$_POST['id_produto'];
        $valor_produto = (float)$_POST['valor'];
        $tipo_movimentacao = (int)$_POST['tipo_movimentacoes'];

        // Subtrai ou adiciona a quantidade do estoque
        $produto = $db->dbSelect("SELECT * FROM produtos WHERE id = ?", 'first', [$id_produtos]);

        $itemMovimentacao = $db->dbSelect(
            "SELECT * FROM movimentacoes_itens WHERE id_movimentacoes = ? AND id_produtos = ?",
            'first',
            [$id_movimentacoes, $id_produtos]
        );

        if ($tipo_movimentacao == '1') {
            $quantidadeEstoque = $produto->quantidade + $quantidade;
            $quantidadeAdicionar = $quantidade;
            $quantidadeAtual = $itemMovimentacao->quantidade + $quantidadeAdicionar;
        } else if ($tipo_movimentacao == '2') {
            $quantidadeEstoque = $produto->quantidade - $quantidade;
            $quantidadeRemover = $quantidade;
            $quantidadeAtual = $itemMovimentacao->quantidade - $quantidadeRemover;
        } else {
            exit;
        }

        $db->dbUpdate("UPDATE produtos SET quantidade = ? WHERE id = ?", [$quantidadeEstoque, $id_produtos]);
        
        // Se o item existir na movimentação, atualiza a quantidade
        if (!empty($itemMovimentacao)) {
            
            $db->dbUpdate(
                "UPDATE movimentacoes_itens SET quantidade = ?, valor = ? WHERE id_movimentacoes = ? AND id_produtos = ?",
                [$quantidadeAtual, $valor_produto, $id_movimentacoes, $id_produtos]
            );

            header("Location: formMovimentacoes.php?acao=update&id_movimentacoes=$id_movimentacoes&msgSucesso=Item inserido com sucesso");
            exit;
            
        } else {
            
            // Se o item não existir na movimentação adiciona o item e quantidade
            $db->dbInsert(
                "INSERT INTO movimentacoes_itens(quantidade, valor, id_movimentacoes, id_produtos) VALUES (?, ?, ?, ?)",
                [$quantidade, $valor_produto, $id_movimentacoes, $id_produtos]
            );

            header("Location: formMovimentacoes.php?acao=update&id_movimentacoes=$id_movimentacoes&msgSucesso=Quantidade do item atualizada com sucesso");
            exit;
        }

    } else {
        header("Location: formMovimentacoes.php?acao=update&id_movimentacoes=$id_movimentacoes&msgError=Não recebi dados do formulário");
        exit;
    }
?>

