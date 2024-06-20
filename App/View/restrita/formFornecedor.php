<?php

    use App\Library\Formulario;

    $estadoSelecionado = setValor('estado');
    $cidadeSelecionada = setValor('cidade');

?>

<div class="container">
    
    <?= Formulario::titulo('Fornecedor', false, false) ?>

    <a href="<?= baseUrl() ?>Fornecedor/requireAPI/84429695000111">
        <button>Enviar</button>
    </a>

    <form method="POST" action="<?= baseUrl() ?>Fornecedor/<?= $this->getAcao() ?>">

        <div class="row">

            <div class="mb-3 col-4">
                <label for="cnpj" class="form-label">CNPJ</label>
                <input type="text" class="form-control" name="cnpj" id="cnpj" 
                    maxlength="18" oninput="formatarCNPJ(this)" placeholder="Informe o cnpj"
                    value="<?= Formulario::formatarCNPJInput(setValor('cnpj')) ?>"
                    autofocus <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="mb-3 col-4">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" 
                    maxlength="50" placeholder="Informe nome do fornecedor"
                    value="<?= setValor('nome') ?>"
                    <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="mb-3 col-4">
                <label for="statusRegistro" class="form-label">Status</label>
                <select class="form-control" name="statusRegistro" id="statusRegistro" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value=""  <?= setValor('statusRegistro') == ""  ? "SELECTED": "" ?>>...</option>
                    <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED": "" ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED": "" ?>>Inativo</option>
                </select>
            </div>

            <div class="col-6 mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-control" name="estado" id="estado" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="" selected disabled>...</option>
                    <?php foreach ($dados['aEstado'] as $value): ?>
                        <option value="<?= $value['id'] ?>" <?= $estadoSelecionado == $value['id'] ? "selected" : "" ?>><?= $value['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <select class="form-control" name="cidade" id="cidade" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <?php if (empty($cidadeSelecionada)) : ?>
                        <option value="" selected disabled>Escolha um estado</option>
                    <?php endif; ?>

                    <?php if (!empty($cidadeSelecionada)) : ?>
                        <?php foreach ($dados['aCidade'] as $value): ?>
                        <option value="<?= $value['id'] ?>" <?= $cidadeSelecionada == $value['id'] ? "selected" : "" ?>><?= $value['nome'] ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-3 col-5">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" class="form-control" name="bairro" id="bairro" 
                    maxlength="50" placeholder="Informe o bairro"
                    value="<?= setValor('bairro') ?>"
                    <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="mb-3 col-3">
                <label for="endereco" class="form-label">Endereco</label>
                <input type="text" class="form-control" name="endereco" id="endereco" 
                    maxlength="50" placeholder="Informe o endereco"
                    value="<?= setValor('endereco') ?>"
                    <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="mb-3 col-2">
                <label for="numero" class="form-label">Numero</label>
                <input type="text" class="form-control" name="numero" id="numero" 
                    maxlength="50" placeholder="Informe o numero"
                    value="<?= setValor('numero') ?>"
                    <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="mb-3 col-2">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" name="telefone" id="telefone" 
                    maxlength="50" placeholder="Informe o telefone"
                    value="<?= Formulario::formatarTelefone(setValor('telefone')) ?>"
                    oninput="formatarTelefone(this)"
                    <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

            <div class="mb-3">
                <button type="submit" class="btn btn-outline-primary">Gravar</button>&nbsp;&nbsp;
                <?= Formulario::botao('voltar') ?>
            </div>
            
        </div>

    </form>

</div>

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

        $(function() {
            $('#estado').change(function() {
                if ($(this).val()) {
                    $('#cidade').hide();
                    $('.carregando').show();

                    $.getJSON('/Fornecedor/getCidadeComboBox/lista/' + $(this).val(), 
                        function(data) {
                            var options = '<option value="" selected disabled>... Escolha uma cidade ...</option>';
                            for (var i = 0; i < data.length; i++) {
                                options += '<option value="' + data[i].id + '">' + data[i].nome + '</option>';
                            }
                            $('#cidade').html(options);
                            $('#cidade').show();
                        }
                    ).fail(function() {
                        console.error("Erro ao carregar cidades.");
                    }).always(function() {
                        $('.carregando').hide();
                    });
                }
            });
        });
    </script>