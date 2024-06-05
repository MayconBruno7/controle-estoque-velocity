<?php

    require_once "library/protectUser.php";
    require_once "library/Database.php";
    require_once "library/Funcoes.php";
    require_once "helpers/Formulario.php";

    
    $db = new Database();

    // Variável para armazenar os produtos disponíveis
    $produtos = [];

    // Se a ação for 'delete', redireciona para a página anterior
    if (isset($_GET['acao']) && $_GET['acao'] == 'delete') {
        // Listar todos os produtos disponíveis
        $produtos = $db->dbSelect(
            "SELECT 
                produtos.*, 
                (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
            FROM 
                produtos 
            WHERE 
                produtos.statusRegistro = 1 AND produtos.id = ?", 'all', [$_GET['id']]
        );

        // var_dump($_GET['id']);
        // var_dump($produtos);
        // exit; 
        if (!isset($_GET['id']) || !isset($_GET['id_movimentacoes']) || !isset($_GET['tipo']) || !isset($_GET['qtd_produto'])) {
            header("Location: formMovimentacoes.php?msgError=Dados inválidos para exclusão.&acao=insert");
            exit;
        }
    } else { 
        // Listar todos os produtos disponíveis
        $produtos = $db->dbSelect(
            "SELECT 
                produtos.*, 
                (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
            FROM 
                produtos 
            WHERE 
                produtos.statusRegistro = 1"
        );
    }
?>

<?php require_once "comuns/cabecalho.php"; ?>

<main class="container mt-5">
    <div class="row">
        <div class="col-12">
            <?= getMensagem(); ?>
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
                <th>Opções</th>
            </tr>
        </thead>   
        <tbody>
            <?php foreach ($produtos as $row) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nome'] ?></td>
                    <td><?= $row['quantidade'] ?></td>
                    <?php if (!isset($_GET["acao"])) : ?>
                        <td><?= isset($row['valor']) ? number_format($row['valor'], 2, ",", ".") : "" ?></td>
                    <?php endif; ?>
                    <td><?= getCondicao($row['condicao']) ?></td>
                    <td>
                        <?php if (isset($_GET["acao"]) && ($_GET['acao'] == 'insert' || $_GET['acao'] == 'update')) : ?>
                            <form id="form<?= $row['id'] ?>" action="inserirProdutoMovimentacao.php?acao=<?= $_GET['acao'] ?>" method="POST">
                                <div class="row mt-3">
                                    <div class="col">
                                        <label for="valor_<?= $row['id'] ?>" class="form-label">Valor</label>
                                        <input type="text" name="valor" id="valor_<?= $row['id'] ?>" class="form-control" disabled required>
                                    </div>
                                    <div class="col">
                                        <label for="quantidade_<?= $row['id'] ?>" class="form-label">Quantidade</label>
                                        <input type="number" name="quantidade" id="quantidade_<?= $row['id'] ?>" class="form-control" disabled required>
                                    </div>
                                    <div class="col">
                                        <input type="hidden" name="id_movimentacoes" value="<?= isset($_GET['id_movimentacoes']) ? $_GET['id_movimentacoes'] : '' ?>">
                                        <input type="hidden" name="tipo_movimentacoes" value="<?= isset($_GET['tipo']) ? $_GET['tipo'] : '' ?>">
                                        <input type="hidden" name="id_produto" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-primary mt-4" onclick="enableInputs(<?= $row['id'] ?>)">Adicionar</button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>

                        <?php if (isset($_GET["acao"]) && $_GET['acao'] == 'delete') : ?>
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

                        <?php if (!isset($_GET["acao"])) : ?>
                            <a href="formProdutos.php?acao=update&id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm" title="Alteração">Alterar</a>&nbsp;
                            <a href="formProdutos.php?acao=delete&id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                            <a href="formProdutos.php?acao=view&id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<script>
    // Função para habilitar campos de entrada quando o botão de adicionar é clicado
    function enableInputs(idProduto) {
        document.getElementById('valor_' + idProduto).removeAttribute('disabled');
        document.getElementById('quantidade_' + idProduto).removeAttribute('disabled');
    }
</script>

<?php
echo datatables('tbListaprodutos');
require_once "comuns/rodape.php";
?>
