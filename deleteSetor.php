<?php

    require_once "library/Database.php";

    if (isset($_POST['id_setor'])) {

        $db = new Database();

        try {
            $result = $db->dbDelete("DELETE FROM setor 
                                    WHERE id_setor = ?",
                                    [$_POST['id_setor']]
                                );

            if ($result) {
                return header("Location: listaSetor.php?msgSucesso=Registro excluído com sucesso.");
            } else {
                return header("Location: listaSetor.php?msgError=Falha ao tentar excluír o registro.");
            }
            
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }

    } else {
        return header("Location: listaSetor.php");
    }