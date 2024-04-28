<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {

       // Criando o objeto Db para classe de base de dados
       $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbUpdate("UPDATE fornecedor SET nome = ?, cnpj = ?, endereco = ?, telefone = ?, statusRegistro = ? WHERE id = ?", 
        [
            $_POST['nome'],
            $_POST['cnpj'],
            $_POST['endereco'],
            $_POST['telefone'],
            $_POST['statusRegistro'],
            $_POST['id']
        ]);


        // verifica se o item foi adicionado atráves do rowCount
        if ($data) {
            return header("Location: listaFornecedor.php?msgSucesso=Registro alterado com sucesso.");
        } else {
            return header("Location: listaFornecedor.php?msgError=Falha ao tentar alterar o registro.");
        }
        
    // se ocorrer algum erro durante a conexão com o banco de dados é retornado pelo bloco catch
    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }