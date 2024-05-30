<?php

    use App\Library\Formulario;

    echo Formulario::titulo('Estoque');

?>

    <!-- Verifica e retorna mensagem de erro ou sucesso -->
    <main class="container mt-5">

        <div class="row">
            <?php if (!isset($_GET["id_movimentacoes"])) : ?>
                <div class="col-12 d-flex justify-content-start">
                    <a href="formprodutos.php?acao=insert" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Inserir">Adicionar Produto</a>
                </div>
            <?php endif; ?>
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
        
        <!-- Parte de exibição da tabela -->
        <table id="tbListaprodutos" class="table table-striped table-hover table-bordered table-responsive-sm display align-items-center" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Nome Produto</th>
                    <th>Quantidade</th>
                    <?php if (!isset($_GET["acao"])) : ?>
                        <th>Valor</th>
                    <?php endif; ?>
                    <th>Estado do Produto</th>
                    <th>Status do produto</th>
                    <th>Opções</th>
                </tr>
            <thead>   

            <tbody>
                <?php
                    foreach ($aDados as $value) {
                        ?>
                            <tr>
                                <td> <?= $value['id'] ?> </td>
                                <td> <?= $value['nome'] ?> </td>
                                <td> <?= !empty($value['quantidade']) ? $value['quantidade'] : 'Nenhuma quantidade encontrada' ?>
                                <?php if (!isset($_GET["acao"])) : ?>
                                <td>
                                    <?= !empty($value['valor']) ? number_format($value['valor'], 2, ",", ".") : "Nenhum valor encontrado" ?>
                                </td>
                                <?php endif; ?>
                                <td><?= Formulario::getCondicao($value['condicao']) ?></td>
                                <td><?= Formulario::getStatusDescricao($value['statusRegistro']) ?></td>
                                <td>
                                    <?= Formulario::botao("view", $value['id']) ?>
                                    <?= Formulario::botao("update", $value['id']) ?>
                                    <?= Formulario::botao("delete", $value['id']) ?>
                                </td>
                            </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </main>


    <?= Formulario::getDataTables('listaProdutos'); ?>





