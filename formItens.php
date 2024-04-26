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
            $dataMod = $db->dbSelect("SELECT DATE_FORMAT(dataMod_itens, '%d/%m/%Y ás %H:%i:%s') AS dataModFormatada FROM itens where id_itens = ?", 'first', [$_GET['id_itens']]);
        
            // Verifica se a dataModFormatada está definida e atribui à variável
            $dataModFormatada = isset($dataMod->dataModFormatada) ? $dataMod->dataModFormatada : '';
        
            // prepara comando SQL
            $dados = $db->dbSelect("SELECT * FROM itens WHERE id_itens = ?", 'first',[$_GET['id_itens']]);
            
            if ($dados) {
                $setor_item_id = $dados->setor_itens;
            }
        
        // se houver erro na conexão com o banco de dados o catch retorna
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }

    $dadosSetor = $db->dbSelect("SELECT * FROM setor ORDER BY id_setor");
    $dadosHistorico = $db->dbSelect("SELECT * FROM historico_itens");

    $dadosFornecedor = $db->dbSelect("SELECT * FROM fornecedor");

    if ($dadosFornecedor) {
        // Verifica se existe um valor de fornecedor definido no item
        $fornecedor_id = isset($dados->fornecedor_id) ? $dados->fornecedor_id : "";
    }

    function obterNomeSetor($setor_id, $db) {
        $query = "SELECT nome_setor FROM setor WHERE id_setor = ?";
        $result = $db->dbSelect($query, 'first', [$setor_id]);
        return $result ? $result->nome_setor : '';
    }
    
    function obterNomeFornecedor($fornecedor_id, $db) {
        $query = "SELECT nome_fornecedor FROM fornecedor WHERE id_fornecedor = ?";
        $result = $db->dbSelect($query, 'first', [$fornecedor_id]);
        return $result ? $result->nome_fornecedor : '';
    }
    
    $nome_setor = isset($setor_item_id) ? obterNomeSetor($setor_item_id, $db) : '';
    $nome_fornecedor = isset($fornecedor_id) ? obterNomeFornecedor($fornecedor_id, $db) : '';
   
    // var_dump($nome_setor = isset($setor_item_id) ? obterNomeSetor($setor_item_id, $db) : '');
    // var_dump($nome_fornecedor = isset($fornecedor_id) ? obterNomeFornecedor($fornecedor_id, $db) : '');
    // exit;

    // var_dump($dadosSetor);
    // var_dump($dadosFornecedor);

    // var_dump($setor_item_id);
    // var_dump($fornecedor_id);




    // muda as ações para os nomes das página e muda o estado do item colocando 1 para novo e 2 para usado
    require_once "helpers/Formulario.php";


    require_once "library/protectUser.php";

    // var_dump($dadosFornecedor); 
    // exit();

