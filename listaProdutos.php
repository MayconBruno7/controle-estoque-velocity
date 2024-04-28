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

            if (!isset($_GET['acao'])) {
        
                $produtos = $db->dbSelect(
                    "SELECT 
                        produtos.*, 
                        (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
                    FROM 
                        produtos 
                    WHERE 
                        produtos.statusRegistro = 1"
                );
                
                //    var_dump($produtos);
                
            } else {
        
                if ($_GET['acao'] == 'insert') {
                    // Adicionar item à comanda: listar todos os produtos disponíveis
                    $produtos = $db->dbSelect(
                        "SELECT 
                            produtos.*, 
                            (SELECT valor FROM movimentacoes_itens WHERE id_produtos = produtos.id LIMIT 1) AS valor
                        FROM 
                            produtos 
                        WHERE 
                            produtos.statusRegistro = 1"
                    );
                } 
                // else if ($_GET['acao'] == 'delete') {
                //     // Remover item da comanda: listar apenas os produtos na comanda
                //    $produtoss = $db->dbSelect(
        
                //         "SELECT 
                //             p.*, 
                //             pc.descricao_categoria AS categoriaDescricao,
                //             COALESCE(SUM(ic.QUANTIDADE), 0) as totalQuantidade
                //         FROM 
                //             produto AS p
                //         INNER JOIN 
                //             produto_categoria as pc ON pc.ID_CATEGORIA = p.ID_PRODUTO_CATEGORIA
                //         LEFT JOIN 
                //             itens_comanda ic ON ic.PRODUTOS_ID_PRODUTOS = p.ID_PRODUTOS AND ic.COMANDA_ID_COMANDA = ?
                //         WHERE 
                //             ic.COMANDA_ID_COMANDA IS NOT NULL
                //         GROUP BY 
                //             p.ID_PRODUTOS
                //         ORDER BY 
                //             p.descricao",
                //         'all',
                //         [$_GET['idComanda']]
        
                //     );
                // }
            }
        // Se houver algum erro de conexão com o banco de dados será disparado pelo bloco catch
        } catch (Exception $ex) {
            echo json_encode(['produtos.statusRegistro' => false, 'msgErro' => 'Erro interno ao processar a requisição']);
        }

        
        // var_dump($dados)
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
        <!-- <form action="updateQuantidade.php" method="POST"> -->

        <form method="POST">
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
                                    <td> <?= $row['quantidade'] ?>
                                    <?php if (!isset($_GET["acao"])) : ?>
                                    <td>
                                        <?= $row['valor'] ?>
                                    </td>
                                    <?php endif; ?>

                                    <td><?= getCondicao($row['condicao']) ?></td>

                                    <td>
                                        <?php if (isset($_GET["acao"]) && $_GET['acao'] == 'insert') : ?>
                                            <form class="g-3" action="inserirProdutoMovimentacao.php" method="post">
                                                <label for="valor" class="form-label">Valor</label>
                                                <input type="text" name="valor" id="valor" class="form-control" required>
                                                
                                                <label for="quantidade" class="form-label mt-2">Quantidade Adicionada</label>
                                                <input type="number" name="quantidade" id="quantidade" class="form-control" required>

                                                <input type="hidden" name="id_produto" value="<?=$row['id'] ?>">
                                                <input type="hidden" name="id_movimentacoes" value="<?= $_GET['id_movimentacoes'] ?>">
                                                <input type="hidden" name="tipo_movimentacoes" value="<?= $_GET['tipo'] ?>">

                                                <button type="submit" class="btn btn-primary btn-sm mt-2">Adicionar</button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if (isset($_GET["acao"]) && $_GET['acao'] == 'delete') : ?>
                                            <form class="g-3" action="deleteProdutoMovimentacao.php" method="post" enctype="multipart/form-data">
                                                <p>Quantidade atual: <?= $_GET['quantidade'] ?> </p>
                                                <label for="quantidadeRemover" class="form-label">Remover quantidade</label>
                                                <input type="number" name="quantidadeRemover" id="quantidadeRemover" class="form-control" required></input>
                                                <input type="hidden" name="id_produtos" value="<?=$row['id'] ?>">
                                                <input type="hidden" name="id_movimentacao" value="<?= $_GET['id_movimentacoes'] ?>">
                                                <button type="submit" class="btn btn-primary btn-sm mt-2">Remover</button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if (!isset($_GET["acao"])) : ?>
                                            <a href="formProdutos.php?acao=update&id=<?=$row['id'] ?>" class="btn btn-outline-primary btn-sm" title="Alteração">Alterar</a>&nbsp;
                                            <a href="formProdutos.php?acao=delete&id=<?=$row['id'] ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                            <a href="formProdutos.php?acao=view&id=<?=$row['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                                        <?php endif; ?>
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

        echo datatables('tbListaprodutos');

        require_once "comuns/rodape.php";

    ?>
    
    