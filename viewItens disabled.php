
<?php 

    require_once "library/protectUser.php";

    $dados = [];

    /*
    *   Se for alteração, exclusão ou visualização busca a UF pelo ID que foi recebido via método GET
    */

    try {

        // Conecta com a base de dados
        $conn = new PDO(
            "mysql:host=localhost;dbname=controle_estoque", 
            "root", 
            "",

            // diz pro banco de dados qual o padrão de codificação que será usado
            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
        );

        // tratamento de exceções PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($id)) {
            $query_itens = "SELECT id, nomeItem, descricao, quantidade, statusRegistro, dataMod FROM itens WHERE id = :id LIMIT 1";
    
            $result_itens = $conn->prepare($query_itens);
            $result_itens->bindParam(':id', $id, PDO::PARAM_INT);
            $result_itens->execute();
    
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