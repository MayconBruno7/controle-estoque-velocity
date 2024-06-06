<?php

    // $dados = [];

    // $fornecedor_id = "";
    // $fornecedor_nome = "";
    // $setor_item_id = "";
    // $total = 0;

    // require_once "library/Database.php";

    // $db = new Database();

    // $dados = $db->dbSelect(
    //     "SELECT * FROM
    //         movimentacoes WHERE id = ?",
    //         'first',
    //     [isset($_GET['id_movimentacoes']) ? $_GET['id_movimentacoes'] :""]
    // );

    // $produtos = $db->dbSelect(
    //     "SELECT * FROM
    //         produtos ORDER BY nome"
    // );

    // $produtosMov = $db->dbSelect(
    //     "SELECT mi.id_movimentacoes,
    //         mi.id_produtos AS id_prod_mov_itens,
    //         mi.quantidade AS mov_itens_quantidade,
    //         mi.valor,
    //         p.*
    //     FROM movimentacoes_itens mi
    //     INNER JOIN produtos p ON p.id = mi.id_produtos
    //     WHERE mi.id_movimentacoes = ?
    //         OR mi.id_movimentacoes IS NULL
    //     ORDER BY p.descricao;
    //     ",
    //     'all',
    //     [isset($_GET['id_movimentacoes']) ? $_GET['id_movimentacoes'] :""]
    // );

    // try {
    //     if ($dados) {
    //         $fornecedor_id = $dados->id_fornecedor;
    //     }

    // } catch (Exception $ex) {
    //     echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    // }

    // $dadosProdutos = $db->dbSelect("SELECT * FROM produtos ORDER BY id");

    // // <-------------- Informações do Fornecedor ----------------->
    // $dadosFornecedor = $db->dbSelect("SELECT * FROM fornecedor");

    // function obterNomeFornecedor($fornecedor_id, $db) {
    //     $query = "SELECT nome FROM fornecedor WHERE id = ?";
    //     $result = $db->dbSelect($query, 'first', [$fornecedor_id]);
    //     return $result ? $result->nome : '';
    // }

    // $nome_fornecedor = isset($fornecedor_id) ? obterNomeFornecedor($fornecedor_id, $db) : '';

    // // <-------------- Informações do Setor ----------------->
    // $dadosSetor = $db->dbSelect("SELECT * FROM setor ORDER BY id");

    // if ($dados) {
    //     $setor_item_id = $dados->id_setor;
    // }

    // function obterNomeSetor($setor_id, $db) {
    //     $query = "SELECT nome FROM setor WHERE id = ?";
    //     $result = $db->dbSelect($query, 'first', [$setor_id]);
    //     return $result ? $result->nome : '';
    // }

    // $nome_setor = isset($setor_item_id) ? obterNomeSetor($setor_item_id, $db) : '';
    // // <---------------------------------------------------------->

   

    // // Verificar se há uma sessão de movimentação
    // if (!isset($_SESSION['movimentacao'])) {
    //     $_SESSION['movimentacao'] = array();
    // }

    // // Verificar se há uma sessão de produtos
    // if (!isset($_SESSION['produtos'])) {
    //     $_SESSION['produtos'] = array();
    // }

    // if ($this->getAcao() == 'insert') {
    //     if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //         // Decodifica os dados recebidos do JavaScript
    //         $movimentacao = json_decode(file_get_contents("php://input"), true);
    
    //         // Verificar se há produtos a serem adicionados
    //         if (isset($_SESSION['produtos']) && count($_SESSION['produtos']) > 0) {
    //             // Adicionar os produtos à sessão de movimentação
    //             $movimentacao['produtos'] = $_SESSION['produtos'];
    //         }
    
    //         // Adiciona os dados à sessão
    //         if (isset($movimentacao)) {
    //             $_SESSION['movimentacao'][] = $movimentacao;
    //         }
    
    //         // Limpar a sessão de produtos
    //         unset($_SESSION['produtos']);
    //     }
    // }
    
    // $dadosMovimentacao = isset($_SESSION['movimentacao'][0]) ? $_SESSION['movimentacao'][0] : [];
    // $total = 0;

    // // Funções dos formulários
    // require_once "helpers/Formulario.php";
    // // Recupera o cabeçalho para a página
    // require_once "comuns/cabecalho.php";
    // // Não permite que um usuário não logado acesse a página
    // require_once "library/protectUser.php";

    // $idUltimaMovimentacao = $db->dbSelect("SELECT MAX(id) AS ultimo_id FROM movimentacoes");
    // $idMovimentacaoAtual = $idUltimaMovimentacao[0]['ultimo_id'];

    
    // unset($_SESSION['movimentacao']);
    // // limpa a sessão de produtos
    // unset($_SESSION['produtos']);
    // exit;

    // // Verifica se a sessão de movimentação existe e se há produtos nela
    // if (isset($_SESSION['movimentacao']) && isset($_SESSION['movimentacao'][0]['produtos'])) {
    //     // Loop através de todas as movimentações
    //     foreach ($_SESSION['movimentacao'] as $movimentacao) {
    //         // Verifica se há produtos na movimentação
    //         if(isset($movimentacao['produtos'])) {
    //             // Loop através de todos os produtos na movimentação atual
    //             foreach ($movimentacao['produtos'] as $produto) {
    //                 // Acessa os dados do produto
    //                 echo "Nome do Produto: " . $produto['nome_produto'] . "<br>";
    //                 echo "ID do Produto: " . $produto['id_produto'] . "<br>";
    //                 echo "Quantidade: " . $produto['quantidade'] . "<br>";
    //                 echo "Valor: " . $produto['valor'] . "<br>";
    //                 echo "<br>";
    //             }
    //         }
    //     }
    // } 

    //  session_start();

    // var_dump($_SESSION['movimentacao']);
    // var_dump( $_SESSION['movimentacao'][0]['produtos']);
    // exit;

    use App\Library\Formulario;

    $total = 0;


