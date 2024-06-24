<?php

    use App\Library\Formulario;

?>

    <!-- Verifica e retorna mensagem de erro ou sucesso -->
    <main class="container">

        <?= Formulario::titulo('Estoque', true, false); ?>
        
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
                    <?php if (!$this->getAcao()) : ?>
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
                                <td> <?= !empty($value['quantidade']) ? $value['quantidade'] : 'Não encontrado' ?>
                                <?php if (!$this->getAcao()) : ?>
                                <td>
                                    <?= !empty($value['valor']) ? number_format($value['valor'], 2, ",", ".") : "Não encontrado" ?>
                                </td>
                                <?php endif; ?>
                                <td><?= Formulario::getCondicao($value['condicao']) ?></td>
                                <td><?= Formulario::getStatusDescricao($value['statusRegistro']) ?></td>
                                <td>
                                    <!-- <?php if ($this->getAcao() == 'insert' || $this->getAcao() == 'update') : ?>
                                        <form id="form<?= $value['id'] ?>" action="<?= ($this->getAcao() == 'update') ? baseUrl() . 'Movimentacao/update/updateProdutoMovimentacao/' . $this->getId() : baseUrl() . 'Movimentacao/insertProdutoMovimentacao/' . $this->getAcao() ?>" method="POST">
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
                                                    <input type="hidden" name="id_produto" value="<?= $value['id'] ?>">
                                                    <input type="hidden" name="id_movimentacao" value="<?= $this->getId() ?>">
                                                    <input type="hidden" name="tipo" value="<?= $this->getAcao() == 'insert' ? $this->getOutrosParametros(6) : $this->getOutrosParametros(4) ?>">
                                                    <button type="submit" class="btn btn-primary mt-4" onclick="enableInputs(<?= $value['id'] ?>)">Adicionar</button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?> -->

                                    <?php if ($this->getAcao() == 'delete') : ?>
                                        <form class="g-3" action="<?= baseUrl() ?>Movimentacao/deleteProdutoMovimentacao/<?= $this->getAcao() ?>" method="post">
                                            <p>Quantidade atual: <?= $this->getOutrosParametros(5) ?></p>
                                            <label for="quantidadeRemover" class="form-label">Quantidade</label>
                                            <input type="number" name="quantidadeRemover" id="quantidadeRemover" class="form-control" required></input>
                                            <input type="hidden" name="id_produto" value="<?= $this->getOutrosParametros(4) ?>">
                                            <input type="hidden" name="id_movimentacao" value="<?= $this->getId() ?>">
                                            <input type="hidden" name="tipo" value="<?= $this->getOutrosParametros(6) ?>">
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

    <?= Formulario::getDataTables('tbListaprodutos'); ?>

    <script>
        // Função para habilitar campos de entrada quando o botão de adicionar é clicado
        function enableInputs(idProduto) {
            document.getElementById('valor_' + idProduto).removeAttribute('disabled');
            document.getElementById('quantidade_' + idProduto).removeAttribute('disabled');
        }
    </script>





