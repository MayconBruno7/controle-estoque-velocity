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

    public static function formataTelefone($telefone) 
    {
        // Remove todos os caracteres não numéricos do telefone
        $telefone = preg_replace("/[^0-9]/", "", $telefone);
    
        // Verifica se o número de telefone possui 10 ou 11 dígitos
        if (strlen($telefone) == 10) {
            // Formata para (XX) XXXX-XXXX
            return "(" . substr($telefone, 0, 2) . ") " . substr($telefone, 2, 4) . "-" . substr($telefone, 6, 4);
        } elseif (strlen($telefone) == 11) {
            // Formata para (XX) XXXXX-XXXX
            return "(" . substr($telefone, 0, 2) . ") " . substr($telefone, 2, 5) . "-" . substr($telefone, 7, 4);
        } else {
            // Retorna o número original se não for possível formatar
            return $telefone;
        }
    }
    
    public static function formatarCNPJ($cnpj) 
    {
        if (strlen($cnpj) == 14) {
            // Remove caracteres não numéricos
            $cnpj = preg_replace('/\D/', '', $cnpj);
        
            // Adiciona os separadores
            $cnpjFormatado = substr($cnpj, 0, 2) . '.' .
                            substr($cnpj, 2, 3) . '.' .
                            substr($cnpj, 5, 3) . '/' .
                            substr($cnpj, 8, 4) . '-' .
                            substr($cnpj, 12, 2);
            
            return $cnpjFormatado;

        } else {
            return $cnpj;
        }
    }

    public static function formatarCPF($cpf) {
        // Remove todos os caracteres que não são dígitos
        $cpf = preg_replace('/\D/', '', $cpf);
    
        // Formata o CPF (###.###.###-##)
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
    

    public static function formatarDataBrasileira($data) 
    {
        // Converte a data para o formato timestamp, se necessário
        $timestamp = is_numeric($data) ? $data : strtotime($data);
        
        // Formata a data no formato brasileiro
        return date('d/m/Y H:i:s', $timestamp);
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