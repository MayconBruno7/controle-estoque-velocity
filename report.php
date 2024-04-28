<?php
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn-relatorio"])) {
    // Verifica se o funcionário foi selecionado
    if (isset($_POST["funcionario_selecionado"])) {
        $funcionario_id = $_POST["funcionario_selecionado"];

        // Aqui você pode usar a variável $funcionario_id para recuperar as informações específicas do funcionário selecionado do banco de dados
        // Por exemplo, você pode executar outra consulta SQL para obter os detalhes do funcionário com base no ID selecionado

        // Exemplo de como obter informações do funcionário selecionado do banco de dados (supondo que você tenha uma classe Database):
        
        try {
            $db = new Database();
            $funcionario_info = $db->dbSelect("SELECT * FROM funcionarios WHERE id_funcionarios = :id_funcionario", [":id_funcionario" => $funcionario_id]);
            // Agora $funcionario_info contém as informações do funcionário selecionado
        } catch (Exception $ex) {
            echo "Erro ao obter informações do funcionário: " . $ex->getMessage();
        }
        

        // Aqui você pode exibir as informações do funcionário, por exemplo:
        
        if ($funcionario_info) {
            echo "<h2>Informações do Funcionário</h2>";
            echo "<p>ID: " . $funcionario_info[0]['id_funcionarios'] . "</p>";
            echo "<p>Nome: " . $funcionario_info[0]['nome_funcionarios'] . "</p>";
            // Exibir outras informações do funcionário conforme necessário
        } else {
            echo "Funcionário não encontrado";
        }
        

        // Por enquanto, apenas para demonstração, vamos mostrar o ID do funcionário selecionado
        echo "<h2>Relatório do Funcionário Selecionado</h2>";
        echo "<p>ID do Funcionário Selecionado: $funcionario_id</p>";
    } else {
        echo "Por favor, selecione um funcionário.";
    }
} else {
    echo "Erro: O formulário não foi submetido corretamente.";
}
?>
