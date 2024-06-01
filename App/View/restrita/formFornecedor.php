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
                    maxlength="50" placeholder="Informe o cnpj"
                    value="<?= setValor('cnpj') ?>"
                    autofocus>
            </div>

            <div class="mb-3 col-4">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" 
                    maxlength="50" placeholder="Informe nome do fornecedor"
                    value="<?= setValor('nome') ?>"
                    autofocus>
            </div>

            <div class="mb-3 col-4">
                <label for="statusRegistro" class="form-label">Status</label>
                <select class="form-control" name="statusRegistro" id="statusRegistro" required>
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
                    autofocus>
            </div>

            <div class="mb-3 col-6">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" class="form-control" name="cidade" id="cidade" 
                    maxlength="50" placeholder="Informe a cidade"
                    value="<?= setValor('cidade') ?>"
                    autofocus>
            </div>

            <div class="mb-3 col-5">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" class="form-control" name="bairro" id="bairro" 
                    maxlength="50" placeholder="Informe o bairro"
                    value="<?= setValor('bairro') ?>"
                    autofocus>
            </div>

            <div class="mb-3 col-3">
                <label for="endereco" class="form-label">Endereco</label>
                <input type="text" class="form-control" name="endereco" id="endereco" 
                    maxlength="50" placeholder="Informe o endereco"
                    value="<?= setValor('endereco') ?>"
                    autofocus>
            </div>

            <div class="mb-3 col-2">
                <label for="numero" class="form-label">Numero</label>
                <input type="text" class="form-control" name="numero" id="numero" 
                    maxlength="50" placeholder="Informe o numero"
                    value="<?= setValor('numero') ?>"
                    autofocus>
            </div>

            <div class="mb-3 col-2">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" name="telefone" id="telefone" 
                    maxlength="50" placeholder="Informe o telefone"
                    value="<?= setValor('telefone') ?>"
                    autofocus>
            </div>

            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

            <div class="mb-3">
                <button type="submit" class="btn btn-outline-primary">Gravar</button>&nbsp;&nbsp;
                <?= Formulario::botao('voltar') ?>
            </div>
            
        </div>

    </form>

</div>