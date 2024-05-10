<?php

    $dados = [];

    $fornecedor_id = "";
    $fornecedor_nome = "";
    $setor_item_id = "";
    $total = 0;

    require_once "library/Database.php";

    $db = new Database();

    $dados = $db->dbSelect(
        "SELECT * FROM
            movimentacoes WHERE id = ?",
            'first',
        [isset($_GET['id_movimentacoes']) ? $_GET['id_movimentacoes'] :""]
    );

    $produtos = $db->dbSelect(
        "SELECT * FROM
            produtos ORDER BY nome"
    );

    $produtosMov = $db->dbSelect(
        "SELECT mi.id_movimentacoes,
            mi.id_produtos AS id_prod_mov_itens,
            mi.quantidade AS mov_itens_quantidade,
            mi.valor,
            p.*
        FROM movimentacoes_itens mi
        INNER JOIN produtos p ON p.id = mi.id_produtos
        WHERE mi.id_movimentacoes = ?
            OR mi.id_movimentacoes IS NULL
        ORDER BY p.descricao;
        ",
        'all',
        [isset($_GET['id_movimentacoes']) ? $_GET['id_movimentacoes'] :""]
    );

    try {
        if ($dados) {
            $fornecedor_id = $dados->id_fornecedor;
        }

    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }

    $dadosProdutos = $db->dbSelect("SELECT * FROM produtos ORDER BY id");

    // <-------------- Informações do Fornecedor ----------------->
    $dadosFornecedor = $db->dbSelect("SELECT * FROM fornecedor");

    function obterNomeFornecedor($fornecedor_id, $db) {
        $query = "SELECT nome FROM fornecedor WHERE id = ?";
        $result = $db->dbSelect($query, 'first', [$fornecedor_id]);
        return $result ? $result->nome : '';
    }

    $nome_fornecedor = isset($fornecedor_id) ? obterNomeFornecedor($fornecedor_id, $db) : '';

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

    session_start();

    // Verificar se há uma sessão de movimentação
    if (!isset($_SESSION['movimentacao'])) {
        $_SESSION['movimentacao'] = array();
    }

    // Verificar se há uma sessão de produtos
    if (!isset($_SESSION['produtos'])) {
        $_SESSION['produtos'] = array();
    }

    if (isset($_GET['acao']) && $_GET['acao'] == 'insert') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Decodifica os dados recebidos do JavaScript
            $movimentacao = json_decode(file_get_contents("php://input"), true);

            // Adiciona os dados à sessão
            if (isset($movimentacao)) {
                $_SESSION['movimentacao'][] = $movimentacao;
            }
        }

        // Verificar se há produtos a serem adicionados
        if (isset($_SESSION['produtos']) && count($_SESSION['produtos']) > 0) {
            // Adicionar os produtos à sessão de movimentação
            $_SESSION['movimentacao'][0]['produtos'] = $_SESSION['produtos'];
        }
    }

    $dadosMovimentacao = isset($_SESSION['movimentacao'][0]) ? $_SESSION['movimentacao'][0] : [];
    $total = 0;

    // Funções dos formulários
    require_once "helpers/Formulario.php";
    // Recupera o cabeçalho para a página
    require_once "comuns/cabecalho.php";
    // Não permite que um usuário não logado acesse a página
    require_once "library/protectUser.php";

    $idUltimaMovimentacao = $db->dbSelect("SELECT MAX(id) AS ultimo_id FROM movimentacoes");
    $idMovimentacaoAtual = $idUltimaMovimentacao[0]['ultimo_id'];

    
    // // var_dump($motivo);
    // var_dump( $_SESSION['movimentacao']);

    // var_dump( $_SESSION['produtos']);

    //     // // // Limpa a sessão movimentacao
    //     // unset($_SESSION['movimentacao']);
    //     // // // limpa a sessão de produtos
    //     // unset($_SESSION['produtos']);
    // exit;

?>

