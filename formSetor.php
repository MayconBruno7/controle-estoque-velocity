<?php 

    $dados = [];

    /*
    *   Se for alteração, exclusão ou visualização busca a UF pelo ID que foi recebido via método GET
    */
    if ($_GET['acao'] != "insert") {

        try {
            require_once "library/Database.php";
            // Criando o objeto Db para classe de base de dados
            $db = new Database();
    
            // prepara comando SQL
            $dados = $db->dbSelect("SELECT * FROM setor WHERE id_setor = ?", 'first',[$_GET['id_setor']]);
        
        // se houver erro na conexão com o banco de dados o catch retorna
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }

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
                <h2>Setor<?= subTitulo($_GET['acao']) ?></h2>
            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form class="g-3" action="<?= $_GET['acao'] ?>Setor.php" method="POST" id="form">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id_setor" id="id_setor" value="<?= isset($dados->id_setor) ? $dados->id_setor : "" ?>">

            <div class="row">

                <div class="col-8">
                    <label for="nome_setor" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome_setor" id="nome_setor" placeholder="Nome do setor" required autofocus value="<?= isset($dados->nome_setor) ? $dados->nome_setor : "" ?>">
                </div>

                <div class="col-4">
                    <label for="status_setor" class="form-label mt-3">Status</label>
                    <select name="status_setor" id="status_setor" class="form-control" required>
                        <!--  verifica se o status_setor está no banco de dados e retorna esse status_setor -->
                        <option value=""  <?= isset($dados->status_setor) ? $dados->status_setor == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->status_setor) ? $dados->status_setor == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->status_setor) ? $dados->status_setor == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="responsavel_setor" class="form-label mt-3">Responsável</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="responsavel_setor" id="responsavel_setor" placeholder="Pessoa responsável pelo setor" required autofocus value="<?= isset($dados->responsavel_setor) ? $dados->responsavel_setor : "" ?>">
                </div>

                <!-- se a ação for view não aparece a hora formatada no formsetor -->
                <?php  if ($_GET['acao'] == 'view' || $_GET['acao'] == 'delete') { ?>
        

            <?php 
            } 
            ?>

            <div class="col-6 mt-4 mb-4">
                <a href="listaSetor.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                
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


    <?php

    require_once "comuns/rodape.php";

    ?>