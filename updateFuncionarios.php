<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {

       // Criando o objeto Db para classe de base de dados
       $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbUpdate("UPDATE funcionarios SET nome_funcionarios = ?, cpf_funcionarios = ?, status_funcionarios = ?, telefone_funcionarios = ?, setor_funcionarios = ?, salario_funcionario = ?  WHERE id_funcionarios = ?", 
        [
            $_POST['nome_funcionarios'],
            $_POST['cpf_funcionarios'],
            $_POST['status_funcionarios'],
            $_POST['telefone_funcionarios'],
            $_POST['setor_funcionarios'],
            $_POST['salario_funcionario'],
            $_POST['id_funcionarios']
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