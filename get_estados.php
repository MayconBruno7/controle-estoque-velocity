<?php
// Função para fazer uma requisição para a API do IBGE e obter os estados de um país
// function getIBGEStates($pais_id) {
//     $url = "https://servicodados.ibge.gov.br/api/v1/localidades/pais/{$pais_id}/estados";
//     $response = file_get_contents($url);
//     return json_decode($response, true);
// }

// // Verifica se o ID do país foi recebido via GET
// if(isset($_GET['pais_id'])) {
//     $pais_id = $_GET['pais_id'];
    
//     // Obtém os estados do país
//     $estados = getIBGEStates($pais_id);
    
//     // Monta as opções de estados
//     $options = '<option value="">Selecione um estado</option>';
//     foreach ($estados as $estado) {
//         $options .= "<option value='{$estado['id']}'>{$estado['nome']}</option>";
//     }
    
//     // Retorna as opções
//     echo $options;
// } else {
//     // Se o ID do país não foi fornecido, retorna uma opção padrão
//     echo '<option value="">Selecione um país primeiro</option>';
// }
?>
