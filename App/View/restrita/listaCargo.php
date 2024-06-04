<?php

    use App\Library\Formulario;

    echo Formulario::titulo('Cargos', true, false);

?>

<body>
    <!-- Verifica e retorna mensagem de erro ou sucesso -->
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

        <!-- Parte de exibição da tabela -->
        <form>
            <a href="formCargo.php?acao=insert" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Inserir">Adicionar cargo</a>&nbsp;

            <table id="tbListacargo" class="table table-striped table-hover table-bordered table-responsive-sm display" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Cargo</th>
                        <th>Estado do Cargo</th>
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
        </form>
    </main>

    