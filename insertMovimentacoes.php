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
        $valor = (float)$_POST['valor'];
        $setor_id = (int)$_POST['setor_id'];
        $data_pedido = $_POST['data_pedido']; // corrigi a conversão para não ser inteiro
        $data_chegada = $_POST['data_chegada']; // corrigi a conversão para não ser inteiro
        $motivo = (int)$_POST['motivo'];
        $statusRegistro = (int)$_POST['statusRegistro'];
        $tipo_movimentacao = (int)$_POST['tipo_movimentacoes'];

        // Dados do produto
        $id_produtos = (int)$_POST['id_produto'];
        $quantidade = (int)$_POST['quantidade'];
        $valor_produto = (float)$_POST['valor']; // corrigi a conversão para float

        // Insere a movimentação
        $inserirMovimentacao = $db->dbInsert(
            "INSERT INTO movimentacoes(id_fornecedor, tipo, statusRegistro, id_setor, data_pedido, data_chegada, motivo) VALUES(?, ?, ?, ?, ?, ?, ?)",
            [$fornecedor_id, $tipo, $statusRegistro, $setor_id, $data_pedido, $data_chegada, $motivo]
        );

        // Obtém o ID da última movimentação inserida
        $idUltimaMovimentacao = $db->dbSelect("SELECT MAX(id) AS ultimo_id FROM movimentacoes");
        $idMovimentacaoAtual = $idUltimaMovimentacao[0]['ultimo_id'];

        // Obtém o item de movimentação, se existir
        $ItemMovimentacao = $db->dbSelect(
            "SELECT * FROM movimentacoes_itens WHERE id_movimentacoes = ? AND id_produtos = ?",
            'first',
            [$idMovimentacaoAtual, $id_produtos]
        );

        // Se o tipo de movimentação for adicionar ou remover
        if ($tipo_movimentacao == '1' || $tipo_movimentacao == '2') {
            
            // Obtém os dados do produto
            $produto = $db->dbSelect("SELECT * FROM produtos WHERE id = ?", 'first', [$id_produtos]);

            // Calcula a nova quantidade e valor do produto
            if ($tipo_movimentacao == '1') {
                $quantidadeEstoque = $produto->quantidade + $quantidade;
                $quantidadeAtual = ($ItemMovimentacao) ? $ItemMovimentacao->quantidade + $quantidade : $quantidade;
            } else {
                $quantidadeEstoque = $produto->quantidade - $quantidade;
                $quantidadeAtual = ($ItemMovimentacao) ? $ItemMovimentacao->quantidade - $quantidade : -$quantidade;
            }

            // Atualiza o item de movimentação, se existir
            if ($ItemMovimentacao) {
                $db->dbUpdate(
                    "UPDATE movimentacoes_itens SET quantidade = ?, valor = ? WHERE id_movimentacoes = ? AND id_produtos = ?",
                    [$quantidadeAtual, $valor_produto, $idMovimentacaoAtual, $id_produtos]
                );
            } else {
                // Se o item não existir na movimentação, insere um novo
                $inserirItemMovimentacao = $db->dbInsert(
                    "INSERT INTO movimentacoes_itens(quantidade, valor, id_movimentacoes, id_produtos) VALUES(?, ?, ?, ?)",
                    [$quantidadeAtual, $valor_produto, $idMovimentacaoAtual, $id_produtos]
                );
            }

            // Atualiza a quantidade do produto
            $db->dbUpdate("UPDATE produtos SET quantidade = ? WHERE id = ?", [$quantidadeEstoque, $id_produtos]);
        }

        // Redireciona para a página de lista de movimentações
        header("Location: listaMovimentacoes.php?msgSucesso=Movimentação inserida com sucesso");
        unset($_SESSION['movimentacao']);
        unset($_SESSION['produtos']);
        exit;

    } else {
        // Redireciona para o formulário de movimentações com mensagem de erro
        header("Location: formMovimentacoes.php?msgError=Não recebi dados do formulário&acao=insert");
        exit;
    }
?>
