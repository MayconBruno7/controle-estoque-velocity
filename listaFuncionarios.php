<?php

    $login = 1;
    
    // carrega o cabecalho
    require_once "comuns/cabecalho.php";
    require_once "library/protectUser.php";
    require_once "library/Database.php";
    require_once "library/Funcoes.php";
    require_once "helpers/Formulario.php";

    // Criando o objeto Db para classe de base de dados
    $db = new Database();
    
    try {
        // preparação da query que será executada no banco de dados
        $dados = $db->dbSelect("SELECT funcionarios.*, setor.nome_setor AS nome_do_setor FROM funcionarios JOIN setor ON funcionarios.setor_funcionarios = setor.id_setor ORDER BY id_funcionarios");
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

        <!-- Parte de exibição da tabela -->
        <form>
            
            <a href="formfuncionarios.php?acao=insert" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Inserir">Adicionar funcionarios</a>&nbsp;

            <table id="tbListafuncionarios" class="table table-striped table-hover table-bordered table-responsive-sm display" style="width:100%">
                <thead class="table-dark">
                    <tr>
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
                                    <td> <?= $row['nome_funcionarios'] ?> </td>
                                    <td> <?= $row['salario_funcionario'] ?> </td>
                                    <td> <?= $row['nome_do_setor'] ? : "Nenhum setor encontrado" ?> </td>

                                    <td><?= getStatusDescricao($row['status_funcionarios']) ?></td>
                                    <td>
                                        <a href="formfuncionarios.php?acao=update&id_funcionarios=<?= $row['id_funcionarios'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                                        <a href="formfuncionarios.php?acao=delete&id_funcionarios=<?= $row['id_funcionarios'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                                        <a href="formfuncionarios.php?acao=view&id_funcionarios=<?= $row['id_funcionarios'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
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
        echo datatables('tbListafuncionarios');
        require_once "comuns/rodape.php";

    ?>

    