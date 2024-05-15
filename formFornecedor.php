<?php 

    require_once "library/protectUser.php";

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
                    <label for="cnpj" class="form-label mt-3">CNPJ</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="cnpj" id="cnpj" maxlength="18" oninput="formatarCNPJ(this)" placeholder="CNPJ do fornecedor"  autofocus required value="<?= isset($dados->cnpj) ? formatarCNPJinput($dados->cnpj) : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-4">
                    <label for="nome" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do fornecedor" required value="<?= isset($dados->nome) ? $dados->nome : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-4">
                    <label for="statusRegistro" class="form-label mt-3">Status Fornecedor</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                        <!--  verifica se o statusRegistro está no banco de dados e retorna esse statusRegistro -->
                        <option value=""  <?= isset($dados->statusRegistro) ? $dados->statusRegistro == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-6">
                    <label for="estado" class="form-label mt-3">Estado</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="estado" id="estado" placeholder="Estado" required value="<?= isset($dados->estado) ? $dados->estado : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-6">
                    <label for="cidade" class="form-label mt-3">Cidade</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Cidade" required value="<?= isset($dados->cidade) ? $dados->cidade : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-5">
                    <label for="bairro" class="form-label mt-3">Bairro</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro" required value="<?= isset($dados->bairro) ? $dados->bairro : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-3">
                    <label for="endereco" class="form-label mt-3">Endereço</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Endereco" required value="<?= isset($dados->endereco) ? $dados->endereco : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-2">
                    <label for="numero" class="form-label mt-3">Número</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="numero" id="numero" placeholder="Numero" required value="<?= isset($dados->numero) ? $dados->numero : "" ?>" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
                </div>

                <div class="col-2">
                    <label for="telefone" class="form-label mt-3">Telefone</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Telefone do fornecedor" required value="<?= isset($dados->telefone) ? formatarTelefone($dados->telefone) : "" ?>" oninput="formatarTelefone(this)" <?= isset($_GET['acao']) && $_GET['acao'] == 'delete' || $_GET['acao'] == 'view' ? 'disabled' : '' ?>>
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

        function formatarCNPJinput(cnpjInput) {
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

        function formatarTelefone(input) {
            // Remove todos os caracteres não numéricos
            var telefone = input.value.replace(/\D/g, '');

            // Verifica o tamanho máximo do número de telefone
            var maxLength = 11; // Se quiser permitir mais dígitos, ajuste o valor aqui

            // Formatação do número de telefone
            if (telefone.length <= maxLength) {
                telefone = telefone.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})/, '($1) $2 $3-$4');
            } else {
                // Caso o número de telefone tenha mais dígitos do que o permitido
                telefone = telefone.slice(0, maxLength);
                telefone = telefone.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})/, '($1) $2 $3-$4');
            }

            // Atualiza o valor do input com o telefone formatado
            input.value = telefone;
        }

        const campoCNPJ = document.getElementById('cnpj');

        campoCNPJ.addEventListener('input', function() {
            if (campoCNPJ.value.length === 18) {
                // Quando o CNPJ tiver 14 dígitos, faça a solicitação para o servidor intermediário
                fetch('requireAPI.php?cnpj=' + campoCNPJ.value)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        // Verifica se os campos retornados são undefined
                        if (data.nome === undefined || data.uf === undefined || data.municipio === undefined || data.bairro === undefined || data.logradouro === undefined || data.numero === undefined || data.telefone === undefined) {
                            // Limpa os campos do formulário
                            document.getElementById('nome').value = '';
                            document.getElementById('estado').value = '';
                            document.getElementById('cidade').value = '';
                            document.getElementById('bairro').value = '';
                            document.getElementById('endereco').value = '';
                            document.getElementById('numero').value = '';
                            document.getElementById('telefone').value = '';
                        } else {
                            // Atualize os campos com os dados da API
                            document.getElementById('nome').value = (data['fantasia'] === '') ? data['nome'] : data['fantasia'];
                            document.getElementById('estado').value = data['uf'];
                            document.getElementById('cidade').value = data['municipio'];
                            document.getElementById('bairro').value = data['bairro'];
                            document.getElementById('endereco').value = data['logradouro'];
                            document.getElementById('numero').value = data['numero'];
                            document.getElementById('telefone').value = data['telefone'];
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao consultar API:', error);
                    });
            } else if (campoCNPJ.value === "") {
                // Limpa os campos do formulário se o CNPJ estiver vazio
                document.getElementById('nome').value = '';
                document.getElementById('estado').value = '';
                document.getElementById('cidade').value = '';
                document.getElementById('bairro').value = '';
                document.getElementById('endereco').value = '';
                document.getElementById('numero').value = '';
                document.getElementById('telefone').value = '';
            }
        });

    </script>

<?php

  require_once "comuns/rodape.php";

?>