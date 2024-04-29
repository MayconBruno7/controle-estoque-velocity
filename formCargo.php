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
            $dados = $db->dbSelect("SELECT * FROM cargo WHERE id = ?", 'first',[$_GET['id']]);
        
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
                <h2>Cargo<?= subTitulo($_GET['acao']) ?></h2>
            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form class="g-3" action="<?= $_GET['acao'] ?>Cargo.php" method="POST" id="form">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

            <div class="row">

                <div class="col-9">
                    <label for="nome" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do fornecedor" required autofocus value="<?= isset($dados->nome) ? $dados->nome : "" ?>">
                </div>


                <div class="col-3 mt-3">
                    <label for="statusRegistro" class="form-label">Estado de registro</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <!--  verifica se o status está no banco de dados e retorna esse status -->
                        <option value=""  <?= isset($dados->statusRegistro) ? $dados->statusRegistro == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>



            <div class="col-auto mt-4 mb-4">
                <a href="listaCargo.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                
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