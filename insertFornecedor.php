<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {
        
        // Criando o objeto Db para classe de base de dados
        $db = new Database();

        // prepara a query
        $data = $db->dbInsert("INSERT INTO fornecedor(nome, cnpj, endereco, telefone, statusRegistro) VALUES (?, ?, ?, ?, ?)", [
            $_POST['nome'],
            preg_replace("/[^0-9]/", "", $_POST['cnpj']),
            $_POST['endereco'],
            preg_replace("/[^0-9]/", "", $_POST['telefone']),
            $_POST['statusRegistro']
        ]);

        // verifica se o ultimo item adicionado no banco de dados é mais que zero
        if ($data) {
            return header("Location: listaFornecedor.php?msgSucesso=Registro inserido com sucesso.");
        } else {
            return header("Location: listaFornecedor.php?msgError=Falha ao tentar inserir o registro.");
        }
        
    // se houver algum erro na conexão com o banco de dados é retornado pelo bloco catch
    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }