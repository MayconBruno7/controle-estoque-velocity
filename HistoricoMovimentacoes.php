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

        $dados = $db->dbSelect("SELECT m.id as id_mov, f.nome as nome_fornecedor, m.tipo, m.data_pedido, m.data_chegada FROM movimentacoes m JOIN fornecedor f ON (f.id = m.id_fornecedor) WHERE m.id IN (SELECT id_movimentacoes FROM movimentacoes_itens WHERE id_produtos = ?)", 'all', [$id_produto]);

    } catch (Exception $ex) {
        echo json_encode(['movimentacoes.statusRegistro' => false, 'msgErro' => 'Erro interno ao processar a requisição']);
    }
?>

<title>Movimentações</title>

<main class="container mt-5">

<div class="row">
    <div class="col-12">
        <?php if (isset($_GET['msgSucesso'])): ?>

            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?= $_GET['msgSucesso'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php endif; ?>

        <?php if (isset($_GET['msgError'])): ?>

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?= $_GET['msgError'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php endif; ?>
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
            <th>Opções</th>
        </tr>
    </thead>   

    <tbody>
    <?php
        if ($dados) {
            foreach ($dados as $row) {
                ?>
                    <tr>
                        <td> <?= $row['id_mov'] ?> </td>
                        <td> <?= $row['nome_fornecedor'] ?> </td>
                        <td><?= getTipoMovimentacao($row['tipo']) ?></td>
                        <td> <?= date('d/m/Y', strtotime($row['data_pedido'])) ?> </td>
                        <td> <?= date('d/m/Y', strtotime($row['data_chegada'])) ?> </td>
                        <td>
                            <a href="formMovimentacoes.php?acao=view&id=<?= $row['id_mov'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
                            <a href="formMovimentacoes.php?acao=update&id=<?= $row['id_mov'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                            <a href="formMovimentacoes.php?acao=delete&id=<?= $row['id_mov'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                        </td>
                    </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6'>Nenhum registro encontrado.</td></tr>";
        }
    ?>
</tbody>
</table>

</main>

<?php

echo datatables('tbListaprodutos');

require_once "comuns/rodape.php";

?>

