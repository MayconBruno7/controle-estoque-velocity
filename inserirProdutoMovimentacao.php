<?php

    session_start();

    require_once "library/Database.php";

    $db = new Database();

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
        header("Location: formMovimentacoes.php?msgSucesso=Produto inserido com sucesso.&acao=insert");
        exit;
    } else {
        // Limpar a sessão movimentação
        unset($_SESSION['movimentacao']);
        // Limpar a sessão de produtos
        unset($_SESSION['produtos']);
        // Redirecionar de volta para a página de movimentações
        header("Location: formMovimentacoes.php?msgErro=Erro ao inserir produto.&acao=insert");
        exit;
    }
?>
