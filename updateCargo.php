<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {

       // Criando o objeto Db para classe de base de dados
       $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbUpdate("UPDATE cargo SET nome_cargo = ? WHERE id_cargo = ?", 
        [
            $_POST['nome_cargo'],
            $_POST['id_cargo']
        ]);


        // verifica se o item foi adicionado atráves do rowCount
        if ($data) {
            return header("Location: listaCargo.php?msgSucesso=Registro alterado com sucesso.");
        } else {
            return header("Location: listaCargo.php?msgError=Falha ao tentar alterar o registro.");
        }
        
    // se ocorrer algum erro durante a conexão com o banco de dados é retornado pelo bloco catch
    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }