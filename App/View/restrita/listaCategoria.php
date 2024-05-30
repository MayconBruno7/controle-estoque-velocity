<?php

use App\Library\Formulario;

echo Formulario::titulo('Categorias');

?>

<table id="listaCategoria" class="table table-bordered table-striped table-hover table-sm">
    <thead>
        <tr>
            <th>Id</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($aDados as $value) : ?>
            <tr>
                <td><?= $value['id'] ?></td>
                <td><?= $value['descricao'] ?></td>
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

<?= Formulario::getDataTables("listaCategoria"); ?>