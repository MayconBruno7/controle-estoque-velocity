<?php 

    $dados = [];

    $fornecedor_id = "";
    $fornecedor_nome = "";
    $setor_item_id = "";
    $total = 0;

    require_once "library/Database.php";

    $db = new Database();

    if (isset($_GET['acao']) && $_GET['acao'] == "insert" || $_GET['acao'] == "update") {

        $dados = $db->dbSelect("SELECT * FROM movimentacoes WHERE id = ?", 'first', [isset($_GET['id_movimentacoes']) ? $_GET['id_movimentacoes'] :""]);

        $produtos = $db->dbSelect("SELECT * FROM produtos ORDER BY nome");

        $produtosMov = $db->dbSelect("SELECT mi.id_movimentacoes, mi.id_produtos as id_prod_mov_itens, mi.quantidade as mov_itens_quantidade, mi.valor, p.*
        FROM movimentacoes_itens mi
        INNER JOIN produtos p ON p.id = mi.id_produtos
        WHERE mi.id_movimentacoes = ? 
        ORDER BY p.descricao", 'all', [isset($_GET['id_movimentacoes']) ? $_GET['id_movimentacoes'] :""]);
    
        try {

            if ($dados) {
                $fornecedor_id = $dados->id_fornecedor;
            } 

            // if ($dados) {
            //     $Produtos_id = $dados->Produto_Pedido;
            // } 

        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }

    
    $dadosProdutos = $db->dbSelect("SELECT * FROM produtos ORDER BY id");

    // if (!isset($Produtos_id)) {
    //     $Produtos_id = "";
    // }

    // <-------------- Informações do Fornecedor -----------------> 
    $dadosFornecedor = $db->dbSelect("SELECT * FROM fornecedor");


    function obterNomeFornecedor($fornecedor_id, $db) {
        $query = "SELECT nome FROM fornecedor WHERE id = ?";
        $result = $db->dbSelect($query, 'first', [$fornecedor_id]);
        return $result ? $result->nome : '';
    }

    $nome_fornecedor = isset($fornecedor_id) ? obterNomeFornecedor($fornecedor_id, $db) : '';
    // <----------------------------------------------------------> 

    // <-------------- Informações do Setor -----------------> 
    $dadosSetor = $db->dbSelect("SELECT * FROM setor ORDER BY id");

    if ($dados) {
        $setor_item_id = $dados->id_setor;
    }

    function obterNomeSetor($setor_id, $db) {
        $query = "SELECT nome FROM setor WHERE id = ?";
        $result = $db->dbSelect($query, 'first', [$setor_id]);
        return $result ? $result->nome : '';
    }

    $nome_setor = isset($setor_item_id) ? obterNomeSetor($setor_item_id, $db) : '';
    // <----------------------------------------------------------> 

    // muda as ações para os nomes das página e muda o estado do item colocando 1 para novo e 2 para usado
    require_once "helpers/Formulario.php";

    // recupera o cabeçalho para a página
    require_once "comuns/cabecalho.php";
    require_once "library/protectUser.php";

?>
    <main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
                <h2>Movimentação<?= subTitulo($_GET['acao']) ?></h2>
            </div>
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

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form class="g-3" action="<?= $_GET['acao'] ?>Movimentacoes.php" method="POST" id="form">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

            <div class="row">

            <div class="col-8 mt-3">
                    <label for="fornecedor_id" class="form-label">Fornecedor</label>
                    <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <option value="">...</option>
                        <?php foreach($dadosFornecedor as $fornecedor) : ?>
                            <option value="<?= $fornecedor['id'] ?>" <?= $fornecedor['id'] == $fornecedor_id ? 'selected' : '' ?>>
                                <?= $fornecedor['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-2 mt-3">
                    <label for="tipo" class="form-label">Tipo de Movimentação</label>
                    <select name="tipo" id="tipo" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <!--  verifica se o statusItem está no banco de dados e retorna esse status -->
                        <option value=""  <?= isset($dados->tipo) ? $dados->tipo == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->tipo) ? $dados->tipo == 1  ? "selected" : "" : "" ?>>Entrada</option>
                        <option value="2" <?= isset($dados->tipo) ? $dados->tipo == 2  ? "selected" : "" : "" ?>>Saída</option>
                    </select>
                        </div>

                <div class="col-2 mt-3">
                    <label for="statusRegistro" class="form-label">Status da Movimentação</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <!--  verifica se o status está no banco de dados e retorna esse status -->
                        <option value=""  <?= isset($dados->statusRegistro) ? $dados->statusRegistro == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>


                <div class="col-8 mt-3">
                    <label for="setor_id" class="form-label">Setor</label>
                    <select name="setor_id" id="setor_id" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <option value="">...</option> 
                        <?php foreach ($dadosSetor as $setor): ?>
                            <option value="<?= $setor['id'] ?>" <?= $setor['id'] == $setor_item_id ? 'selected' : '' ?>>
                                <?= $setor['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="col-2 mt-3">
                    <label for="data_pedido" class="form-label">Data do Pedido</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="date" class="form-control" name="data_pedido" id="data_pedido" placeholder="data_pedido do item" required autofocus value="<?= isset($dados->data_pedido) ? $dados->data_pedido : "" ?>">
                </div>


                <div class="col-2 mt-3">
                    <label for="data_chegada" class="form-label">Data de Chegada</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="date" class="form-control" name="data_chegada" id="data_chegada" placeholder="data_chegada do item"  value="<?= isset($dados->data_chegada) ? $dados->data_chegada : "" ?>">
                </div>



                <div class="col-12 mt-3">
                    <label for="motivo" class="form-label">Motivo</label>
                    <textarea class="form-control" name="motivo" id="motivo" placeholder="Detalhe o motivo" <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dados->motivo) ? $dados->motivo : "" ?></textarea>
                </div>


                <div class="row mt-4">
                    <div class="col-md-6">
                        <h3 class="d-inline">Produtos do pedido</h3>
                    </div>
                    <div class="col-md-6">
                        <a href="listaProdutos.php?acao=insert&id_movimentacoes=<?= isset($dados->id) ? $dados->id : "" ?>&tipo=<?= isset($dados->tipo) ? $dados->tipo : ""  ?>" 
                        class="btn btn-outline-primary btn-sm styleButton" title="Inserir">Adicionar Produtos</a>
                    </div>
                </div>

                <table id="tbListaProduto" class="table table-striped table-hover table-bordered table-responsive-sm mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Produto</th>
                            <th>Produto</th>
                            <th>Valor Unitário</th>
                            <th>Quantidade</th>           
                            <th>Valor Total</th>
                            <th>Opções</th>
                        </tr>
                    <thead>
                    <tbody>

                        <?php
                            if (isset($_GET['acao']) && $_GET['acao'] != "insert") {

                            foreach ($produtosMov as $row) {
                        ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['nome'] ?></td>
                                    <td><?= number_format($row['valor'], 2, ",", ".")  ?> </td>
                                    <td><?= $row['mov_itens_quantidade'] ?></td>
                                    <td><?= ($row["mov_itens_quantidade"] * $row["valor"]) ?></td>

                                    
                                    <td>
                                        <a href="listaProduto.php?acao=delete&id_produtos=<?= $row['id_prod_mov_itens'] ?>&id_movimentacoes=<?= $row['id_movimentacoes'] ?>&qtd_produto=<?= $row['mov_itens_quantidade'] ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                        <a href="produtos.php?acao=view&id_produtos=<?= $row['id_prod_mov_itens'] ?>&id_movimentacoes=<?= $row['id_movimentacoes'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                                    </td>
                                    
                                </tr>
                            
                        <?php
                                $total = $total + ($row["mov_itens_quantidade"] * $row["valor"]);
                                
                            }
                            }
                        ?>
                    </tbody>
                </table>

                <p><h2 align='center'>Valor Total: R$ <?= number_format($total, 2, ',', '.')?></h2></p>   

                <!-- se a ação for view não aparece a hora formatada  -->
                <?php  if ($_GET['acao'] == 'view' || $_GET['acao'] == 'delete') { ?>
        
            <?php 
            } 
            ?>

            <div class="col-6 mt-4 mb-4">
                <a href="listaMovimentacoes.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                
                <!-- define o texto de cada botão de acordo com a sua ação -->
                <?php if ($_GET['acao'] == "delete") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Excluir</button>
                <?php endif; ?>

                <?php if ($_GET['acao'] == "update") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Alterar</button>
                <?php endif; ?>

                <?php if ($_GET['acao'] == "insert") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                <?php endif; ?>
            </div>
        </form>
    </main>

    <script src="assets/ckeditor5-build-classic/ckeditor.js"></script>
    <script>
       document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#motivo'), {})
                .then(editor => { // Definindo o editor CKEditor aqui
                    document.getElementById('historico').addEventListener('change', function() {
                        var option = this.options[this.selectedIndex];
                        
                        // Definindo o texto do setor e fornecedor nos elementos
                        document.getElementById('setor_id').value = option.getAttribute('data-setor'); 
                        document.getElementById('fornecedor_id').value = option.getAttribute('data-fornecedor');
                        
                        // Definindo os outros valores conforme necessário
                        document.getElementById('nome').value = option.getAttribute('data-nome');
                        editor.setData(option.getAttribute('data-motivo')); 
                        document.getElementById('quantidade').value = option.getAttribute('data-quantidade');
                        document.getElementById('status').value = option.getAttribute('data-status');
                        document.getElementById('statusItem').value = option.getAttribute('data-statusitem');
                        console.log(option);
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        });

    </script>
    <?php

    require_once "comuns/rodape.php";

    ?>