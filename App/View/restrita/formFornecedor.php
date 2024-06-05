<?php

use App\Library\Formulario;

?>

<div class="container">
    
    <?= Formulario::titulo('Fornecedor', false, false) ?>

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

            <div class="mb-3 col-6">
                <label for="estado" class="form-label">Estado</label>
                <input type="text" class="form-control" name="estado" id="estado" 
                    maxlength="50" placeholder="Informe estado"
                    value="<?= setValor('estado') ?>"
                    <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="mb-3 col-6">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" class="form-control" name="cidade" id="cidade" 
                    maxlength="50" placeholder="Informe a cidade"
                    value="<?= setValor('cidade') ?>"
                    <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
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