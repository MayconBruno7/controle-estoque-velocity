<?php

    require_once "library/Database.php";

    if (isset($_POST['id_funcionarios'])) {

        $db = new Database();

        try {
            $result = $db->dbDelete("DELETE FROM funcionarios 
                                    WHERE id_funcionarios = ?",
                                    [$_POST['id_funcionarios']]
                                );

            if ($result) {
                return header("Location: listaFuncionarios.php?msgSucesso=Registro excluído com sucesso.");
            } else {
                return header("Location: listaFuncionarios.php?msgError=Falha ao tentar excluír o registro.");
            }
            
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }

    } else {
        return header("Location: listaFuncionarios.php");
    }