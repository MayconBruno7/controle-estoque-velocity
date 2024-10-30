<?= $this->extend('layout/layout_default') ?>

<?= $this->section('conteudo') ?>

<main class="container">

    <?= exibeTitulo("Departamento", ['acao' => $action]) ?>

    <section class="mb-5">

        <?= form_open("Departamento/" . ($action == "delete" ? "delete" : "store")) ?>

            <div class="row">

                <div class="form-group col-12 col-md-8">
                    <label for="descricao" class="form-label">Descrição</label>
                    <input type="text" name="descricao" id="descricao"  class="form-control" maxlength="50" value="<?= setValor("descricao", $data) ?>" required autofocus>
                    <?= setaMsgErrorCampo("descricao", $errors) ?>
                </div>

                <div class="form-group col-12 col-md-4">
                    <?= comboboxStatus(setValor("statusRegistro", $data)) ?>
                    <?= setaMsgErrorCampo("statusRegistro", $errors) ?>
                </div>

                <input type="hidden" name="action" value="<?= $action ?>">
                <input type="hidden" name="id" value="<?= setValor("id", $data) ?>">

                <a href="<?= base_url() ?>/Departamento" class="ml-3">Voltar</a>
                <?php if ($action != 'view'): ?>
                    <button type="submit" value="submit" class="button button-login ml-3">Gravar</button>
                <?php endif; ?>
            </div>

        <?= form_close() ?>

    </section>
    
</main>

<?= $this->endSection() ?>