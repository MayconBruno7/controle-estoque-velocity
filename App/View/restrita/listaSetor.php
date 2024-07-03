<?php

    use App\Library\Formulario;

?>

    <!-- Verifica e retorna mensagem de erro ou sucesso -->
    <main class="container">
        <?= Formulario::titulo('', true, false); ?>
        <div class="row">
            <div class="col-12">
                    <?= Formulario::exibeMsgError() ?>
                </div>

                <div class="col-12 mt-3">
                    <?= Formulario::exibeMsgSucesso() ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-center">Lista de Setores</div>
                <div class="card-body">
                    <table id="tbListasetor" class="table table-striped table-hover table-bordered table-responsive-sm display" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Id</th>
                                <th>Setor</th>
                                <th>Responsável</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        <thead>

                        <tbody>
                            <?php
                            foreach ($aDados as $value) {
                                ?>
                                <tr>
                                    <td><?= $value['id'] ?></td>
                                    <td><?= $value['nome'] ?></td>
                                    <td><?= !empty($value['nomeResponsavel']) ? $value['nomeResponsavel'] : 'Nenhum responsável encontrado' ?></td>
                                    <td><?= Formulario::getStatusDescricao($value['statusRegistro']) ?></td>
                                    <td>
                                        <?= Formulario::botao("view", $value['id']) ?>
                                        <?= Formulario::botao("update", $value['id']) ?>
                                        <?= Formulario::botao("delete", $value['id']) ?>
                                    </td>
                                </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?= Formulario::getDataTables('tbListasetor'); ?>