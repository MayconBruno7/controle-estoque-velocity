<?php

    use App\Library\Formulario;
    use App\Library\Session;

    // Verificar se há uma sessão de movimentação
    if (!session()->get('movimentacao')) {
        session()->get('movimentacao');
    }

    // Verificar se há uma sessão de produtos
    if (!session()->get('produtos')) {
        session()->get('produtos');
    }

    if ($this->getAcao() == 'insert') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Decodifica os dados recebidos do JavaScript
            $movimentacao = json_decode(file_get_contents("php://input"), true);
    
            // Verificar se há produtos a serem adicionados
            if (session()->get('produtos') && count(session()->get('produtos')) > 0) {
                // Adicionar os produtos à sessão de movimentação
                $movimentacao['produtos'] = session()->get('produtos');
            }
    
            // Adiciona os dados à sessão
            if (isset($movimentacao)) {
                $_SESSION['movimentacao'][] = $movimentacao;
            }
    
            // Limpar a sessão de produtos
            session()->destroy('produtos');
        }
    }
    
    $dadosMovimentacao = isset($_SESSION['movimentacao'][0]) ? $_SESSION['movimentacao'][0] : [];
    $total = 0;

?>

<?= $this->extend('layouts/default') ?>
<?= $this->section('content') ?>

<main class="container mt-5">

    <div class="modal fade" id="modalAdicionarProduto" tabindex="-1" aria-labelledby="modalAdicionarProdutoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdicionarProdutoLabel">Adicionar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= ($acao == 'update') ? base_url('Movimentacao/update/updateProdutoMovimentacao/' . $this->getId()) : base_url('Movimentacao/insertProdutoMovimentacao/' . $acao) ?>" id="formAdicionarProduto" method="POST">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="id_produto" class="form-label">Produto</label>
                                <input type="text" class="form-control" id="search_produto" placeholder="Pesquisar produto">
                                <select class="form-control" name="id_produto" id="id_produto" required <?= $acao == 'view' || $acao == 'delete' ? 'disabled' : '' ?>>
                                    <option value="" selected disabled>Vazio</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor Unitário</label>
                            <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                        </div>

                        <input type="hidden" name="id_movimentacao" value="<?= $this->getId() ?>">
                        <input type="hidden" name="tipo" value="<?= setValor('tipo') ?>">
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 200px;">
        <?= exibeTitulo('Movimentacao'); ?>
    </div>

    <div class="row justify-content-center">
       <!-- Mensagens de erro ou sucesso -->
    </div>

    <form class="g-3" action="<?= base_url('Movimentacao/' . $acao) ?>" method="POST" id="form">
        <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

        <?php if ($acao == 'insert') : ?>
            <div class="row justify-content-center">
                <div class="col-6 mt-3">
                    <label for="fornecedor_id" class="form-label">Fornecedor</label>
                    <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= $acao != 'insert' && $acao != 'update' ? 'disabled' : '' ?>>
                        <option value="">...</option>
                        <?php foreach ($dados['aFornecedorMovimentacao'] as $fornecedor) : ?>
                            <option value="<?= $fornecedor['id'] ?>" <?= isset($dadosMovimentacao['fornecedor_id']) && $dadosMovimentacao['fornecedor_id'] == $fornecedor['id'] ? 'selected' : '' ?>>
                                <?= $fornecedor['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label for="tipo" class="form-label">Tipo de Movimentação</label>
                    <select name="tipo" id="tipo" class="form-control" required <?= $acao != 'insert' && $acao != 'update' ? 'disabled' : '' ?>>
                        <option value="">...</option>
                        <option value="1" <?= isset($dadosMovimentacao['tipo_movimentacao']) && $dadosMovimentacao['tipo_movimentacao'] == 1 ? 'selected' : '' ?>>Entrada</option>
                        <option value="2" <?= isset($dadosMovimentacao['tipo_movimentacao']) && $dadosMovimentacao['tipo_movimentacao'] == 2 ? 'selected' : '' ?>>Saída</option>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label for="statusRegistro" class="form-label">Status da Movimentação</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $acao != 'insert' && $acao != 'update' ? 'disabled' : '' ?>>
                        <option value="">...</option>
                        <option value="1" <?= isset($dadosMovimentacao['statusRegistro']) && $dadosMovimentacao['statusRegistro'] == 1 ? 'selected' : '' ?>>Ativo</option>
                        <option value="2" <?= isset($dadosMovimentacao['statusRegistro']) && $dadosMovimentacao['statusRegistro'] == 2 ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-8 mt-3">
                    <label for="setor_id" class="form-label">Setor</label>
                    <select name="setor_id" id="setor_id" class="form-control" required <?= $acao != 'insert' && $acao != 'update' ? 'disabled' : '' ?>>
                        <option value="">...</option>
                        <?php foreach ($dados['aSetorMovimentacao'] as $setor) : ?>
                            <option value="<?= $setor['id'] ?>" <?= (isset($dadosMovimentacao['setor_id']) && $dadosMovimentacao['setor_id'] == $setor['id']) ? 'selected' : '' ?>>
                                <?= $setor['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-2 mt-3">
                    <label for="data_pedido" class="form-label">Data do Pedido</label>
                    <input type="date" class="form-control" name="data_pedido" id="data_pedido" placeholder="data_pedido do item" required autofocus value="<?= isset($dadosMovimentacao['data_pedido']) ? $dadosMovimentacao['data_pedido'] : "" ?>" max="<?= date('Y-m-d') ?>" <?= $acao != 'insert' && $acao != 'update' ? 'disabled' : '' ?>>
                </div>
            </div>
        <?php else : ?>
            <div class="row justify-content-center">
                <div class="col-12 mt-3">
                    <label class="form-label">Produto</label>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdicionarProduto">Adicionar</button>
                </div>
            </div>
        <?php endif; ?>
    </form>

    <div class="container mt-4">
        <h4 class="text-center">Produtos</h4>
        <div class="row justify-content-center">
            <div class="col-12">
                <table class="table table-bordered table-hover" id="tabelaProdutos">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Valor Unitário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($dadosMovimentacao['produtos'])) : ?>
                            <?php foreach ($dadosMovimentacao['produtos'] as $produto) : ?>
                                <tr>
                                    <td><?= $produto['nome'] ?></td>
                                    <td><?= $produto['quantidade'] ?></td>
                                    <td>R$ <?= number_format($produto['valor'], 2, ',', '.') ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" onclick="removerProduto('<?= $produto['id'] ?>')">Remover</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">Nenhum produto adicionado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script>

    $(function() {
        $('#search_produto').keyup(function() {
            var termo = $(this).val().trim();

            if (termo.length > 0) {
                $('#id_produto').hide();
                $('.carregando').show();

                $.getJSON('/Movimentacao/getProdutoComboBox/' + termo, 
                function(data) {
                    console.log(data);
                    var options = '<option value="" selected disabled>Escolha o produto</option>';
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            options += '<option value="' + data[i].id + '">' + data[i].id + ' - ' + data[i].nome + '</option>';
                        }
                    } else {
                        options = '<option value="" selected disabled>Nenhum produto encontrado</option>';
                    }
                    $('#id_produto').html(options).show();
                })
                .fail(function() {
                    console.error("Erro ao carregar produtos.");
                    $('#id_produto').html('<option value="" selected disabled>Erro ao carregar produtos</option>').show();
                })
                .always(function() {
                    $('.carregando').hide();
                });
            } else {
                $('#id_produto').html('<option value="" selected disabled>Escolha um produto</option>').show();
            }
        });
    });

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

            // Função para abrir o modal
            function abrirModal() {
                var modal = new bootstrap.Modal(document.getElementById('modalAdicionarProduto'));
                modal.show();
            }

            // Envia os dados para o PHP usando AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'formMovimentacoes.php?acao=insert', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var idMovimentacoes = ''; // Defina o valor corretamente
                    var tipo = tipo_movimentacao;
                    abrirModal();
                } else {
                    console.log('Erro ao salvar informações');
                }
            };
            xhr.send(JSON.stringify(movimentacao));
        }
    });

</script>
