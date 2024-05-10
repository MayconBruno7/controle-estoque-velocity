<?php

    session_start();

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    $db = new Database();

    // if (isset($_POST['id_movimentacoes'], $_POST['quantidade'], $_POST['id_produto'], $_POST['valor'], $_POST['tipo_movimentacoes'])) {
    if (isset($_POST['quantidade'], $_POST['id_produto'], $_POST['valor'], $_POST['tipo_movimentacoes'])) {
        // $id_movimentacao = (int)$_POST['id_movimentacoes'];
        $quantidade = (int)$_POST['quantidade'];
        $id_produtos = (int)$_POST['id_produto'];
        $valor_produto = (float)$_POST['valor'];
        $tipo_movimentacao = (int)$_POST['tipo_movimentacoes'];

        // Subtrai ou adiciona a quantidade do estoque
        $produto = $db->dbSelect("SELECT * FROM produtos WHERE id = ?", 'first', [$id_produtos]);

        $_SESSION['produtos'][] = array(
            'nome_produto' => $produto->nome,
            'id_produto' => $id_produtos,
            'quantidade' => $quantidade,
            'valor' => $valor_produto
        );
        
        header("Location: formMovimentacoes.php?msgSucesso=Produto inserido com sucesso.&acao=insert");
        exit;

    } else {
        header("Location: formMovimentacoes.php?msgError=Não recebi dados do formulário&acao=insert");
        exit;
    }
?>
