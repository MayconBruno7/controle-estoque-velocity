<?php



    // $nome_produto = $db->dbSelect(
    //     "SELECT nome FROM produtos WHERE id = ?", 'first', [$id_produto]
    // );

    use App\Library\Formulario;

?>

<title>Movimentações</title>

<main class="container mt-5">

        <div class="row">
            <div class="col-10">
            <!-- <?= $nome_produto->nome ?> -->
                <h2>Histórico de Movimentações: </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                    <?= Formulario::exibeMsgError() ?>
                </div>

                <div class="col-12 mt-3">
                    <?= Formulario::exibeMsgSucesso() ?>
                </div>
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
                            <td> <?= isset($row['tipo']) ? Formulario::getTipoMovimentacao($row['tipo']) : 'Nenhum tipo de movimentação' ?></td>
                            <td> <?= isset($row['data_pedido']) ? date('d/m/Y', strtotime($row['data_pedido'])) : 'Nenhuma data de pedido encontrada' ?> </td>
                            <td> <?= isset($row['data_chegada']) ? date('d/m/Y', strtotime($row['data_chegada'])) : 'Nenhuma data de chegada encontrada' ?> </td>
                            <td> <?= isset($row['Quantidade']) ? number_format($row['Quantidade'], 2, ",", "."): 'Nenhuma '?> </td>
                            <td> <?= isset($row['Valor']) ? number_format($row['Valor'], 2, ",", "."): 'Nenhuma '?> </td>
                            <td>
                                <a href="<?= baseUrl() ?>Movimentacao/Form/view/<?= $row['id_mov'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
                                <a href="<?= baseUrl() ?>Movimentacao/Form/update/<?= $row['id_mov'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                                <a href="<?= baseUrl() ?>Movimentacao/Form/<?= $row['id_mov'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                            </td>
                        </tr>
                    <?php
                }
            } 
        ?>
        </tbody>
    </table>
</main>

<?= Formulario::getDataTables("listaProdutos"); ?>



