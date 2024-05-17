<?php

    $login = 1;

    require_once "comuns/cabecalho.php";
    require_once "library/protectUser.php";
    require_once "library/Database.php";
    require_once "library/Funcoes.php";
    require_once "helpers/Formulario.php";

    try {
        
        $db = new Database();

        $id_produto = $_GET['id_produtos'];

        $dados = $db->dbSelect(
            "SELECT m.id as id_mov, 
                f.nome as nome_fornecedor, 
                m.tipo, m.data_pedido, 
                m.data_chegada, 
                (SELECT SUM(movi.quantidade) FROM movimentacoes_itens movi WHERE movi.id_movimentacoes = m.id) AS Quantidade, 
                (SELECT SUM(movi.valor) FROM movimentacoes_itens movi WHERE movi.id_movimentacoes = m.id) AS Valor 
            FROM movimentacoes m JOIN fornecedor f ON (f.id = m.id_fornecedor) 
            WHERE m.id IN (SELECT id_movimentacoes FROM movimentacoes_itens WHERE id_produtos = ?)", 
                'all', 
                [$id_produto]
            );

    } catch (Exception $ex) {
        echo json_encode(['movimentacoes.statusRegistro' => false, 'msgErro' => 'Erro interno ao processar a requisição']);
    }

    $nome_produto = $db->dbSelect(
        "SELECT nome FROM produtos WHERE id = ?", 'first', [$id_produto]
    );

?>

<title>Movimentações</title>

<main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <h2>Histórico de Movimentações: <?= $nome_produto->nome ?></h2>
            </div>
        </div>

    <div class="row">
        <div class="col-12">
            <?= getMensagem(); ?>
        </div>
    </div>

    <table id="tbListaprodutos" class="table table-striped table-hover table-bordered table-responsive-sm display align-items-center" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th>Pedido</th>
                <th>Fornecedor</th>
                <th>Tipo</th>
                <th>Data do Pedido</th>
                <th>Data da Chegada</th>
                <th>Quantidade</th>
                <th>R$ Total</th>
                <th>Opções</th>
            </tr>
        </thead>   

        <tbody>
        <?php
            if ($dados) {
                foreach ($dados as $row) {
                    ?>
                        <tr>
                            <td> <?= isset($row['id_mov']) ? $row['id_mov'] : 'Nenhuma movimentação encontrada' ?> </td>
                            <td> <?= isset($row['nome_fornecedor']) ? $row['nome_fornecedor'] : 'Nenhum fornecedor encontrado' ?> </td>
                            <td> <?= isset($row['tipo']) ? getTipoMovimentacao($row['tipo']) : 'Nenhum tipo de movimentação' ?></td>
                            <td> <?= isset($row['data_pedido']) ? date('d/m/Y', strtotime($row['data_pedido'])) : 'Nenhuma data de pedido encontrada' ?> </td>
                            <td> <?= isset($row['data_chegada']) ? date('d/m/Y', strtotime($row['data_chegada'])) : 'Nenhuma data de chegada encontrada' ?> </td>
                            <td> <?= isset($row['Quantidade']) ? number_format($row['Quantidade'], 2, ",", "."): 'Nenhuma '?> </td>
                            <td> <?= isset($row['Valor']) ? number_format($row['Valor'], 2, ",", "."): 'Nenhuma '?> </td>
                            <td>
                                <a href="formMovimentacoes.php?acao=view&id_movimentacoes=<?= $row['id_mov'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
                                <a href="formMovimentacoes.php?acao=update&id_movimentacoes=<?= $row['id_mov'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                                <a href="formMovimentacoes.php?acao=delete&id_movimentacoes=<?= $row['id_mov'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                            </td>
                        </tr>
                    <?php
                }
            } else {
                // echo "<tr><td colspan='6'>Nenhum registro encontrado.</td></tr>";
            }
        ?>
        </tbody>
    </table>
</main>

<?php

    echo datatables('tbListaprodutos');

    require_once "comuns/rodape.php";

?>

