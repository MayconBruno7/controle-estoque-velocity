<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {

       // Criando o objeto Db para classe de base de dados
       $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbUpdate("UPDATE itens SET nomeItem = ?, descricao = ?, quantidade = ?, statusRegistro = ?, statusItem = ?, dataMod = NOW() WHERE id = ?",
        [
            $_POST['nome'],
            $_POST['descricao'],
            $_POST['quantidade'],
            $_POST['statusRegistro'],
            $_POST['statusItem'],
            $_POST['id']
        ]);

        // verifica se o item foi adicionado atráves do rowCount
        if ($data) {
            return header("Location: listaItens.php?msgSucesso=Registro alterado com sucesso.");
        } else {
            return header("Location: listaItens.php?msgError=Falha ao tentar alterar o registro.");
        }
        
    // se ocorrer algum erro durante a conexão com o banco de dados é retornado pelo bloco catch
    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }