<?php
    require_once "library/protectUser.php";
    require_once "library/Database.php";

    // Verifica se está sendo recebido alguma informação pelo método POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        try {
            $db = new Database();

            // Processar os dados enviados pelo formulário
            // Verifica se o campo quantidade está definido e se é um array
            if (isset($_POST['quantidade']) && is_array($_POST['quantidade'])) {
                // Itera sobre cada elemento do $_POST['quantidade']
                foreach ($_POST['quantidade'] as $itemId => $quantidade) {
                    // Obtém a quantidade atual do item
                    $item = $db->dbSelect("SELECT quantidade FROM itens WHERE id = ?", 'first', [$itemId]);

                    // Verifica se a quantidade foi alterada
                    if ($item && $item->quantidade != $quantidade) {
                        // Atualiza a quantidade no banco de dados
                        $updateSuccess = $db->dbUpdate("UPDATE itens SET quantidade = ?, dataMod = NOW() WHERE id = ?", [$quantidade, $itemId]);

                        // Verifica se a atualização foi bem-sucedida
                        if (!$updateSuccess) {
                            // Redireciona para a página de erro se a atualização falhar
                            header('Location: listaItens.php?msgError=Erro ao atualizar a quantidade do item');
                            exit();
                        }
                    }
                }

                // Redireciona de volta para a página após a atualização bem-sucedida
                header('Location: listaItens.php?msgSucesso=Quantidades de itens atualizada com sucesso');
                exit();
            } else {
                // Redireciona para a página de erro se não houver itens para atualizar
                header('Location: listaItens.php?msgError=Nenhuma quantidade de item fornecida para atualização');
                exit();
            }
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: ' . $ex->getMessage() . "</p>";
        }
    }
?>
