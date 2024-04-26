<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {

       // Criando o objeto Db para classe de base de dados
       $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbUpdate("UPDATE setor SET nome_setor = ?, responsavel_setor = ?, status_setor = ?  WHERE id_setor = ?", 
        [
            $_POST['nome_setor'],
            $_POST['responsavel_setor'],
            $_POST['status_setor'], 
            $_POST['id_setor']
        ]);


        // verifica se o item foi adicionado atráves do rowCount
        if ($data) {
            return header("Location: listaSetor.php?msgSucesso=Registro alterado com sucesso.");
        } else {
            return header("Location: listaSetor.php?msgError=Falha ao tentar alterar o registro.");
        }
        
    // se ocorrer algum erro durante a conexão com o banco de dados é retornado pelo bloco catch
    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }