<?php

use App\Library\Formulario;

?>

<script type="text/javascript" src="<?= baseUrl() ?>assets/DataTables/datatables.min.js"></script>

<?= Formulario::titulo("Usuários") ?>

<table id="tbListaUsuario" class="table table-hover table-bordered table-striped table-sm">
    <thead>
        <tr class="text-weigth-bold">
            <th>Nome</th>
            <th>E-mail</th>
            <th>Nível</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dados as $value): ?>
            <tr>
                <td><?= $value['nome'] ?></td>
                <td><?= $value['email'] ?></td>
                <td><?= ($value['nivel'] == 1 ? "Administrador" : "Visitante") ?></td>
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

<?= Formulario::getDataTables("tbListaUsuario") ?>