<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {

       // Criando o objeto Db para classe de base de dados
       $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbUpdate("UPDATE fornecedor SET nome_fornecedor = ?, cnpj_fornecedor = ?, endereco_fornecedor = ?, telefone_fornecedor = ?, status_fornecedor = ? WHERE id_fornecedor = ?", 
        [
            $_POST['nome_fornecedor'],
            $_POST['cnpj_fornecedor'],
            $_POST['endereco_fornecedor'],
            $_POST['telefone_fornecedor'],
            $_POST['status_fornecedor'],
            $_POST['id_fornecedor']
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