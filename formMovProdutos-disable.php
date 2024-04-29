<?php

// require_once "helpers/protectUser.php";
require_once "helpers/Formulario.php";
require_once "comuns/cabecalho.php";
require_once "library/Database.php";
require_once "library/Funcoes.php";

$db = new Database();
$dados = [];

$id_movimentacao = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_movimentacao) {
    // Busca os dados da movimentação
    $dados = $db->dbSelect("SELECT * FROM movimentacoes WHERE id = ?", 'first', [$id_movimentacao]);
}

$produtos = $db->dbSelect("SELECT * FROM produtos ORDER BY descricao");

$movimentacoes_itens = $db->dbSelect("SELECT valor FROM movimentacoes_itens");

?>
<main class="container mt-5">

    <div class="row">
        <div class="col-2 text-end">
            <?php if (isset($_GET["id_movimentacoes"])) : /* botão gravar não é exibido na visualização dos dados */ ?>
                <button class="btn btn-outline-primary btn-sm" onclick="goBack()">Voltar</button>
            <?php endif; ?>
            <?php if (!isset($_GET["id_movimentacoes"])) : /* botão gravar não é exibido na visualização dos dados */ ?>
            <a href="formProduto.php?acao=insert" class="btn btn-outline-success btn-sm" title="Novo">Nova</a>
            <?php endif; ?>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <?php if (isset($_GET['msgSucesso'])) : ?>

                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?= $_GET['msgSucesso'] ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

            <?php endif; ?>

            <?php if (isset($_GET['msgError'])) : ?>

                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?= $_GET['msgError'] ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['acao']) && $_GET['acao'] == 'close') : ?>
        <form class="g-3" action="fechaComanda.php?idComanda=<?= $_GET['idComanda'] ?>" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

            <div class="row">

                <!-- <div class="col-12">
                    <label for="descricao" class="form-label">Nome do cliente</label>
                    <input type="text" class="form-control" name="descricao" id="descricao" placeholder="Descrição do produto" required maxlength="50" value="<?= isset($dados->DESCRICAO_COMANDA) ? $dados->DESCRICAO_COMANDA : "" ?>">
                </div>

                <div class="col-6 mt-3">
                    <label for="ID_COMANDA" class="form-label">Numero da comanda</label>
                    <input type="text" class="form-control" name="ID_COMANDA" id="ID_COMANDA" placeholder="Descrição do produto" required maxlength="50" value="<?= isset($dados->ID_COMANDA) ? $dados->ID_COMANDA : "" ?>">
                </div>

                <div class="col-6 mt-3 mb-3">
                    <label for="FORMA_PAGAMENTO" class="form-label">Forma de pagamento</label>
                    <select name="FORMA_PAGAMENTO" id="FORMA_PAGAMENTO" class="form-control" required>
                        <option value="" <?= isset($finComanda->PAGAMENTO_ID_PAGAMENTO) ? $finComanda->PAGAMENTO_ID_PAGAMENTO == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($finComanda->PAGAMENTO_ID_PAGAMENTO) ? $finComanda->PAGAMENTO_ID_PAGAMENTO == 1 ? "selected" : "" : "" ?>>Dinheiro</option>
                        <option value="2" <?= isset($finComanda->PAGAMENTO_ID_PAGAMENTO) ? $finComanda->PAGAMENTO_ID_PAGAMENTO == 2 ? "selected" : "" : "" ?>>Cartão de crédito</option>
                        <option value="3" <?= isset($finComanda->PAGAMENTO_ID_PAGAMENTO) ? $finComanda->PAGAMENTO_ID_PAGAMENTO == 3 ? "selected" : "" : "" ?>>Cartão de débito</option>
                        <option value="4" <?= isset($finComanda->PAGAMENTO_ID_PAGAMENTO) ? $finComanda->PAGAMENTO_ID_PAGAMENTO == 4 ? "selected" : "" : "" ?>>Pix</option>
                    </select>
                </div> -->
            </div>
        <?php endif; ?>
        <table id="tbListaProduto" class="table table-striped table-hover table-bordered table-responsive-sm mt-3">
        <thead>
                <tr>
                    <th>Id movimentação</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unidade</th>
                    <th>Valor Total Produto</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($produtos as $produto) {
                    $movimentacao_item = $db->dbSelect("SELECT valor FROM movimentacoes_itens WHERE id_produtos = ?", 'first', [$produto['id']]);
                    ?>
                    <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= $produto['nome'] ?></td>
                        <td><?= $produto['quantidade'] ?></td>
                        <td><?= isset($movimentacao_item->valor) ? $movimentacao_item->valor : 'N/A' ?></td>
                        <td><?= $produto['quantidade'] * (isset($movimentacao_item->valor) ? $movimentacao_item->valor : 0) ?></td>

                        <td>
                            <a href="listaProduto.php?acao=delete&id_produtos=<?= $produto['id'] ?>&qtd_produto=<?= $produto['quantidade'] ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                            <a href="produtos.php?acao=view&id_produtos=<?= $produto['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <!-- <input type="hidden" name="totalComanda" value="<?= $total ?>">
 -->
        <p><h2 align='center'>Valor Total: R$ 20</h2></p>

        <div class="container text-center mt-5">
            <?php if (isset($_GET["idComanda"])) : /* botão gravar não é exibido na visualização dos dados */ ?>
                <a class="btn btn-primary" href="listaComanda.php">Voltar</a>
            <?php endif; ?>

            <?php if (isset($_GET['acao']) && $_GET['acao'] == 'close') : ?>
                <button type="submit" class="btn btn-primary"><?= ($_GET['situacaoComanda']) == 1 ? 'Fechar comanda' : 'Abrir comanda' ?></button>
            <?php endif; ?>
        </div>

        </form>

        
<script>

function goBack() {
    window.history.back();
}

</script>
        <?php
        echo datatables("tbListaProduto");
        // Carrega o rodapé HTML
        require_once "comuns/rodape.php";
        ?>

