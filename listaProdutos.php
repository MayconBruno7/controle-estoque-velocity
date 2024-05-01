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
     
        // Adicionar item à comanda: listar todos os produtos disponíveis
        $produtos = $db->dbSelect(
            "SELECT 
                produtos.*, 
                (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
            FROM 
                produtos"
        );
            
    // Se houver algum erro de conexão com o banco de dados será disparado pelo bloco catch
    } catch (Exception $ex) {
        echo json_encode(['produtos.statusRegistro' => false, 'msgErro' => 'Erro interno ao processar a requisição']);
    }

?>

    <title>Estoque</title>

    <!-- Verifica e retorna mensagem de erro ou sucesso -->
    <main class="container mt-5">

        <div class="row">
            <?php if (!isset($_GET["id_movimentacoes"])) : ?>
                <div class="col-12 d-flex justify-content-start">
                    <a href="formprodutos.php?acao=insert" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Inserir">Adicionar Produto</a>
                </div>
            <?php endif; ?>
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
                    <th>Nome Produto</th>
                    <th>Quantidade</th>
                    <?php if (!isset($_GET["acao"])) : ?>
                        <th>Valor</th>
                    <?php endif; ?>
                    <th>Estado do Produto</th>
                    <th>Status do produto</th>
                    <th>Opções</th>
                </tr>
            <thead>   

            <tbody>
                <?php
                    foreach ($produtos as $row) {
                        ?>
                            <tr>
                                <td> <?= $row['id'] ?> </td>
                                <td> <?= $row['nome'] ?> </td>
                                <td> <?= !empty($row['quantidade']) ? $row['quantidade'] : 'Nenhuma quantidade encontrada' ?>
                                <?php if (!isset($_GET["acao"])) : ?>
                                <td>
                                    <?= !empty($row['valor']) ? $row['valor'] : 'Nenhum valor encontrado' ?>
                                </td>
                                <?php endif; ?>
                                <td><?= getCondicao($row['condicao']) ?></td>
                                <td><?= getStatusDescricao($row['statusRegistro']) ?></td>
                                <td>
                                    <a href="formProdutos.php?acao=update&id=<?=$row['id'] ?>" class="btn btn-outline-primary btn-sm" title="Alteração">Alterar</a>&nbsp;
                                    <a href="formProdutos.php?acao=delete&id=<?=$row['id'] ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                    <a href="formProdutos.php?acao=view&id=<?=$row['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
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

    