<?php

    use App\Library\Formulario;

?>

<script src="<?= baseUrl() ?>assets/ckeditor5/ckeditor.js"></script>

<div class="container">
    
    <?= Formulario::titulo('Produto', false, true) ?>

    <form method="POST" action="<?= baseUrl() ?>Produto/<?= $this->getAcao() ?>" enctype="multipart/form-data">

        <div class="row">

            <div class="mb-3 col-8">
                <label for="descricao" class="form-label">Descrição</label>
                <input type="text" class="form-control" name="descricao" id="descricao" 
                    maxlength="50" placeholder="Informe a descrição do Produto"
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

            <div class="mb-3 col-12">
                <label for="categoria_id" class="form-label">Categoria</label>
                <select class="form-control" name="categoria_id" id="categoria_id" required>
                    <option value=""  <?= setValor('categoria_id') == ""  ? "SELECTED": "" ?>>...</option>
                    <?php foreach ($aDados['aCategoria'] as $value): ?>
                        <option value="<?= $value['id'] ?>" <?= setValor('statusRegistro') == $value['id'] ? "SELECTED": "" ?>><?= $value['descricao'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3 col-12">
                <label for="caracteristicas" class="form-label">Características</label>
                <textarea class="form-control" name="caracteristicas" id="caracteristicas"><?= setValor('caracteristicas') ?></textarea>
            </div>

            <div class="mb-3 col-sm-6 col-md-3">
                <label for="saldoEmEstoque" class="form-label">Saldo Em Estoque</label>
                <input type="text" class="form-control" name="saldoEmEstoque" id="saldoEmEstoque" 
                    value="<?= formataValor(setValor('saldoEmEstoque', 0), 3) ?>" dir="rtl">
            </div>

            <div class="mb-3 col-sm-6 col-md-3">
                <label for="custoTotal" class="form-label">Custo Total</label>
                <input type="text" class="form-control" name="custoTotal" id="custoTotal" 
                    value="<?= formataValor(setValor('custoTotal', 0)) ?>" dir="rtl">
            </div>

            <div class="mb-3 col-sm-6 col-md-3">
                <label for="precoVenda" class="form-label">Preço de Venda</label>
                <input type="text" class="form-control" name="precoVenda" id="precoVenda" 
                    value="<?= formataValor(setValor('precoVenda', 0)) ?>" dir="rtl">
            </div>

            <div class="mb-3 col-sm-6 col-md-3">
                <label for="precoPromocao" class="form-label">Preço Promoção</label>
                <input type="text" class="form-control" name="precoPromocao" id="precoPromocao" 
                    value="<?= formataValor(setValor('precoPromocao', 0)) ?>" dir="rtl">
            </div>

            <?php if (in_array($this->getAcao(), ['insert', 'update'])): ?>

                <div class="col-12 mb-3">
                    <label for="anexos" class="form-label">Imagem</label>
                    <input class="form-control" type="file" id="imagem" name="imagem">
                </div>

                <?php endif; ?>

                <?php if (!empty(setValor('imagem'))): ?>

                <div class="mb-3 col-12">
                    <h5>Imagem</h5>
                    <img src="<?= baseUrl() ?>uploads/produto/<?= setValor('imagem') ?>" class="img-thumbnail" height="120" width="120"/>
                </div>

            <?php endif; ?>

            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">
            <input type="hidden" name="nomeImagem" value="<?= setValor('imagem') ?>">

            <div class="mb-3">
                <button type="submit" class="btn btn-outline-primary">Gravar</button>&nbsp;&nbsp;
                <?= Formulario::botao('voltar') ?>
            </div>
            
        </div>

    </form>

</div>

<script type="text/javascript">

    ClassicEditor
        .create( document.querySelector('#caracteristicas'))
        .catch( error => {
            console.error( error );
        })

    $(document).ready( function() { 
        $('#saldoEmEstoque').mask('#.###.###.##0,000', {reverse: true});
        $('#precoVenda').mask('##.###.###.##0,00', {reverse: true});
        $('#precoPromocao').mask('##.###.###.##0,00', {reverse: true});
        $('#custoTotal').mask('##.###.###.##0,00', {reverse: true});
    })

</script>