<?php

use App\Library\ModelMain;

Class FornecedorModel extends ModelMain
{
    public $table = "fornecedor";

    public function requireAPI($cnpj) {
        $cnpj_limpo = preg_replace("/[^0-9]/", "", $cnpj);
        $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj_limpo}";

        $options = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        $context = stream_context_create($options);

        $response = file_get_contents($url, false, $context);

        if ($response !== false) {
            $data = json_decode($response, true);
            return $data !== null ? $data : ['error' => 'Erro ao decodificar a resposta da API.'];
        } else {
            return ['error' => 'Erro ao consultar a API.'];
        }
    }
}