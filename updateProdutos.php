<?php

    require_once "library/Database.php";
    require_once "library/protectUser.php";

    // Verifica se a ação é uma atualização (update)
    if (isset($_POST['id'])) {

        // Criando o objeto Db para classe de base de dados
        $db = new Database();

        // Atualiza as informações do item na tabela principal
        $atualizacaoQuery = "UPDATE produtos 
                                SET nome = ?, descricao = ?, quantidade = ?, statusRegistro = ?, condicao = ?, setor = ?, fornecedor = ?, dataMod = NOW() 
                                WHERE id = ?";
        $atualizacaoData = $db->dbUpdate($atualizacaoQuery,
        [
            $_POST['nome'],
            $_POST['descricao'],
            $_POST['quantidade'],
            $_POST['statusRegistro'],
            $_POST['condicao'],
            $_POST['setor_id'],
            $_POST['fornecedor_id'],

            $_POST['id']
        ]);

        // Obtém os dados antigos do item antes da atualização
        $dadosAntigos = $db->dbSelect("SELECT * FROM produtos WHERE id = ?", 'first',[$_POST['id']]);

        // Insere os dados antigos no histórico
        $historicoQuery = "INSERT INTO historico_produtos
                            (id_produtos, nome_produtos, setor_id, descricao_anterior, quantidade_anterior, fornecedor_id, status_anterior, statusItem_anterior, dataMod) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $historicoData = $db->dbInsert($historicoQuery, 
        [
            $_POST['id'],
            $dadosAntigos->nome,
            $dadosAntigos->setor,
            $dadosAntigos->descricao,
            $dadosAntigos->quantidade,
            $dadosAntigos->fornecedor,
            $dadosAntigos->statusRegistro,
            $dadosAntigos->condicao,
            $dadosAntigos->dataMod
        ]);

        // Verifica se a atualização foi bem sucedida
        if ($atualizacaoData) {
            header("Location: listaProdutos.php?msgSucesso=Registro alterado com sucesso.");
        } else {
            header("Location: listaProdutos.php?msgError=Falha ao tentar alterar o registro.");
        }
    } else {
        // Se a ação não for uma atualização, redirecione para a página adequada
        header("Location: listaProdutos.php?msgError=Operação inválida.");
    }
?>
