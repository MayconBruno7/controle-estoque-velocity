<?php

use App\Library\Formulario;

?>

<div class="container">
    
    <?= Formulario::titulo('Categoria', false, true) ?>

    <form method="POST" action="<?= baseUrl() ?>Categoria/<?= $this->getAcao() ?>">

        <div class="row">

            <div class="mb-3 col-8">
                <label for="descricao" class="form-label">Descrição</label>
                <input type="text" class="form-control" name="descricao" id="descricao" 
                    maxlength="50" placeholder="Informe a descrição da categoria"
                    value="<?= setValor('descricao') ?>"
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

            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

            <div class="mb-3">
                <button type="submit" class="btn btn-outline-primary">Gravar</button>&nbsp;&nbsp;
                <?= Formulario::botao('voltar') ?>
            </div>
            
        </div>

    </form>

</div>