<main class="container mt-5">
    <div class="row">
        <div class="col-10">
            <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
            <h2>Movimentação<?= subTitulo(isset($_GET['acao']) ? $_GET['acao'] : "") ?></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?= getMensagem() ?>
        </div>
    </div>

    <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
    <form class="g-3" action="<?= isset($_GET['acao']) ? $_GET['acao'] : ""  ?>Movimentacoes.php" method="POST" id="form">

        <!--  verifica se o id está no banco de dados e retorna esse id -->
        <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

        <?php if (isset($_GET['acao']) && $_GET['acao'] == 'insert') : ?>
        <div class="row">
            <div class="col-6 mt-3">
                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                    <option value="">...</option>
                    <?php foreach($dadosFornecedor as $fornecedor) : ?>
                        <option value="<?= $fornecedor['id'] ?>" <?= isset($dadosMovimentacao['fornecedor_id']) && $dadosMovimentacao['fornecedor_id'] == $fornecedor['id'] ? 'selected' : '' ?>>
                            <?= $fornecedor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="tipo" class="form-label">Tipo de Movimentação</label>
                <select name="tipo" id="tipo" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                    <option value="">...</option>
                    <option value="1" <?= isset($dadosMovimentacao['tipo_movimentacao']) && $dadosMovimentacao['tipo_movimentacao'] == 1 ? 'selected' : '' ?>>Entrada</option>
                    <option value="2" <?= isset($dadosMovimentacao['tipo_movimentacao']) && $dadosMovimentacao['tipo_movimentacao'] == 2 ? 'selected' : '' ?>>Saída</option>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="statusRegistro" class="form-label">Status da Movimentação</label>
                <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                    <option value="">...</option>
                    <option value="1" <?= isset($dadosMovimentacao['statusRegistro']) && $dadosMovimentacao['statusRegistro'] == 1 ? 'selected' : '' ?>>Ativo</option>
                    <option value="2" <?= isset($dadosMovimentacao['statusRegistro']) && $dadosMovimentacao['statusRegistro'] == 2 ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="col-8 mt-3">
                <label for="setor_id" class="form-label">Setor</label>
                <select name="setor_id" id="setor_id" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <?php foreach ($dadosSetor as $setor): ?>
                        <option value="<?= $setor['id'] ?>" <?= (isset($dadosMovimentacao['setor_id']) && $dadosMovimentacao['setor_id'] == $setor['id']) ? 'selected' : '' ?>>
                            <?= $setor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-2 mt-3">
                <label for="data_pedido" class="form-label">Data do Pedido</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="date" class="form-control" name="data_pedido" id="data_pedido" placeholder="data_pedido do item" required autofocus value="<?= isset($dadosMovimentacao['data_pedido']) ? $dadosMovimentacao['data_pedido'] : "" ?>" <?= isset($_GET['acao']) && ($_GET['acao'] == 'delete' || $_GET['acao'] == 'view') ? 'disabled' : '' ?>>
            </div>

            <div class="col-2 mt-3">
                <label for="data_chegada" class="form-label">Data de Chegada</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="date" class="form-control" name="data_chegada" id="data_chegada" placeholder="data_chegada do item" value="<?= isset($dadosMovimentacao['data_chegada']) ? $dadosMovimentacao['data_chegada'] : "" ?>" <?= isset($_GET['acao']) && ($_GET['acao'] == 'delete' || $_GET['acao'] == 'view') ? 'disabled' : '' ?>>
            </div>


            <div class="col-12 mt-3">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea class="form-control" name="motivo" id="motivo" placeholder="Detalhe o motivo" <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dadosMovimentacao['motivo']) ? is_array($dadosMovimentacao['motivo']) : "" ?></textarea>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['acao']) && $_GET['acao'] != 'insert') : ?>
            <div class="row">
                <div class="col-6 mt-3">
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

                <div class="col-3 mt-3">
                    <label for="tipo" class="form-label">Tipo de Movimentação</label>
                    <select name="tipo" id="tipo" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <!--  verifica se o statusItem está no banco de dados e retorna esse status -->
                        <option value=""  <?= isset($dados->tipo) ? $dados->tipo == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->tipo) ? $dados->tipo == 1  ? "selected" : "" : "" ?>>Entrada</option>
                        <option value="2" <?= isset($dados->tipo) ? $dados->tipo == 2  ? "selected" : "" : "" ?>>Saída</option>
                    </select>
                </div>

                <div class="col-3 mt-3">
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
                    <input type="date" class="form-control" name="data_pedido" id="data_pedido" placeholder="data_pedido do item" required autofocus value="<?= isset($dados->data_pedido) ? $dados->data_pedido : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-2 mt-3">
                    <label for="data_chegada" class="form-label">Data de Chegada</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="date" class="form-control" name="data_chegada" id="data_chegada" placeholder="data_chegada do item"  value="<?= isset($dados->data_chegada) ? $dados->data_chegada : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-12 mt-3">
                    <label for="motivo" class="form-label">Motivo</label>
                    <textarea class="form-control" name="motivo" id="motivo" placeholder="Detalhe o motivo" <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dados->motivo) ? $dados->motivo : "" ?></textarea>
                </div>
            <?php endif; ?>

            <div class="col mt-4">
                <div class="col-md-8">
                    <h3 class="d-inline">Produtos do pedido</h3>
                </div>
            </div>

            <?php if(isset($_GET['acao']) && $_GET['acao'] != 'delete' && $_GET['acao'] != 'view') : ?>
                <div class="col mt-4">
                    <div class="col-auto text-end ml-2">
                        <a
                            class="btn btn-outline-primary btn-sm styleButton" id="btnSalvar" title="Inserir">
                            Adicionar Produtos
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <table id="tbListaProduto" class="table table-striped table-hover table-bordered table-responsive-sm mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Produto</th>
                    <th>Valor Unitário</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($_GET['acao']) && $_GET['acao'] == 'insert') : ?>
                    <?php foreach ($_SESSION['produtos'] as $produto) : ?>
                        <tr>
                            <td><?= $produto['id_produto'] ?></td>
                            <td><?= $produto['nome_produto'] ?></td>
                            <td><?= number_format($produto['valor'], 2, ",", ".") ?></td>
                            <td><?= $produto['quantidade'] ?></td>
                            <td><?= number_format(($produto['quantidade'] * $produto['valor']), 2, ",", ".") ?></td>
                            <td>
                                <?php if(isset($_GET['acao']) && $_GET['acao'] != 'delete' && $_GET['acao'] != 'view') : ?>
                                    <a href="viewEstoque.php?acao=delete&id=<?= $produto['id_produto'] ?>&id_movimentacoes=<?= $idMovimentacaoAtual ?>&qtd_produto=<?=  $produto['quantidade'] ?>&tipo=<?= $dadosMovimentacao['tipo_movimentacao'] ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                <?php endif; ?>
                                    <a href="formProdutos.php?acao=view&id=<?= $produto['id_produto'] ?>&id_movimentacoes=<?= $idMovimentacaoAtual ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                            </td>
                        </tr>

                        <input type="hidden" name="quantidade" id="quantidade" value="<?= $produto['quantidade'] ?>">
                        <input type="hidden" name="id_produto" id="id_produto" value="<?= $produto['id_produto'] ?>">
                        <input type="hidden" name="valor" id="valor" value="<?= $produto['valor'] ?>">
                        <input type="hidden" name="tipo_movimentacoes" id="tipo_movimentacoes" value="<?= isset($dadosMovimentacao['tipo_movimentacao']) ? $dadosMovimentacao['tipo_movimentacao'] : '' ?>">

                        <?php
                            $total += $produto['quantidade'] * $produto['valor'];
                        ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            
                <?php if(isset($_GET['acao']) && $_GET['acao'] != 'insert') : ?>
                    <?php

                            foreach ($produtosMov as $row) {
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['nome'] ?></td>
                            <td><?= number_format($row['valor'], 2, ",", ".")  ?> </td>
                            <td><?= $row['mov_itens_quantidade'] ?></td>
                            <td><?= number_format(($row["mov_itens_quantidade"] * $row["valor"]), 2, ",", ".") ?></td>
                            <td>
                            <?php if(isset($_GET['acao']) && $_GET['acao'] != 'delete' && $_GET['acao'] != 'view') : ?>
                                <a href="viewEstoque.php?acao=delete&id=<?= $row['id'] ?>&id_movimentacoes=<?= $row['id_movimentacoes'] ?>&qtd_produto=<?= $row['mov_itens_quantidade'] ?>&tipo=<?= isset($dados->tipo) ? $dados->tipo : ""?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                            <?php endif; ?>
                                <a href="formProdutos.php?acao=view&id=<?= $row['id'] ?>&id_movimentacoes=<?= $row['id_movimentacoes'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                            </td>
                        </tr>

                        <?php

                            $total = $total + ($row["mov_itens_quantidade"] * $row["valor"]);

                            }

                        ?>
                <?php endif; ?>
            </tbody>
        </table>

        <p>
            <h2 align="center">
                Valor Total: R$ <?= number_format($total, 2, ',', '.')?>
            </h2>
        </p>
        </div>

        <div class="col-6 mt-4 mb-4">
            <a href="listaMovimentacoes.php" class="btn btn-outline-secondary btn-sm">Voltar</a>

            <!-- define o texto de cada botão de acordo com a sua ação -->
            <?php if (isset($_GET['acao']) && $_GET['acao'] == "delete") : ?>
                <button type="submit" class="btn btn-primary btn-sm">Excluir</button>
            <?php endif; ?>

            <?php if (isset($_GET['acao']) && $_GET['acao'] == "update") : ?>
                <button type="submit" class="btn btn-primary btn-sm">Alterar</button>
            <?php endif; ?>

            <?php if (isset($_GET['acao']) && $_GET['acao'] == "insert") : ?>
                <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
            <?php endif; ?>
        </div>
    </form>

    <!-- <button onclick="capturarValores()">Salvar na Sessão</button> -->
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#motivo'), {})
            .then(editor => {
                document.getElementById('historico').addEventListener('change', function() {
                    var option = this.options[this.selectedIndex];
                    document.getElementById('setor_id').value = option.getAttribute('data-setor');
                    document.getElementById('fornecedor_id').value = option.getAttribute('data-fornecedor');
                    document.getElementById('nome').value = option.getAttribute('data-nome');
                    editor.setData(option.getAttribute('data-motivo'));
                    document.getElementById('quantidade').value = option.getAttribute('data-quantidade');
                    document.getElementById('status').value = option.getAttribute('data-status');
                    document.getElementById('statusItem').value = option.getAttribute('data-statusitem');
                });
            })
            .catch(error => {
                console.error(error);
            });

        // Chama a função capturarValores quando o link for clicado
        document.getElementById('btnSalvar').addEventListener('click', function(event) {
            event.preventDefault(); // Previne o comportamento padrão de redirecionamento do link
            capturarValores();
        });

        function capturarValores() {
            var fornecedor_id = document.getElementById('fornecedor_id').value;
            var tipo_movimentacao = document.getElementById('tipo').value;
            var statusRegistro = document.getElementById('statusRegistro').value;
            var setor_id = document.getElementById('setor_id').value;
            var data_pedido = document.getElementById('data_pedido').value;
            var data_chegada = document.getElementById('data_chegada').value;
            // var motivo = CKEDITOR.instances.motivo.getData(); // Obtém o conteúdo do CKEditor

            // Array para armazenar os produtos
            var produtos = [];

            // Iterar sobre os campos de produto e capturar seus valores
            var produtosCampos = document.querySelectorAll('.produto-campo');
            produtosCampos.forEach(function(campo) {
                var id_produto = campo.querySelector('.id_produto').value;
                var nome_produto = campo.querySelector('.nome_produto').value;
                var valor = campo.querySelector('.valor').value;
                var quantidade = campo.querySelector('.quantidade').value;

                produtos.push({
                    'id_produto': id_produto,
                    'nome_produto': nome_produto,
                    'valor': valor,
                    'quantidade': quantidade
                });
            });

            // Criação do objeto movimentacao
            var movimentacao = {
                'fornecedor_id': fornecedor_id,
                'tipo_movimentacao': tipo_movimentacao,
                'statusRegistro': statusRegistro,
                'setor_id': setor_id,
                'data_pedido': data_pedido,
                'data_chegada': data_chegada,
                // 'motivo': motivo,
                'produtos': produtos // Adiciona produtos ao objeto movimentacao
            };

            // Envia os dados para o PHP usando AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'formMovimentacoes.php?acao=insert', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var idMovimentacoes = ''; // Defina o valor corretamente
                    var tipo = tipo_movimentacao;
                    window.location.href = "viewEstoque.php?acao=insert&id_movimentacoes=" + idMovimentacoes + "&tipo=" + encodeURIComponent(tipo);
                } else {
                    console.log('Erro ao salvar informações');
                }
            };
            xhr.send(JSON.stringify(movimentacao));
        }

        // window.onbeforeunload = function(event) {
        //     var activeElement = document.activeElement;
        //     // Verifica se o evento foi acionado pelo botão "Salvar"
        //     var btnSalvar = document.getElementById('btnSalvar');
        //     // Verifica se o evento foi acionado pelo link "Voltar"
        //     var linkVoltar = document.querySelector('a[href="listaMovimentacoes.php"]');
        //     if ((activeElement !== btnSalvar && activeElement.tagName.toLowerCase() !== 'button') 
        //         && (activeElement !== linkVoltar && activeElement.tagName.toLowerCase() !== 'a')) {
        //         // Envia uma requisição AJAX para limpar a sessão
        //         var xhr = new XMLHttpRequest();
        //         xhr.open('GET', 'limparSessao.php', true);
        //         xhr.send();
        //     }
        // };

    });

</script>

<?php

    require_once "comuns/rodape.php";

?>