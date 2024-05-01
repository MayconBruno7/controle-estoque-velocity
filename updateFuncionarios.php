<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {

       // Criando o objeto Db para classe de base de dados
       $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbUpdate("UPDATE funcionarios SET nome = ?, cpf = ?, statusRegistro = ?, telefone = ?, setor = ?, salario = ? WHERE id = ?", 
        [
            $_POST['nome'],
            preg_replace("/[^0-9]/", "", $_POST['cpf']),
            $_POST['statusRegistro'],
            preg_replace("/[^0-9]/", "", $_POST['telefone']),
            $_POST['setor'],
            $_POST['salario'],
            $_POST['id']
        ]);


        // verifica se o item foi adicionado atráves do rowCount
        if ($data) {
            return header("Location: listaFuncionarios.php?msgSucesso=Registro alterado com sucesso.");
        } else {
            return header("Location: listaFuncionarios.php?msgError=Falha ao tentar alterar o registro.");
        }
        
    // se ocorrer algum erro durante a conexão com o banco de dados é retornado pelo bloco catch
    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }