<?= $this->extend('layout/layout_default') ?>

<?= $this->section('conteudo') ?>

<div class="container" style="margin-top: 100px;">
    <?= exibeTitulo("Cargo", ['acao' => $action]) ?>
</div>

<main class="container mt-5">
    <?= form_open(base_url() . 'Cargo/' . ($action == "delete" ? "delete" : "store")) ?>

        <!-- Verifica se o id está no banco de dados e retorna esse id -->
        <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

        <div class="row">
            <div class="col-9">
                <label for="nome" class="form-label mt-3">Nome</label>
                <!-- Verifica se o nome está no banco de dados e retorna esse nome -->
                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do cargo" required autofocus value="<?= setValor('nome') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                <?= setaMsgErrorCampo('nome', $errors) ?>
            </div>

            <div class="col-3 mt-3">
                <label for="statusRegistro" class="form-label">Estado de registro</label>
                <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="" <?= setValor('statusRegistro') == "" ? "SELECTED" : "" ?>>...</option>
                    <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED" : "" ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED" : "" ?>>Inativo</option>
                </select>
                <?= setaMsgErrorCampo('statusRegistro', $errors) ?>
            </div>
        </div>

        <div class="form-group col-12 mt-5">
            <?php if ($this->getAcao() != "view"): ?>
                <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
            <?php endif; ?>
            <a href="<?= base_url() ?>/Cargo" class="btn btn-secondary">Voltar</a>
        </div>

    <?= form_close() ?>
</main>

<?= $this->endSection() ?>
