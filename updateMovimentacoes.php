<?php

    require_once "library/Database.php";
    require_once "library/protectUser.php";

    // Verifica se a ação é uma atualização (update)
    if (isset($_POST['id'])) {

        // Criando o objeto Db para classe de base de dados
        $db = new Database();

        $atualizacaoData = $db->dbUpdate("UPDATE movimentacoes 
            SET id_setor = ?, id_fornecedor = ?, statusRegistro = ?, tipo = ?, motivo = ?, data_pedido = ?, data_chegada = ? 
            WHERE id = ?"
        ,
        [
            $_POST['setor_id'],
            $_POST['fornecedor_id'],
            $_POST['statusRegistro'],
            $_POST['tipo'],
            $_POST['motivo'],
            $_POST['data_pedido'],
            $_POST['data_chegada'],

            $_POST['id']
        ]);

        // var_dump($_POST['setor_id'],
        // $_POST['fornecedor_id'],
        // $_POST['statusRegistro'],
        // $_POST['tipo'],
        // isset($_POST['motivo']) ? $_POST['motivo'] : "1",
        // $_POST['data_pedido'],
        // $_POST['data_chegada'],

        // $_POST['id']);
        // exit;

        // Verifica se a atualização foi bem sucedida
        if ($atualizacaoData) {
            header("Location: listaMovimentacoes.php?msgSucesso=Registro alterado com sucesso.");
        } else {
            header("Location: listaMovimentacoes.php?msgError=Falha ao tentar alterar o registro.");
        }
    } else {
        // Se a ação não for uma atualização, redirecione para a página adequada
        header("Location: listaMovimentacoes.php?msgError=Operação inválida.");
    }
?>
