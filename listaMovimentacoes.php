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

        $dados = $db->dbSelect(
            "SELECT 
            m.id AS id_movimentacao, 
            f.nome AS nome_fornecedor, 
            m.tipo AS tipo_movimentacao, 
            m.data_pedido, 
            m.data_chegada
        FROM 
            movimentacoes m 
        LEFT JOIN 
            fornecedor f ON f.id = m.id_fornecedor 
        LEFT JOIN 
            movimentacoes_itens mi ON mi.id_movimentacoes = m.id
        LEFT JOIN 
            produtos p ON p.id = mi.id_produtos 
        WHERE 
            m.statusRegistro = 1;
        "
        );

    // Se houver algum erro de conexão com o banco de dados será disparado pelo bloco catch
    } catch (Exception $ex) {
        echo json_encode(['movimentacoes.statusRegistro' => false, 'msgErro' => 'Erro interno ao processar a requisição']);
    }

?>

<title>Movimentações</title>

<!-- Verifica e retorna mensagem de erro ou sucesso -->
<main class="container mt-5">

    <div class="row">
        <div class="col-12 d-flex justify-content-start">
            <a href="formMovimentacoes.php?acao=insert" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Inserir">Adicionar Movimentação</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?= getMensagem(); ?>
        </div>
    </div>

    <!-- Parte de exibição da tabela -->

    <table id="tbListaprodutos" class="table table-striped table-hover table-bordered table-responsive-sm display align-items-center" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th>Id</th>
                <th>Fornecedor</th>
                <th>Tipo</th>
                <th>Data do Pedido</th>
                <th>Data da Chegada</th>
                <th>Opções</th>
            </tr>
        <thead>   

        <tbody>
            <?php
                foreach ($dados as $row) {
                    ?>
                        <tr>
                            <td> <?= $row['id_movimentacao'] ?> </td>
                            <td> <?= $row['nome_fornecedor'] ?> </td>
                            <td> <?= getTipo($row['tipo_movimentacao']) ?></td>
                            <td> <?= formatarDataBrasileira($row['data_pedido']) ?> </td>
                            <td> <?= $row['data_chegada'] != '0000-00-00' ? formatarDataBrasileira($row['data_chegada']) : 'Nenhuma data encontrada' ?> </td>
                            <td>
                                <a href="formMovimentacoes.php?acao=update&id_movimentacoes=<?= $row['id_movimentacao'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Alterar">Alterar</a>&nbsp;
                                <a href="formMovimentacoes.php?acao=delete&id_movimentacoes=<?= $row['id_movimentacao'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                                <a href="formMovimentacoes.php?acao=view&id_movimentacoes=<?= $row['id_movimentacao'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
                            </td>
                        </tr>
                    
                    <?php
                }
            ?>
        </tbody>
    </table>
</main>

<?php

    echo datatables('tbListaprodutos');

    require_once "comuns/rodape.php";

?>

