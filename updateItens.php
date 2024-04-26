<?php

    require_once "library/Database.php";
    require_once "library/protectUser.php";

    // Verifica se a ação é uma atualização (update)
    if (isset($_POST['id'])) {

        // Criando o objeto Db para classe de base de dados
        $db = new Database();

        // Atualiza as informações do item na tabela principal
        $atualizacaoQuery = "UPDATE itens 
                                SET nomeItem = ?, setor_item = ?, descricao = ?, quantidade = ?, fornecedor_id = ?, statusRegistro = ?, statusItem = ?, dataMod = NOW() 
                                WHERE id = ?";
        $atualizacaoData = $db->dbUpdate($atualizacaoQuery,
        [
            $_POST['nome'],
            $_POST['setor_id'],
            $_POST['descricao'],
            $_POST['quantidade'],
            $_POST['fornecedor_id'],
            $_POST['statusRegistro'],
            $_POST['statusItem'],
            $_POST['id']
        ]);

        // Obtém os dados antigos do item antes da atualização
        $dadosAntigos = $db->dbSelect("SELECT * FROM itens WHERE id = ?", 'first',[$_POST['id']]);

        // Insere os dados antigos no histórico
        $historicoQuery = "INSERT INTO historico_itens 
                            (id_item, nome_item, setor_id, descricao_anterior, quantidade_anterior, fornecedor_id, statusRegistro_anterior, statusItem_anterior, dataMod) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $historicoData = $db->dbInsert($historicoQuery, 
        [
            $_POST['id'],
            $dadosAntigos->nomeItem,
            $dadosAntigos->setor_item,
            $dadosAntigos->descricao,
            $dadosAntigos->quantidade,
            $dadosAntigos->fornecedor_id,
            $dadosAntigos->statusRegistro,
            $dadosAntigos->statusItem,
            $dadosAntigos->dataMod
        ]);

        // Verifica se a atualização foi bem sucedida
        if ($atualizacaoData) {
            header("Location: listaItens.php?msgSucesso=Registro alterado com sucesso.");
        } else {
            header("Location: listaItens.php?msgError=Falha ao tentar alterar o registro.");
        }
    } else {
        // Se a ação não for uma atualização, redirecione para a página adequada
        header("Location: listaItens.php?msgError=Operação inválida.");
    }
?>
