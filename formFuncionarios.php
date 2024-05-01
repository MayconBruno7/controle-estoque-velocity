<?php 

    $dados = [];

    require_once "library/Database.php";
    // Criando o objeto Db para classe de base de dados
    $db = new Database();

    /*
    *   Se for alteração, exclusão ou visualização busca a UF pelo ID que foi recebido via método GET
    */

    if ($_GET['acao'] != "insert") {

        try {

            $dados = $db->dbSelect("SELECT * FROM funcionarios WHERE id = ?", 'first', [$_GET['id']]);

            if ($dados) {
                $setor_funcionario_id = $dados->setor;
            }

        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }

    // Se o setor do funcionário não foi encontrado, inicializa $setor_funcionario_id como vazio
    if (!isset($setor_funcionario_id)) {
        $setor_funcionario_id = "";
    }

    $dadosSetor = $db->dbSelect("SELECT * FROM setor ORDER BY id");

    // Verifica se $dadosFuncionarios contém elementos
    $setoresCadastrados = !empty($dadosSetor);

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
                <h2>Funcionário<?= subTitulo($_GET['acao']) ?></h2>
            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form class="g-3" action="<?= $_GET['acao'] ?>Funcionarios.php" method="POST" id="form">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

            <div class="row">

                <div class="col-4">
                    <label for="nome" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do funcionario" required autofocus value="<?= isset($dados->nome) ? $dados->nome : "" ?>">
                </div>

                <div class="col-4">
                    <label for="cpf" class="form-label mt-3">CPF</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="cpf" id="cpf" placeholder="Cpf do funcionario" required autofocus value="<?= isset($dados->cpf) ? $dados->cpf : "" ?>">
                </div>

                <div class="col-4">
                    <label for="statusRegistro" class="form-label mt-3">Estado do registro</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required>
                        <!--  verifica se o statusRegistro está no banco de dados e retorna esse statusRegistro -->
                        <option value=""  <?= isset($dados->statusRegistro) ? $dados->statusRegistro == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-4">
                    <label for="telefone" class="form-label mt-3">Telefone</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Endereço completo" required autofocus value="<?= isset($dados->telefone) ? $dados->telefone : "" ?>">
                </div>

                <div class="col-4 mt-3">
                    <label for="setor" class="form-label">Setor</label>
                    <select name="setor" id="setor" class="form-control" <?= !$setoresCadastrados ? '' : 'required' ?> <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                    <?php foreach ($dadosSetor as $setor): ?>
                        <option value="">...</option> <!-- Opção padrão -->
                            <option value="<?= $setor['id'] ?>" <?= $setor['id'] == $setor_funcionario_id ? 'selected' : '' ?>>
                                <?= $setor['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-4">
                    <label for="salario" class="form-label mt-3">Salário</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="salario" id="salario" placeholder="Salário R$" required autofocus value="<?= isset($dados->salario) ? $dados->salario : "" ?>">
                </div>

            </div>

            <div class="col-auto mt-4 mb-4">
                <a href="listafuncionarios.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                
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