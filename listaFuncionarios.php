<?php

    $login = 1;
    
    require_once "library/protectNivel.php";
    require_once "comuns/cabecalho.php";
    require_once "library/Database.php";
    require_once "library/Funcoes.php";
    require_once "helpers/Formulario.php";

    // Criando o objeto Db para classe de base de dados
    $db = new Database();
    
    try {
        // preparação da query que será executada no banco de dados
        $dados = $db->dbSelect("SELECT funcionarios.*, setor.nome AS nome_do_setor FROM funcionarios LEFT JOIN setor ON funcionarios.setor = setor.id ORDER BY id");
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
                <?= getMensagem(); ?>
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
                                    <td> <?= $row['nome'] ?> </td>
                                    <td> R$ <?= number_format($row['salario'], 2, ',', '.') ?></td>
                                    <td> <?= $row['nome_do_setor'] ? : "Nenhum setor selecionado" ?> </td>

                                    <td><?= getStatusDescricao($row['statusRegistro']) ?></td>
                                    <td>
                                        <a href="formfuncionarios.php?acao=update&id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                                        <a href="formfuncionarios.php?acao=delete&id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                                        <a href="formfuncionarios.php?acao=view&id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
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

    