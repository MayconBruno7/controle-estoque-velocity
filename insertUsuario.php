<?php
    require_once "library/protectNivel.php";
    require_once "library/Database.php";

    if (isset($_POST['nome'])) {

        if (trim($_POST['senha']) == trim($_POST['confSenha'])) {

            $db = new Database();

            try {

                $result = $db->dbInsert("INSERT INTO usuario (nome, email, statusRegistro, nivel, senha)
                    VALUES (?, ?, ?, ?, ?)",
                    [
                        $_POST['nome'],
                        $_POST['email'],
                        $_POST['statusRegistro'],
                        $_POST['nivel'],
                        password_hash(trim($_POST['senha']), PASSWORD_DEFAULT),
                    ]);

                if ($result) {
                    return header("Location: listaUsuario.php?msgSucesso=Registro inserido com sucesso.");
                } else {
                    return header("Location: listaUsuario.php?msgError=Falha ao tentar inserir o registro.");
                }
                
            } catch (Exception $ex) {
                echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
            }

        } else {
            return header("Location: listaUsuario.php?msgError=Senha não confere.");
        }        

    } else {
        return header("Location: listaUsuario.php");
    }