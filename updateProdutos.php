<?php

    require_once "library/Database.php";
    require_once "library/protectUser.php";

    // Verifica se a ação é uma atualização (update)
    if (isset($_POST['id'])) {

        // Criando o objeto Db para classe de base de dados
        $db = new Database();

        // Atualiza as informações do item na tabela principal
        $atualizacaoQuery = "UPDATE produtos 
<<<<<<< HEAD
                                SET nome = ?, descricao = ?, statusRegistro = ?, condicao = ?, fornecedor = ?, dataMod = NOW() 
=======
                                SET nome = ?, descricao = ?, quantidade = ?, statusRegistro = ?, condicao = ?, setor = ?, fornecedor = ?, dataMod = NOW() 
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
                                WHERE id = ?";
        $atualizacaoData = $db->dbUpdate($atualizacaoQuery,
        [
            $_POST['nome'],
            $_POST['descricao'],
<<<<<<< HEAD
            $_POST['statusRegistro'],
            $_POST['condicao'],
=======
            $_POST['quantidade'],
            $_POST['statusRegistro'],
            $_POST['condicao'],
            $_POST['setor_id'],
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
            $_POST['fornecedor_id'],

            $_POST['id']
        ]);

<<<<<<< HEAD
        

=======
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
        // Obtém os dados antigos do item antes da atualização
        $dadosAntigos = $db->dbSelect("SELECT * FROM produtos WHERE id = ?", 'first',[$_POST['id']]);

        // Insere os dados antigos no histórico
        $historicoQuery = "INSERT INTO historico_produtos
<<<<<<< HEAD
                            (id_produtos, nome_produtos, descricao_anterior, quantidade_anterior, fornecedor_id, status_anterior, statusItem_anterior, dataMod) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
=======
                            (id_produtos, nome_produtos, setor_id, descricao_anterior, quantidade_anterior, fornecedor_id, status_anterior, statusItem_anterior, dataMod) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
        $historicoData = $db->dbInsert($historicoQuery, 
        [
            $_POST['id'],
            $dadosAntigos->nome,
<<<<<<< HEAD
            $_POST['quantidade'],
=======
            $dadosAntigos->setor,
            $dadosAntigos->descricao,
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
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
