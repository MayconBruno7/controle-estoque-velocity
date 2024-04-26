<?php
// require_once "formFornecedor.php"; // inclua o arquivo onde a função getIBGEData está definida

// if(isset($_GET['estado_id'])) {
//     $estado_id = $_GET['estado_id'];
    
//     // Obtém as cidades do estado
//     $cidades = getIBGEData("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$estado_id}/municipios");
    
//     // Monta as opções de cidades
//     $options = '<option value="">Selecione uma cidade</option>';
//     foreach ($cidades as $cidade) {
//         $options .= "<option value='{$cidade['id']}'>{$cidade['nome']}</option>";
//     }
    
//     // Retorna as opções
//     echo $options;
// } else {
//     // Se o ID do estado não foi fornecido, retorna uma opção padrão
//     echo '<option value="">Selecione um estado primeiro</option>';
// }
?>