?>

<main class="container mt-5">
    <div class="row">
        <div class="col-10">
            <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
            <?= Formulario::titulo('Movimentação', true, false); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
                <?= Formulario::exibeMsgError() ?>
            </div>

            <div class="col-12 mt-3">
                <?= Formulario::exibeMsgSucesso() ?>
            </div>
        </div>
    </div>

    <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
    <form class="g-3" action="<?= $this->getAcao() ?>Movimentacoes.php?acao=<?= $this->getAcao() ?>" method="POST" id="form">

        <!--  verifica se o id está no banco de dados e retorna esse id -->
        <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

        <?php if ($this->getAcao()) : ?>
        <div class="row">
            <div class="col-6 mt-3">
                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <?php foreach($dados['aFornecedorMovimentacao'] as $fornecedor) : ?>
                        <option value="<?= $fornecedor['id'] ?>" <?= setValor('id_fornecedor') == $fornecedor['id'] ? 'selected' : '' ?>>
                            <?= $fornecedor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="tipo" class="form-label">Tipo de Movimentação</label>
                <select name="tipo" id="tipo" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <option value="1" <?= setValor('tipo') == 1 ? 'selected' : '' ?>>Entrada</option>
                    <option value="2" <?= setValor('tipo') == 2 ? 'selected' : '' ?>>Saída</option>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="statusRegistro" class="form-label">Status da Movimentação</label>
                <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <option value="1" <?= setValor('statusRegistro') == 1 ? 'selected' : '' ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == 2 ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="col-8 mt-3">
                <label for="setor_id" class="form-label">Setor</label>
                <select name="setor_id" id="setor_id" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <?php foreach ($dados['aSetorMovimentacao'] as $setor): ?>
                        <option value="<?= $setor['id'] ?>" <?= setValor('id_setor') ? 'selected' : '' ?>>
                            <?= $setor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-2 mt-3">
                <label for="data_pedido" class="form-label">Data do Pedido</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="date" class="form-control" name="data_pedido" id="data_pedido" placeholder="data_pedido do item" required autofocus value="<?= setValor('data_pedido') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="col-2 mt-3">
                <label for="data_chegada" class="form-label">Data de Chegada</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="date" class="form-control" name="data_chegada" id="data_chegada" placeholder="data_chegada do item" value="<?= setValor('data_chegada') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>


            <div class="col-12 mt-3">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea class="form-control" name="motivo" id="motivo" placeholder="Detalhe o motivo" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>><?= setValor('motivo') ?></textarea>
            </div>
            <?php endif; ?>

            <div class="col mt-4">
                <div class="col-md-8">
                    <h3 class="d-inline">Produtos do pedido</h3>
                </div>
            </div>

            <?php if($this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
                <div class="col mt-4">
                    <div class="col-auto text-end ml-2">
                    <a href="<?= ($this->getAcao() == 'update') ? 
                                    ('viewEstoque.php?acao=update&id_movimentacoes=' . (isset($dados->id) ? $dados->id : '') . 
                                    '&tipo=' . (isset($dados->tipo) ? $dados->tipo : '')) : '' ?>" 
                        class="btn btn-outline-primary btn-sm styleButton" 
                        id="<?= ($this->getAcao() == 'insert') ? 'btnSalvar' : '' ?>" 
                        title="Adicionar">
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
                <?php if(isset($_SESSION['movimentacao']) && isset($_SESSION['movimentacao'][0]['produtos']) && $this->getAcao() == 'insert' ? $this->getAcao() : "") : "" ?>
                    <?php foreach ($_SESSION['movimentacao'][0]['produtos'] as $produto) : ?>
                        <tr>
                            <td><?= $produto['id_produto'] ?></td>
                            <td><?= $produto['nome_produto'] ?></td>
                            <td><?= number_format($produto['valor'], 2, ",", ".") ?></td>
                            <td><?= $produto['quantidade'] ?></td>
                            <td><?= number_format(($produto['quantidade'] * $produto['valor']), 2, ",", ".") ?></td>
                            <td>
                                <?php if($this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
                                    <a href="viewEstoque.php?acao=delete&id=<?= $produto['id_produto'] ?>&id_movimentacoes=<?= isset($idMovimentacaoAtual) ? $idMovimentacaoAtual : "" ?>&qtd_produto=<?=  isset($produto['quantidade']) ? $produto['quantidade'] : '' ?>&tipo=<?= isset($dadosMovimentacao['tipo_movimentacao']) ? $dadosMovimentacao['tipo_movimentacao'] : '' ?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                                <?php endif; ?>
                                    <a href="formProdutos.php?acao=view&id=<?= $produto['id_produto'] ?>&id_movimentacoes=<?= isset($idMovimentacaoAtual) ? $idMovimentacaoAtual : "" ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
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
            
                <?php if($this->getAcao() != 'insert') : ?>
                    <?php

                            foreach ($dados['aItemMovimentacao'] as $row) {
                        ?>
                        <tr>
                            <td><?= $row['id_prod_mov_itens'] ?></td>
                            <td><?= $row['nome'] ?></td>
                            <td><?= number_format($row['valor'], 2, ",", ".")  ?> </td>
                            <td><?= $row['mov_itens_quantidade'] ?></td>
                            <td><?= number_format(($row["mov_itens_quantidade"] * $row["valor"]), 2, ",", ".") ?></td>
                            <td>
                            <?php if($this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
                                <a href="viewEstoque.php?acao=delete&id=<?= $row['id'] ?>&id_movimentacoes=<?= $row['id_movimentacoes'] ?>&qtd_produto=<?= $row['mov_itens_quantidade'] ?>&tipo=<?= isset($dados->tipo) ? $dados->tipo : ""?>" class="btn btn-outline-danger btn-sm" title="Exclusão">Excluir</a>&nbsp;
                            <?php endif; ?>
                                <a href="formProdutos.php?acao=view&id=<?= $row['id'] ?>&id_movimentacoes=<?= $row['id_movimentacoes'] ?>" class="btn btn-outline-secondary btn-sm" title="Visualização">Visualizar</a>
                            </td>
                        </tr>

                        <input type="hidden" name="quantidade" id="quantidade" value="<?= $row['quantidade'] ?>">
                        <input type="hidden" name="id_produto" id="id_produto" value="<?= $row['id'] ?>">
                        <input type="hidden" name="valor" id="valor" value="<?= $row['valor'] ?>">
                        <input type="hidden" name="tipo_movimentacoes" id="tipo_movimentacoes" value="<?= isset($dadosMovimentacao['tipo_movimentacao']) ? $dadosMovimentacao['tipo_movimentacao'] : '' ?>">

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

        <div class="form-group col-12 mt-5">
            <?= Formulario::botao('voltar') ?>
            <?php if ($this->getAcao() != "view"): ?>
                <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
            <?php endif; ?>
        </div>
    </form>

    <!-- <button onclick="capturarValores()">Salvar na Sessão</button> -->
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script>

    document.addEventListener("DOMContentLoaded", function() {
 
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
            var motivo = document.getElementById('motivo').value;

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
                'motivo': motivo,
                'produtos': produtos 
            };

            // Envia os dados para o PHP usando AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'formMovimentacoes.php?acao=insert', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var idMovimentacoes = ''; // Defina o valor corretamente
                    var tipo = tipo_movimentacao;
                    window.location.href = "<?= baseUrl() ?>Produto/index/insert/movimentacao";
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
