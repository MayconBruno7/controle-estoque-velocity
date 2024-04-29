<?php

    require_once "library/Database.php";

    if (isset($_POST['id'])) {

        $db = new Database();

        try {
            $result = $db->dbDelete("DELETE FROM cargo 
                                    WHERE id = ?",
                                    [$_POST['id']]
                                );

            if ($result) {
                return header("Location: listaCargo.php?msgSucesso=Registro excluído com sucesso.");
            } else {
                return header("Location: listaCargo.php?msgError=Falha ao tentar excluír o registro.");
            }
            
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }

    } else {
        return header("Location: listaCargo.php");
    }