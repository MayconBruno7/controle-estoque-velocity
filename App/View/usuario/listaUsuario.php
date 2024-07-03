<?php

    use App\Library\Formulario;

?>

<main class="container">
    <?= Formulario::titulo("") ?>

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-center">Lista de Usuários</div>
            <div class="card-body">
                <table id="tbListaUsuario" class="table table-striped table-hover table-bordered table-responsive-sm display">
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
                                <td><?= ($value['nivel'] == 1 ? "Administrador" : "Usuário") ?></td>
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
            </div>
        </div>
    </div>
</main>

<?= Formulario::getDataTables("tbListaUsuario") ?>