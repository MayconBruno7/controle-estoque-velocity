<?php

    // recupera o cabeçalho para a página
    require_once "comuns/cabecalho.php";

    require_once "library/Database.php";
    // Criando o objeto Db para classe de base de dados
    $db = new Database();

    $dados = [];

    $setor_item_id = "";
    $fornecedor_id = "";
    $fornecedor_nome = "";

    /*
    *   Se for alteração, exclusão ou visualização busca a UF pelo ID que foi recebido via método GET
    */
    if ($_GET['acao'] != "insert") {

        try {

            // Consulta SQL para buscar a data formatada
            $dataMod = $db->dbSelect("SELECT DATE_FORMAT(dataMod, '%d/%m/%Y ás %H:%i:%s') AS dataModFormatada FROM produtos WHERE id = ?", 'first', [$_GET['id']]);
        
            // Verifica se a dataModFormatada está definida e atribui à variável
            $dataModFormatada = isset($dataMod->dataModFormatada) ? $dataMod->dataModFormatada : '';
        
            // prepara comando SQL
            $dados = $db->dbSelect("SELECT * FROM produtos WHERE id = ? ", 'first',[$_GET['id']]);

        
        // se houver erro na conexão com o banco de dados o catch retorna
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }
    
    $dadosHistorico = $db->dbSelect("SELECT * FROM historico_produtos");

    $dadosFornecedor = $db->dbSelect("SELECT * FROM fornecedor");

    if ($dadosFornecedor) {
        // Verifica se existe um valor de fornecedor definido no item
        $fornecedor_id = isset($dados->fornecedor) ? $dados->fornecedor : "";
    }
    
    function obterNomeFornecedor($fornecedor_id, $db) {
        $query = "SELECT nome FROM fornecedor WHERE id = ?";
        $result = $db->dbSelect($query, 'first', [$fornecedor_id]);
        return $result ? $result->nome : '';
    }
    $nome_fornecedor = isset($fornecedor_id) ? obterNomeFornecedor($fornecedor_id, $db) : '';
 
    // muda as ações para os nomes das página e muda o estado do item colocando 1 para novo e 2 para usado
    require_once "helpers/Formulario.php";
    require_once "library/protectUser.php";

?>

    <main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
                <h2>Item<?= subTitulo($_GET['acao']) ?></h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-start">
                <!-- Passando o ID do produto corretamente na URL -->
                <a href="HistoricoMovimentacoes.php?id_produtos=<?= isset($_GET['id']) ? $_GET['id'] : '' ?>" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Visualizar">Visualizar Histórico de Movimentações</a>
            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form class="g-3" action="<?= $_GET['acao'] ?>produtos.php" method="POST" id="form">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

            <div class="row">

                <div class="col-8">
                    <label for="nome" class="form-label">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do item" required autofocus value="<?= isset($dados->nome) ? $dados->nome : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dados->motivo) ? $dados->motivo : "" ?>>
                </div>

                <div class="col-4">
                    <label for="quantidade" class="form-label">Quantidade</label>
                    <!--  verifica se a quantidade está no banco de dados e retorna essa quantidade -->
                    <input type="number" class="form-control" name="qtd_item" id="quantidade" min="1" max="100"value="<?= isset($dados->quantidade) ? $dados->quantidade : "" ?>" disabled >
                    <input type="hidden" name="quantidade" id="hidden" value="<?= isset($dados->quantidade) ? $dados->quantidade : "" ?>" >
                </div>

                <div class="col-6 mt-3">
                    <label for="fornecedor_id" class="form-label">Fornecedor</label>
                    <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?> <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dados->motivo) ? $dados->motivo : "" ?>> 
                        <option value="">...</option>
                        <?php foreach($dadosFornecedor as $fornecedor) : ?>
                            <option value="<?= $fornecedor['id'] ?>" <?= $fornecedor['id'] == $fornecedor_id ? 'selected' : '' ?>>
                                <?= $fornecedor['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label for="statusRegistro" class="form-label">Estado de registro</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?> <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dados->motivo) ? $dados->motivo : "" ?>>
                        <!--  verifica se o status está no banco de dados e retorna esse status -->
                        <option value=""  <?= isset($dados->statusRegistro) ? $dados->statusRegistro == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label for="condicao" class="form-label">Estado do item</label>
                    <select name="condicao" id="condicao" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dados->motivo) ? $dados->motivo : "" ?>>
                        <!--  verifica se o statusItem está no banco de dados e retorna esse status -->
                        <option value=""  <?= isset($dados->condicao) ? $dados->condicao == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->condicao) ? $dados->condicao == 1  ? "selected" : "" : "" ?>>Novo</option>
                        <option value="2" <?= isset($dados->condicao) ? $dados->condicao == 2  ? "selected" : "" : "" ?>>Usado</option>
                    </select>
                </div>

                <div class="col-12 mt-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" id="descricao" placeholder="Descrição do item" <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dados->motivo) ? $dados->motivo : "" ?>><?= isset($dados->descricao) ? $dados->descricao : "" ?></textarea>
                </div>

                <!-- se a ação for view não aparece a hora formatada no formprodutos -->
                <?php  if ($_GET['acao'] == 'view' || $_GET['acao'] == 'delete' || $_GET['acao'] == 'update') { ?>
                <div class="col-6 mt-3">
                    <label for="dataMod" class="form-label">Data da ultima modificação</label>
                    <input type="text" class="form-control" name="dataMod" id="dataMod" value="<?= $dataModFormatada ?>" disabled>
                </div>
                <?php 
                } 
                ?>

                <?php if (isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'delete' && $_GET['acao'] != 'view') : ?>
                <div class="col-6 mt-3">
                    <label for="historico" class="form-label">Histórico de Alterações</label>
                    <select name="historico" id="historico" class="form-control" <?= isset($_GET['acao']) && $_GET['acao'] != 'delete' && $_GET['acao'] != 'insert' && $_GET['acao'] != 'view' ? '' : 'disabled'?>>
                        <option value="">Selecione uma alteração</option>
                        <?php 
                        // Recupera o histórico de alterações do item
                        $historicoQuery = "SELECT * FROM historico_produtos WHERE id_produtos = ?";
                        $historicoData = $db->dbSelect($historicoQuery, 'all', [$_GET['id']]);

                        foreach ($historicoData as $historicoItem): ?>
                            <?php
                            // Encontrar o fornecedor correto com base no fornecedor_id do histórico
                            $fornecedorNome = '';
                            foreach ($dadosFornecedor as $fornecedor) {
                                if ($fornecedor['id'] == $historicoItem['id']) {
                                    $fornecedorNome = $fornecedor['nome'];
                                    
                                }
                            }
                            ?>
                            <!-- Usar o nome do fornecedor encontrado -->
                            <option value="<?= $historicoItem['id'] ?>" data-nome="<?= $historicoItem['nome_produtos'] ?>" data-fornecedor="<?= $historicoItem['fornecedor_id']; ?>" data-descricao="<?= $historicoItem['descricao_anterior'] ?>" data-quantidade="<?= $historicoItem['quantidade_anterior'] ?>" data-status="<?= $historicoItem['status_anterior'] ?>" data-statusitem="<?= $historicoItem['statusItem_anterior'] ?>">
                                <?= $historicoItem['dataMod'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-auto mt-5 mb-4">
                <!-- define o texto de cada botão de acordo com a sua ação -->
                <?php if ($_GET['acao'] == "delete") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Excluir</button>
                <?php endif; ?>

                <?php if ($_GET['acao'] == "update") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Alterar</button>
                <?php endif; ?>

                <?php if ($_GET['acao'] == "insert") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Inserir</button>
                <?php endif; ?>
            </div>
        </form>

        <div class="col-auto mb-4">
            <button onclick="goBack()" class="btn btn-outline-secondary btn-sm">Voltar</button>
        </div>
       
    </main>

    <script src="assets/ckeditor5-build-classic/ckeditor.js"></script>
    
    <script>

        function goBack() {
            window.history.back();
        }

       document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#descricao'), {})
                .then(editor => { // Definindo o editor CKEditor aqui
                    document.getElementById('historico').addEventListener('change', function() {
                        var option = this.options[this.selectedIndex];
                        
                        // Definindo os outros valores conforme necessário
                        document.getElementById('nome').value = option.getAttribute('data-nome');
                        document.getElementById('quantidade').value = option.getAttribute('data-quantidade');
                        // Definindo o texto do setor e fornecedor nos elementos
                        document.getElementById('fornecedor_id').value = option.getAttribute('data-fornecedor');
                        document.getElementById('statusRegistro').value = option.getAttribute('data-status');
                        document.getElementById('condicao').value = option.getAttribute('data-statusitem');
                        editor.setData(option.getAttribute('data-descricao')); 
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