?>

    <main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
                <h2>Item<?= subTitulo($_GET['acao']) ?></h2>
            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form class="g-3" action="<?= $_GET['acao'] ?>Itens.php" method="POST" id="form">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= isset($dados->id_itens) ? $dados->id_itens : "" ?>">

            <div class="row">

                <div class="col-8">
                    <label for="nome" class="form-label">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do item" required autofocus value="<?= isset($dados->nome_itens) ? $dados->nome_itens : "" ?>">
                </div>

                <div class="col-4">
                    <label for="quantidade" class="form-label">Quantidade</label>
                    <!--  verifica se a quantidade está no banco de dados e retorna essa quantidade -->
                    <input type="number" class="form-control" name="quantidade" id="quantidade" placeholder="Quantidade itens" min="1" max="100" required value="<?= isset($dados->quantidade_itens) ? $dados->quantidade_itens : "" ?>">
                </div>

                <div class="col-3 mt-3">
                    <label for="setor_id" class="form-label">Setor</label>
                    <select name="setor_id" id="setor_id" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <option value="">...</option> 
                        <?php foreach ($dadosSetor as $setor): ?>
                            <option value="<?= $setor['id_setor'] ?>" <?= $setor['id_setor'] == $setor_item_id ? 'selected' : '' ?>>
                                <?= $setor['nome_setor'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label for="statusRegistro" class="form-label">Estado de registro</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <!--  verifica se o statusRegistro está no banco de dados e retorna esse statusRegistro -->
                        <option value=""  <?= isset($dados->statusRegistro_itens) ? $dados->statusRegistro_itens == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro_itens) ? $dados->statusRegistro_itens == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro_itens) ? $dados->statusRegistro_itens == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label for="statusItem" class="form-label">Estado do item</label>
                    <select name="statusItem" id="statusItem" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <!--  verifica se o statusItem está no banco de dados e retorna esse statusRegistro -->
                        <option value=""  <?= isset($dados->statusItem_itens) ? $dados->statusItem_itens == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusItem_itens) ? $dados->statusItem_itens == 1  ? "selected" : "" : "" ?>>Novo</option>
                        <option value="2" <?= isset($dados->statusItem_itens) ? $dados->statusItem_itens == 2  ? "selected" : "" : "" ?>>Usado</option>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <label for="fornecedor_id" class="form-label">Fornecedor</label>
                    <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <option value="">...</option>
                        <?php foreach($dadosFornecedor as $fornecedor) : ?>
                            <option value="<?= $fornecedor['id_fornecedor'] ?>" <?= $fornecedor['id_fornecedor'] == $fornecedor_id ? 'selected' : '' ?>>
                                <?= $fornecedor['nome_fornecedor'] ?>
                            </option>

                            
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 mt-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" id="descricao" placeholder="Descrição do item" <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'readonly' : ''?>><?= isset($dados->descricao_itens) ? $dados->descricao_itens : "" ?></textarea>
                </div>

                <!-- se a ação for view não aparece a hora formatada no formItens -->
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
                        $historicoQuery = "SELECT * FROM historico_itens WHERE id_item = ?";
                        $historicoData = $db->dbSelect($historicoQuery, 'all', [$_GET['id_itens']]);

                        foreach ($historicoData as $historicoItem): ?>
                            <?php
                            // Encontrar o fornecedor correto com base no fornecedor_id do histórico
                            $fornecedorNome = '';
                            foreach ($dadosFornecedor as $fornecedor) {
                                if ($fornecedor['id_fornecedor'] == $historicoItem['fornecedor_id']) {
                                    $fornecedorNome = $fornecedor['nome_fornecedor'];
                                    
                                }
                            }
                            ?>
                            <!-- Usar o nome do fornecedor encontrado -->
                            <option value="<?= $historicoItem['id'] ?>" data-nome="<?= $historicoItem['nome_item'] ?>" data-fornecedor="<?= $historicoItem['fornecedor_id']; ?>" data-setor="<?= $historicoItem['setor_id'] ?>" data-descricao="<?= $historicoItem['descricao_anterior'] ?>" data-quantidade="<?= $historicoItem['quantidade_anterior'] ?>" data-statusregistro="<?= $historicoItem['statusRegistro_anterior'] ?>" data-statusitem="<?= $historicoItem['statusItem_anterior'] ?>">
                                
                                <?= $historicoItem['dataMod'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-auto mt-5 mb-4">
                <a href="listaItens.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                
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
    </main>
    
    <script src="assets/ckeditor5-build-classic/ckeditor.js"></script>
    
    <script>
       document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#descricao'), {})
                .then(editor => { // Definindo o editor CKEditor aqui
                    document.getElementById('historico').addEventListener('change', function() {
                        var option = this.options[this.selectedIndex];
                        
                        // Definindo o texto do setor e fornecedor nos elementos
                        document.getElementById('setor_id').value = option.getAttribute('data-setor'); 
                        document.getElementById('fornecedor_id').value = option.getAttribute('data-fornecedor');
                        
                        // Definindo os outros valores conforme necessário
                        document.getElementById('nome').value = option.getAttribute('data-nome');
                        editor.setData(option.getAttribute('data-descricao')); 
                        document.getElementById('quantidade').value = option.getAttribute('data-quantidade');
                        document.getElementById('statusRegistro').value = option.getAttribute('data-statusregistro');
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