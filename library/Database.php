<?php

class Database
{
    /**
     * dbConect
     *
     * @return PDO
     */
    function dbConect()
    {
        try {
            $conn = new PDO(
                "mysql:host=localhost;dbname=controle_estoque", 
                "root", 
                "",
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            return $conn;
            
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }

    /**
     * select
     *
     * @param string $query 
     * @param string $tipoRetorno 
     * @param array $dados 
     * @return array|object
     */
    function dbSelect($query, $tipoRetorno = 'all', $dados = [])
    {
        try {
            $conn = $this->dbConect();
            $data = $conn->prepare($query);

            $data->execute($dados);

            if ($tipoRetorno == "all") {
                return $data->fetchAll();
            } else {
                return $data->fetch(PDO::FETCH_OBJ);
            }

        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
            return [];
        }
    }

    /**
     * dbInsert
     *
     * @param string $queryInsert 
     * @param array $data 
     * @return bool
     */
    function dbInsert(string $queryInsert, array $dados) : bool
    {
        try {
            $conn = $this->dbConect();

            $data = $conn->prepare($queryInsert);
            $data->execute($dados);

            if ($conn->lastInsertId() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
            return false;
        }
    }

    /**
     * dbUpdate
     *
     * @param string $queryUpdate 
     * @param array $dados 
     * @return bool
     */
    function dbUpdate(string $queryUpdate, array $dados) : bool
    {
        try {
            $conn = $this->dbConect();

            $data = $conn->prepare($queryUpdate);
            $data->execute($dados);

            if ($data->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
            return false;
        }
    }
    /**
     * dbDelete
     *
     * @param string $queryDelete 
     * @param array $dados 
     * @return bool
     */
    function dbDelete(string $queryDelete, array $dados) : bool
    {
        try {
            $conn = $this->dbConect();

            $data = $conn->prepare($queryDelete);
            $data->execute($dados);

            if ($data->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
            return false;
        }
    }
}

