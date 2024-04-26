<?php

    $login = 1;
    
    // carrega o cabecalho
    require_once "comuns/cabecalho.php";
    require_once "library/protectUser.php";
    require_once "library/Database.php";
    require_once "library/Funcoes.php";
    require_once "helpers/Formulario.php";
    
    try {
        
        // Criando o objeto Db para classe de base de dados
        $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbSelect("SELECT * FROM setor ORDER BY id_setor");

    // Se houver algum erro de conexão com o banco de dados será disparado pelo bloco catch
    } catch (Exception $ex) {
        echo json_encode(['status' => false, 'msgErro' => 'Erro interno ao processar a requisição']);
    }

?>

<title>Estoque</title>

<body>
    <!-- Verifica e retorna mensagem de erro ou sucesso -->
    <main class="container mt-5">
        <div class="row">
            <div class="col-12">
                <?php if (isset($_GET['msgSucesso'])): ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><?= $_GET['msgSucesso'] ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>

                <?php if (isset($_GET['msgError'])): ?>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?= $_GET['msgError'] ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php if (isset($_GET['msgSucessoEmail'])): ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><?= $_GET['msgSucessoEmail'] ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>

                <?php if (isset($_GET['msgErrorEmail'])): ?>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?= $_GET['msgErrorEmail'] ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>
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
                        foreach ($data as $row) {
                            ?>
                                <tr>
                                    <td> <?= $row['id_setor'] ?> </td>
                                    <td> <?= $row['nome_setor'] ?> </td>
                                    <td> <?= $row['responsavel_setor'] ?> </td>

                                    <td><?= getStatusDescricao($row['status_setor']) ?></td>
                                    <td>
                                        <a href="formSetor.php?acao=update&id_setor=<?= $row['id_setor'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                                        <a href="formSetor.php?acao=delete&id_setor=<?= $row['id_setor'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                                        <a href="formSetor.php?acao=view&id_setor=<?= $row['id_setor'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
                                    </td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </form>
    </main>
    
    <?php

        echo datatables('tbListasetor');

        require_once "comuns/rodape.php";

    ?>

    