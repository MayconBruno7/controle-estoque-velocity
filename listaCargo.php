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
        $dados = $db->dbSelect("SELECT * FROM cargo ORDER BY id");

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
                <?= getMensagem(); ?>
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
                        foreach ($dados as $row) {
                            ?>
                                <tr>
                                    <td> <?= $row['id'] ?> </td>
                                    <td> <?= $row['nome'] ?> </td>
                                    <td><?= getStatusDescricao($row['statusRegistro']) ?></td>
                                    <td>
                                        <a href="formCargo.php?acao=update&id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                                        <a href="formCargo.php?acao=delete&id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                                        <a href="formCargo.php?acao=view&id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
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

        echo datatables('tbListacargo');   
        require_once "comuns/rodape.php";

    ?>

    