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
            $dados = $db->dbSelect("SELECT * FROM fornecedor WHERE id = ?", 'first',[$_GET['id']]);
        
        // se houver erro na conexão com o banco de dados o catch retorna
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }

    // muda as ações para os nomes das página e muda o estado do item colocando 1 para novo e 2 para usado
    require_once "helpers/Formulario.php";
<<<<<<< HEAD
=======

>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
    // recupera o cabeçalho para a página
    require_once "comuns/cabecalho.php";
    // bloqueia o acesso se o usuário não estiver logado
    require_once "library/protectUser.php";

?>

    <main class="container mt-5">
        <div class="row">
            <div class="col-10">
                <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
                <h2>Fornecedor<?= subTitulo($_GET['acao']) ?></h2>
            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form class="g-3" action="<?= $_GET['acao'] ?>Fornecedor.php" method="POST" id="form">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

            <div class="row">
                <div class="col-4">
                    <label for="nome" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do fornecedor" required autofocus value="<?= isset($dados->nome) ? $dados->nome : "" ?>">
                </div>

                <div class="col-4">
                    <label for="cnpj" class="form-label mt-3">CNPJ</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
<<<<<<< HEAD
                    <input type="text" class="form-control" name="cnpj" id="cnpj" maxlength="18" oninput="formatarCNPJ(this)" placeholder="CNPJ do fornecedor" required value="<?= isset($dados->cnpj) ? $dados->cnpj : "" ?>">
=======
                    <input type="text" class="form-control" name="cnpj" id="cnpj" placeholder="CNPJ do fornecedor" required value="<?= isset($dados->cnpj) ? $dados->cnpj : "" ?>">
>>>>>>> 9a448a63fdf09bd8880301d602cbae2625e49e0b
                </div>

                <div class="col-4">
                    <label for="statusRegistro" class="form-label mt-3">Status Fornecedor</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required>
                        <!--  verifica se o statusRegistro está no banco de dados e retorna esse statusRegistro -->
                        <option value=""  <?= isset($dados->statusRegistro) ? $dados->statusRegistro == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-8">
                    <label for="endereco" class="form-label mt-3">Endereço</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Endereço completo" required value="<?= isset($dados->endereco) ? $dados->endereco : "" ?>">
                </div>

                <div class="col-4">
                    <label for="telefone" class="form-label mt-3">Telefone</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Telefone do fornecedor" required value="<?= isset($dados->telefone) ? $dados->telefone : "" ?>">
                </div>
            </div>

            <div class="col-auto mt-4 mb-4">
                <a href="listafornecedor.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                
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

    <script>
        function formatarCNPJ(campo) {
            // Remove qualquer caracter especial, exceto números
            campo.value = campo.value.replace(/[^\d]/g, '');
            
            // Formata o CNPJ (XX.XXX.XXX/XXXX-XX)
            if (campo.value.length > 2 && campo.value.length <= 5) {
                campo.value = campo.value.replace(/(\d{2})(\d)/, "$1.$2");
            } else if (campo.value.length > 5 && campo.value.length <= 8) {
                campo.value = campo.value.replace(/(\d{2})(\d{3})(\d)/, "$1.$2.$3");
            } else if (campo.value.length > 8 && campo.value.length <= 12) {
                campo.value = campo.value.replace(/(\d{2})(\d{3})(\d{3})(\d)/, "$1.$2.$3/$4");
            } else if (campo.value.length > 12 && campo.value.length <= 14) {
                campo.value = campo.value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d)/, "$1.$2.$3/$4-$5");
            } else if (campo.value.length > 14) {
                campo.value = campo.value.substring(0, 14);
            }
        }

        function formatarCNPJ(cnpjInput) {
            // Remove tudo o que não é dígito
            let cnpj = cnpjInput.value.replace(/\D/g, '');

            // Insere os caracteres especiais
            cnpj = cnpj.replace(/^(\d{2})(\d)/, '$1.$2');
            cnpj = cnpj.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            cnpj = cnpj.replace(/\.(\d{3})(\d)/, '.$1/$2');
            cnpj = cnpj.replace(/(\d{4})(\d)/, '$1-$2');

            // Atualiza o valor do input
            cnpjInput.value = cnpj;
        }
    </script>

<?php

  require_once "comuns/rodape.php";

?>