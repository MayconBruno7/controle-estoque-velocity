<?php

    use App\Library\Formulario;

    echo Formulario::titulo('Fornecedores', true, false);

?>

<main class="container mt-5">

    <div class="row">
        <div class="col-12">
                <?= Formulario::exibeMsgError() ?>
            </div>

            <div class="col-12 mt-3">
                <?= Formulario::exibeMsgSucesso() ?>
            </div>
        </div>
    </div>

    <table id="listaFornecedor" class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aDados as $value) : ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    <td><?= $value['nome'] ?></td>
                    <td><?= Formulario::formatarCNPJInput($value['cnpj']) ?></td>
                    <td><?= Formulario::formatarTelefone($value['telefone']) ?></td>
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
</main>

<?= Formulario::getDataTables("listaFornecedor"); ?>