<?php 

require_once "library/protectUser.php";
require_once "library/Database.php";

$dados = [];

try {
    // Conecta com a base de dados
    $conn = new Database();

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (!empty($id)) {
        $query_itens = "SELECT id, nomeItem, descricao, quantidade, statusRegistro, dataMod FROM itens WHERE id = ? LIMIT 1";
        $result_itens = $conn->dbSelect($query_itens, 'first', [$id]);

        if ($result_itens && $result_itens->rowCount() != 0) {
            $row_itens = $result_itens->fetch(PDO::FETCH_ASSOC);
            // Formatando a data para o formato desejado
            $row_itens['dataMod'] = date('d/m/Y H:i:s', strtotime($row_itens['dataMod']));
            $retorna = ['status' => true, 'dados' => $row_itens];
        } else {
            $retorna = ['status' => false, 'msgErro' => 'Item não encontrado'];
        }
    } else {
        $retorna = ['status' => false, 'msgErro' => 'Item não encontrado'];
    }

    header('Content-Type: application/json');
    echo json_encode($retorna);

} catch (Exception $ex) {
    echo json_encode(['status' => false, 'msgErro' => 'Erro interno ao processar a requisição']);
}
?>
