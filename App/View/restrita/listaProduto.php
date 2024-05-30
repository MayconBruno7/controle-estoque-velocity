<?php

use App\Library\Formulario;

echo Formulario::titulo('Produtos');

?>

<table class="table table-bordered table-striped table-hover table-sm" id="listaProdutos">
    <thead>
        <tr>
            <th>Id</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Saldo em Estoque</th>
            <th>Preço de Venda</th>
            <th>Promoção</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($aDados as $value) : ?>
            <tr>
                <td class="text-center"><?= $value['id'] ?></td>
                <td><?= $value['descricao'] ?></td>
                <td><?= $value['descricaoCategoria'] ?></td>
                <td class="text-end"><?= formataValor($value['saldoEmEstoque'], 3) ?></td>
                <td class="text-end"><?= formataValor($value['precoVenda'], 2) ?></td>
                <td class="text-end"><?= formataValor($value['precoPromocao'], 2) ?></td>
                <td><?= getStatus($value['statusRegistro']) ?></td>
                <td>
                    <?= Formulario::botao("view", $value['id']) ?>
                    <?= Formulario::botao("update", $value['id']) ?>
                    <?= Formulario::botao("delete", $value['id']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= Formulario::getDataTables('listaProdutos'); ?>