<?php

    require_once "library/Database.php";
    require_once "library/Funcoes.php";

    // Carrega cabeçalho HTML (header, nav, etc...)
    require_once "comuns/cabecalho.php";

    // carregando os helpers
    require_once "helpers/Formulario.php";

    if (!Funcoes::userLogado()) {
        return header("Location: viewLogin.php");
    }

    // Criando o objeto Db para classe de base de dados
    $db = new Database();

    // Buscar a lista de Rotas na base de dados

    $data = $db->dbSelect("SELECT * FROM usuario ORDER BY nome");

    require_once "library/protectNivel.php";
    
    ?>

    <main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <h2>Lista de Usuários</h2>
            </div>
            <div class="col-2 text-end">
                <a href="formUsuario.php?acao=insert" class="btn btn-outline-success btn-sm" title="Novo">Nova</a>
            </div>

        </div>

        <?= getMensagem() ?>

        <table id="tbListaUsuario" class="table table-striped table-hover table-bordered table-responsive-sm">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Nível</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </<thead>
            <tbody>
                <?php
                    foreach ($data as $row) {
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['nome'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= getNivelDescricao($row['nivel']) ?></td>
                            <td><?= getStatusDescricao($row['statusRegistro']) ?></td>
                            <td>
                                <a href="formUsuario.php?acao=update&id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm" title="Alteração">Alterar</a>&nbsp;
                                <a href="formUsuario.php?acao=delete&id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                <a href="formUsuario.php?acao=view&id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
            </tbody>
            
        </table>

    </main>

    <?php

    echo datatables("tbListaUsuario");

    // Carrega o ropdapé HTML
    require_once "comuns/rodape.php";