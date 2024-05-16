<?php
    session_start();

    require_once "library/Database.php";
    $db = new Database();
    
    // Verifica se todos os campos do formulário foram enviados
    if (
        isset($_POST['fornecedor_id'], $_POST['tipo'], $_POST['statusRegistro'], $_POST['setor_id'],
        $_POST['data_pedido'], $_POST['data_chegada'], $_POST['motivo'], $_POST['statusRegistro'], $_POST['tipo_movimentacoes'])
    ) {
    
        // Dados da movimentação
        $fornecedor_id = (int)$_POST['fornecedor_id'];
        $tipo = (int)$_POST['tipo'];
        $setor_id = (int)$_POST['setor_id'];
        $data_pedido = $_POST['data_pedido'];
        $data_chegada = $_POST['data_chegada'];
        $motivo = $_POST['motivo'];
        $statusRegistro = (int)$_POST['statusRegistro'];
        $tipo_movimentacao = (int)$_POST['tipo_movimentacoes'];
    
        // Dados do produto
        $id_produto = $_POST['id_produto'];
        $quantidades = $_POST['quantidade'];
        $valores_produtos = $_POST['valor'];
    
        // Verifica se a quantidade de todos os produtos é válida
        foreach ($_SESSION['movimentacao'][0]['produtos'] as $key => $produto) {
            $id_produto = $produto['id_produto'];
            $quantidade = (int)$produto['quantidade'];
            $produtos = $db->dbSelect("SELECT * FROM produtos WHERE id = ?", 'first', [$id_produto]);
    
            if ($tipo_movimentacao == 2 && $produtos->quantidade - $quantidade < 0) {
                header('Location: formMovimentacoes.php?acao=insert&msgError=Movimentação de saída quantidade do produto não pode ficar negativa!');
                exit;
            }
        }
    
        // Insere a movimentação
        $inserirMovimentacao = $db->dbInsert(
            "INSERT INTO movimentacoes(id_fornecedor, tipo, statusRegistro, id_setor, data_pedido, data_chegada, motivo) VALUES(?, ?, ?, ?, ?, ?, ?)",
            [$fornecedor_id, $tipo, $statusRegistro, $setor_id, $data_pedido, $data_chegada, $motivo]
        );
    
        if ($inserirMovimentacao) {
            // Obtém o ID da última movimentação inserida
            $idUltimaMovimentacao = $db->dbSelect("SELECT MAX(id) AS ultimo_id FROM movimentacoes")[0]['ultimo_id'];
    
            // Insere os itens da movimentação
            foreach ($_SESSION['movimentacao'][0]['produtos'] as $key => $produto) {
                $id_produto = $produto['id_produto'];
                $quantidade = (int)$produto['quantidade'];
                $valor_produto = (float)$produto['valor'];
    
                $inserirItemMovimentacao = $db->dbInsert(
                    "INSERT INTO movimentacoes_itens(id_movimentacoes, id_produtos, quantidade, valor) VALUES (?, ?, ?, ?)",
                    [$idUltimaMovimentacao, $id_produto, $quantidade, $valor_produto]
                );
    
                // Atualiza o estoque do produto
                if ($tipo_movimentacao == 1) {
                    $db->dbUpdate("UPDATE produtos SET quantidade = quantidade + ? WHERE id = ?", [$quantidade, $id_produto]);
                } elseif ($tipo_movimentacao == 2) {
                    $db->dbUpdate("UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?", [$quantidade, $id_produto]);
                }
            }
    
            // Redirecionar de volta para a página de movimentações
            header("Location: listaMovimentacoes.php?msgSucesso=Movimentação inserida com sucesso");
            // limpa a sessão
            unset($_SESSION['movimentacao']);
            exit;
        }
    }
    
?>
