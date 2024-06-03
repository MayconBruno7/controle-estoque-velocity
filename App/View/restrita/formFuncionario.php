<?php 

    // if ($_GET['acao'] != "insert") {

    //     try {

    //         $dados = $db->dbSelect("SELECT * FROM funcionarios WHERE id = ?", 'first', [$_GET['id']]);

    //         if ($dados) {
    //             $setor_funcionario_id = $dados->setor;
    //         }

    //     } catch (Exception $ex) {
    //         echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    //     }
    // }

    // // Se o setor do funcionário não foi encontrado, inicializa $setor_funcionario_id como vazio
    // if (!isset($setor_funcionario_id)) {
    //     $setor_funcionario_id = "";
    // }

    // $dadosSetor = $db->dbSelect("SELECT * FROM setor ORDER BY id");

    // // Verifica se $dadosFuncionarios contém elementos
    // $setoresCadastrados = !empty($dadosSetor);
    use App\Library\Formulario;

?>

    <main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
                <?= Formulario::titulo('Funcionários', false, false) ?>

            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form method="POST" action="<?= baseUrl() ?>Funcionario/<?= $this->getAcao() ?>">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

            <div class="row">

                <div class="col-4">
                    <label for="nome" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do funcionario" required autofocus value="<?= setValor('nome') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                </div>

                <div class="col-4">
                    <label for="cpf" class="form-label mt-3">CPF</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="cpf" id="cpf" placeholder="Cpf do funcionario" maxlength="14" required autofocus value="<?= setValor('cpf') ?>" oninput="formatarCPF(this)" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                </div>

                <div class="col-4">
                    <label for="statusRegistro" class="form-label mt-3">Estado do registro</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                        <option value=""  <?= setValor('statusRegistro') == ""  ? "SELECTED": "" ?>>...</option>
                        <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED": "" ?>>Ativo</option>
                        <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED": "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-4">
                    <label for="telefone" class="form-label mt-3">Telefone</label>
                    <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Telefone" maxlength="14" required autofocus value="<?= setValor('telefone') ?>" oninput="formatarTelefone(this)" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                </div>

                <div class="col-4 mt-3">
                    <label for="setor" class="form-label">Setor</label>
                    <select name="setor" id="setor" class="form-control" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option> <!-- Opção padrão -->
                    <?php foreach ($dados['aSetor'] as $setor): ?>
                            <option value="<?= $setor['id'] ?>" <?= setValor('setor') == $setor['id'] ? 'selected' : '' ?>>
                                <?= $setor['nome'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-4">
                    <label for="salario" class="form-label mt-3">Salário</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="salario" id="salario" placeholder="Salário R$" required autofocus value="<?= setValor('salario') ?>" oninput="formatarSalario(this)" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                </div>
            </div>
       

            <div class="form-group col-12 mt-5">
                <?= Formulario::botao('voltar') ?>
                <?php if ($this->getAcao() != "view"): ?>
                    <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
                <?php endif; ?>
            </div>
        </form>
    </main>

    <script>
        function formatarCPF(campo) {
            // Remove todos os caracteres que não são números
            var cpf = campo.value.replace(/\D/g, '');

            // Adiciona pontos e traços conforme o padrão do CPF
            if (cpf.length > 3) {
                cpf = cpf.substring(0, 3) + "." + cpf.substring(3);
            }
            if (cpf.length > 7) {
                cpf = cpf.substring(0, 7) + "." + cpf.substring(7);
            }
            if (cpf.length > 11) {
                cpf = cpf.substring(0, 11) + "-" + cpf.substring(11);
            }

            // Atualiza o valor do input
            campo.value = cpf;
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

        function formatarSalario(input) {
            // Remove todos os caracteres não numéricos
            var valor = input.value.replace(/\D/g, '');

            // Verifica se o valor não está vazio
            if (valor !== '') {
                // Divide o valor em parte inteira e parte decimal
                var parteInteira = valor.substring(0, valor.length - 2);
                var parteDecimal = valor.substring(valor.length - 2);

                // Adiciona o separador de milhares (ponto)
                parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Formata o valor como moeda (R$)
                valor = parteInteira + ',' + parteDecimal;

                // Atualiza o valor do input
                input.value = valor;
            } else {
                // Se o valor estiver vazio, define o valor como vazio
                input.value = '';
            }
        }
    </script>

