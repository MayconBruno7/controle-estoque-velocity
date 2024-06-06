<?php

    use App\Library\Formulario;

    echo Formulario::titulo('Estoque', true, false);

?>

    <!-- Verifica e retorna mensagem de erro ou sucesso -->
    <main class="container mt-5">

        <!-- <div class="row">
            <?php if (!isset($_GET["id_movimentacoes"])) : ?>
                <div class="col-12 d-flex justify-content-start">
                    <a href="formprodutos.php?acao=insert" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Inserir">Adicionar Produto</a>
                </div>
            <?php endif; ?>
        </div> -->

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
                                <?php if ($this->getAcao()) : ?>
                                <td>
                                    <?= !empty($value['valor']) ? number_format($value['valor'], 2, ",", ".") : "Nenhum valor encontrado" ?>
                                </td>
                                <?php endif; ?>
                                <td><?= Formulario::getCondicao($value['condicao']) ?></td>
                                <td><?= Formulario::getStatusDescricao($value['statusRegistro']) ?></td>
                                <td>
                                    <?php if ($this->getAcao() == 'insert' || $this->getAcao() == 'update') : ?>
                                        <form id="form<?= $value['id'] ?>" action="inserirProdutoMovimentacao.php?acao=<?= $this->getAcao() ?>" method="POST">
                                            <div class="row mt-3">
                                                <div class="col">
                                                    <label for="valor_<?= $value['id'] ?>" class="form-label">Valor</label>
                                                    <input type="text" name="valor" id="valor_<?= $value['id'] ?>" class="form-control" disabled required>
                                                </div>
                                                <div class="col">
                                                    <label for="quantidade_<?= $value['id'] ?>" class="form-label">Quantidade</label>
                                                    <input type="number" name="quantidade" id="quantidade_<?= $value['id'] ?>" class="form-control" disabled required>
                                                </div>
                                                <div class="col">
                                                    <input type="hidden" name="id_movimentacoes" value="<?= isset($_GET['id_movimentacoes']) ? $_GET['id_movimentacoes'] : '' ?>">
                                                    <input type="hidden" name="tipo_movimentacoes" value="<?= isset($_GET['tipo']) ? $_GET['tipo'] : '' ?>">
                                                    <input type="hidden" name="id_produto" value="<?= $value['id'] ?>">
                                                    <button type="submit" class="btn btn-primary mt-4" onclick="enableInputs(<?= $value['id'] ?>)">Adicionar</button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($this->getAcao() == 'delete') : ?>
                                        <form class="g-3" action="deleteProdutoMovimentacao.php" method="post">
                                            <p>Quantidade atual: <?= $_GET['qtd_produto'] ?></p>
                                            <label for="quantidadeRemover" class="form-label">Quantidade</label>
                                            <input type="number" name="quantidadeRemover" id="quantidadeRemover" class="form-control" required></input>
                                            <input type="hidden" name="id_produto" value="<?= $_GET['id'] ? $_GET['id'] : "" ?>">
                                            <input type="hidden" name="id_movimentacao" value="<?= $_GET['id_movimentacoes'] ?>">
                                            <input type="hidden" name="tipo_movimentacoes" value="<?= $_GET['tipo'] ?>">
                                            <button type="submit" class="btn btn-primary btn-sm mt-2">Remover</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if (!$this->getAcao()) : ?>
                                        <?= Formulario::botao("view", $value['id']) ?>
                                        <?= Formulario::botao("update", $value['id']) ?>
                                        <?= Formulario::botao("delete", $value['id']) ?>
                                    <?php endif; ?>
                                </td>
                            
                            </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </main>


    <?= Formulario::getDataTables('listaProdutos'); ?>





