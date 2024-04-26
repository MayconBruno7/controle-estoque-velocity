<?php

class Funcoes
{

    /**
     * strDecimais
     *
     * @param string $valor 
     * @return float
     */
    public static function strDecimais($valor)
    {
        return str_replace(",", ".", str_replace(".", "", $valor));
    } 
    /**
     * valorBr
     *
     * @param float $valor 
     * @return float
     */
    public static function valorBr($valor, $decimais = 2) 
    {
        return number_format($valor, $decimais, ",", ".");
    }

    /**
     * userLogado
     *
     * @return bool
     */
    public static function userLogado($nivel = 1)
    {
        if (isset($_SESSION['userId'])) {
            if ($_SESSION['userNivel'] == $nivel) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }
}