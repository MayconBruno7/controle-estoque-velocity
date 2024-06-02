<?php

    use App\Library\Formulario;

    // $dadosFuncionarios = $db->dbSelect("SELECT * FROM funcionarios");

    echo Formulario::titulo('Estoque', true, false);
?>

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
            <a href="formSetor.php?acao=insert" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Inserir">Adicionar setor</a>&nbsp;

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
                        // Obtém o responsável deste setor
                        // $responsavel = $db->dbSelect("SELECT nome FROM funcionarios WHERE id = ?", 'first', [$row['responsavel']]);
                        ?>
                        <tr>
                            <td><?= $value['id'] ?></td>
                            <td><?= $value['nome'] ?></td>
                            <td>Não encontrado</td>
                            <!-- <td><?= $responsavel ? $responsavel->nome : "Nenhum responsável selecionado" ?></td> -->
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
