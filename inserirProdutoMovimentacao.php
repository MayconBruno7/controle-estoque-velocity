<?php

    session_start();

    require_once "library/Database.php";

    $db = new Database();

    isset($_GET['acao']) ? $_GET['acao'] : "";

    $quantidade = (int)$_POST['quantidade'];
    $id_produto = (int)$_POST['id_produto'];
    $valor_produto = (float)$_POST['valor'];

    $produto = $db->dbSelect("SELECT * FROM produtos WHERE id = ?", 'first', [$id_produto]);

    if (isset($_GET['acao']) && $_GET['acao'] == 'insert') {
        // Verificar se há uma sessão de movimentação
        if (!isset($_SESSION['movimentacao'])) {
            $_SESSION['movimentacao'] = array();
        }

        // Verificar se há produtos na sessão de movimentação
        if (!isset($_SESSION['movimentacao'][0]['produtos'])) {
            $_SESSION['movimentacao'][0]['produtos'] = array();
        }

        // Adicionar os produtos à sessão de movimentação
        $_SESSION['movimentacao'][0]['produtos'][] = array(
            'nome_produto' => $produto->nome,
            'id_produto' => $id_produto,
            'quantidade' => $quantidade,
            'valor' => $valor_produto
        );

        // Redirecionar de volta para a página de movimentações
        header("Location: formMovimentacoes.php?msgSucesso=Produto inserido com sucesso.&acao=". $_GET['acao']);
        exit;
        
    } else if (isset($_GET['acao']) && $_GET['acao'] == 'update') {

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

        // Limpar a sessão movimentação
        unset($_SESSION['movimentacao']);
        // Limpar a sessão de produtos
        unset($_SESSION['produtos']);

        // Redirecionar de volta para a página de movimentações
        header("Location: formMovimentacoes.php?msgSucesso=Produto inserido com sucesso.&acao=". $_GET['acao'] . "&id_movimentacoes=" . $_GET['id_movimentacoes']);
        exit;
    }
?>
