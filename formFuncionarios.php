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

        $dados = $db->dbSelect("SELECT * FROM funcionarios WHERE id_funcionarios = ?", 'first', [$_GET['id_funcionarios']]);

        if ($dados) {
            $setor_funcionario_id = $dados->setor_funcionarios;
        }

    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }
}

// Se o setor do funcionário não foi encontrado, inicializa $setor_funcionario_id como vazio
if (!isset($setor_funcionario_id)) {
    $setor_funcionario_id = "";
}

$dadosSetor = $db->dbSelect("SELECT * FROM setor ORDER BY id_setor");

// muda as ações para os nomes das página e muda o estado do item colocando 1 para novo e 2 para usado
require_once "helpers/Formulario.php";

// recupera o cabeçalho para a página
require_once "comuns/cabecalho.php";
require_once "library/protectUser.php";
require_once "library/Funcoes.php";

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
            <input type="hidden" name="id_funcionarios" id="id_funcionarios" value="<?= isset($dados->id_funcionarios) ? $dados->id_funcionarios : "" ?>">

            <div class="row">

                <div class="col-6">
                    <label for="nome_funcionarios" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome_funcionarios" id="nome_funcionarios" placeholder="Nome do funcionario" required autofocus value="<?= isset($dados->nome_funcionarios) ? $dados->nome_funcionarios : "" ?>">
                </div>

                <div class="col-6">
                    <label for="cpf_funcionarios" class="form-label mt-3">CPF</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="cpf_funcionarios" id="cpf_funcionarios" placeholder="Cpf do funcionario" required autofocus value="<?= isset($dados->cpf_funcionarios) ? Funcoes::formatarCPF($dados->cpf_funcionarios) : "" ?>">
                </div>

                <div class="col-6">
                    <label for="status_funcionarios" class="form-label mt-3">Status</label>
                    <select name="status_funcionarios" id="status_funcionarios" class="form-control" required>
                        <!--  verifica se o status_funcionarios está no banco de dados e retorna esse status_funcionarios -->
                        <option value=""  <?= isset($dados->status_funcionarios) ? $dados->status_funcionarios == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->status_funcionarios) ? $dados->status_funcionarios == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->status_funcionarios) ? $dados->status_funcionarios == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-6">
                    <label for="telefone_funcionarios" class="form-label mt-3">Telefone</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="telefone_funcionarios" id="telefone_funcionarios" placeholder="Telefone" required autofocus value="<?= isset($dados->telefone_funcionarios) ? Funcoes::formataTelefone($dados->telefone_funcionarios) : "" ?>">
                </div>

                <div class="col-4 mt-3">
                    <label for="setor_funcionarios" class="form-label">Setor</label>
                    <select name="setor_funcionarios" id="setor_funcionarios" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] != 'insert' && $_GET['acao'] != 'update' ? 'disabled' : ''?>>
                        <option value="">...</option> <!-- Opção padrão -->
                        <?php foreach ($dadosSetor as $setor): ?>
                            <option value="<?= $setor['id_setor'] ?>" <?= $setor['id_setor'] == $setor_funcionario_id ? 'selected' : '' ?>>
                                <?= $setor['nome_setor'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-4 mt-3">
                    <label for="cargo_funcionario">Cargo</label> 
                    <input type="text" class="form-control mt-2" name="cargo_funcionario" id="cargo_funcionario"  placeholder="Cargo do funcionário">
                </div>

                <div class="col-4">
                    <label for="salario_funcionario" class="form-label mt-3">Salário</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="salario_funcionario" id="salario_funcionario" placeholder="Salário R$" required autofocus value="<?= isset($dados->salario_funcionario) ? Funcoes::valorBr($dados->salario_funcionario) : "" ?>">
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