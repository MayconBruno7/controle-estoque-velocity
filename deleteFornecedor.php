<?php

    require_once "library/Database.php";

    if (isset($_POST['id_fornecedor'])) {

        $db = new Database();

        try {
            $result = $db->dbDelete("DELETE FROM fornecedor 
                                    WHERE id_fornecedor = ?",
                                    [$_POST['id_fornecedor']]
                                );

            if ($result) {
                return header("Location: listaFornecedor.php?msgSucesso=Registro excluído com sucesso.");
            } else {
                return header("Location: listaFornecedor.php?msgError=Falha ao tentar excluír o registro.");
            }
            
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }

    } else {
        return header("Location: listaFornecedor.php");
    }