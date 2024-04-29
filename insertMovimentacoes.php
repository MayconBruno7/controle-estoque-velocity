<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // faz a conexão com o banco de dados
    try {
        
        // Criando o objeto Db para classe de base de dados
        $db = new Database();

        // prepara a query
        $data = $db->dbInsert("INSERT INTO movimentacoes(id_setor, id_fornecedor, statusRegistro, tipo,
        motivo, data_pedido, data_chegada) VALUES (?, ?, ?, ?, ?, ?, ?)", 
        [
            $_POST['setor_id'],
            $_POST['fornecedor_id'],
            $_POST['statusRegistro'],
            $_POST['tipo'],
            $_POST['motivo'],
            $_POST['data_pedido'],
            $_POST['data_chegada'],
        ]);

        // verifica se o ultimo item adicionado no banco de dados é mais que zero
        if ($data) {
            return header("Location: listaMovimentacoes.php?msgSucesso=Registro inserido com sucesso.");
        } else {
            return header("Location: listaMovimentacoes.php?msgError=Falha ao tentar inserir o registro.");
        }
        
    // se houver algum erro na conexão com o banco de dados é retornado pelo bloco catch
    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }