<?php

    use App\Library\Formulario;

?>

<!-- Verifica e retorna mensagem de erro ou sucesso -->
<main class="container">
    <?= Formulario::titulo('', true, false); ?>
    <div class="row">
        <div class="col-12">
                <?= Formulario::exibeMsgError() ?>
            </div>

            <div class="col-12 mt-3">
                <?= Formulario::exibeMsgSucesso() ?>
            </div>
        </div>
    </div>

    <!-- Parte de exibição da tabela -->
    <div class="card">
        <div class="card-header d-flex justify-content-center">Lista de Movimentações</div>
            <div class="card-body">
                <table id="tbListaprodutos" class="table table-striped table-hover table-bordered table-responsive-sm display align-items-center" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Fornecedor</th>
                            <th>Tipo</th>
                            <th>Data do Pedido</th>
                            <th>Data da Chegada</th>
                            <th>Opções</th>
                        </tr>
                    <thead>   

                    <tbody>
                        <?php
                            foreach ($aDados as $row) {
                                ?>
                                    <tr>
                                        <td> <?= $row['id_movimentacao'] ?> </td>
                                        <td> <?= $row['nome_fornecedor'] ?> </td>
                                        <td> <?= Formulario::getTipo($row['tipo_movimentacao']) ?></td>
                                        <td> <?= Formulario::formatarDataBrasileira($row['data_pedido']) ?> </td>
                                        <td> <?= $row['data_chegada'] != '0000-00-00' ? Formulario::formatarDataBrasileira($row['data_chegada']) : 'Nenhuma data encontrada' ?> </td>
                                        <td>
                                            <?= Formulario::botao("view", $row['id_movimentacao']) ?>
                                            <?= Formulario::botao("update", $row['id_movimentacao']) ?>
                                            <?= Formulario::botao("delete", $row['id_movimentacao']) ?>
                                        </td>
                                    </tr>
                                
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?= Formulario::getDataTables('tbListaprodutos'); ?>
