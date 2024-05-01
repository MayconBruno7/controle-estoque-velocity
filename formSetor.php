<?php 

    $dados = null;

    require_once "library/Database.php";
    // Criando o objeto Db para classe de base de dados
    $db = new Database();

    /*
    *   Se for alteração, exclusão ou visualização busca a UF pelo ID que foi recebido via método GET
    */

    if ($_GET['acao'] != "insert") {

        try {

            $dados = $db->dbSelect(
                "SELECT * FROM setor WHERE id = ?", 
                'first', 
                [$_GET['id']]
            );
            
            if ($dados) {
                $funcionario_id = $dados->responsavel;
            } 


        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }

    // Se o setor do funcionário não foi encontrado, inicializa $setor_funcionario_id como vazio
    $funcionario_id = isset($dados->responsavel) ? $dados->responsavel : "";

    $dadosFuncionarios = $db->dbSelect("SELECT * FROM funcionarios");

    // Verifica se $dadosFuncionarios contém elementos
    $funcionariosCadastrados = !empty($dadosFuncionarios);

    // var_dump(count($dados->responsavel)) > 0;
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
            <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

            <div class="row">

                <div class="col-8">
                    <label for="nome" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do setor" required autofocus value="<?= isset($dados->nome) ? $dados->nome : "" ?>">
                </div>

                <div class="col-4">
                    <label for="statusRegistro" class="form-label mt-3">Status</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required>
                        <!--  verifica se o statusRegistro está no banco de dados e retorna esse statusRegistro -->
                        <option value=""  <?= isset($dados->statusRegistro) ? $dados->statusRegistro == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-4 mt-3">
                    <label for="funcionarios" class="form-label">Responsavel pelo setor</label>
                    <select name="funcionarios" id="funcionarios" class="form-control" <?= !$funcionariosCadastrados ? '' : 'required' ?> <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : '' ?>>
                        <option value="">...</option> <!-- Opção padrão -->
                        <?php foreach ($dadosFuncionarios as $responsavel): ?>
                            <option value="<?= $responsavel['id'] ?>" <?= $responsavel['id'] == $funcionario_id ? 'selected' : '' ?>>
                                <?= $responsavel['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

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