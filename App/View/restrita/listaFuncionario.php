<?php

    use App\Library\Formulario;

?>


    <main class="container">
        <?= Formulario::titulo('Funcionários', true, false); ?>
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
            
            <table id="tbListafuncionarios" class="table table-striped table-hover table-bordered table-responsive-sm display" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Salário</th>
                        <th>Setor</th>
                        <th>Status</th>
                        <th>Opções</th>
                    </tr>
                <thead>   

                <tbody>
                    <?php
                        foreach ($dados as $row) {
                            ?>
                                <tr>
                                <td> <?= $row['id'] ?> </td>
                                <td> <?= $row['nome'] ?> </td>
                                <td> R$ <?= number_format($row['salario'], 2, ',', '.') ?></td>
                                    <td> <?= $row['nome_do_setor'] ? : "Nenhum setor selecionado" ?> </td>

                                    <td><?= Formulario::getStatusDescricao($row['statusRegistro']) ?></td>
                                    <td>
                                        <?= Formulario::botao("view", $row['id']) ?>
                                        <?= Formulario::botao("update", $row['id']) ?>
                                        <?= Formulario::botao("delete", $row['id']) ?>
                                    </td>
                                </tr>
                                
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </form>
    </main>
    
    <?= Formulario::getDataTables("tbListafuncionarios"); ?>