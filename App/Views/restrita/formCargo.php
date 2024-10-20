<?= $this->extend('layout/layout_default') ?>

<?= $this->section('conteudo') ?>

<?= exibeTitulo("Cargo", ['acao' => $action]) ?>

<main class="container mt-5">
    <?= form_open(base_url() . 'Cargo/' . ($action == "delete" ? "delete" : "store")) ?>

        <div class="row">
            <div class="col-9">
                <label for="nome" class="form-label mt-3">Nome</label>
                <!-- Verifica se o nome está no banco de dados e retorna esse nome -->
                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do cargo" required autofocus value="<?= setValor('nome', $data) ?>" <?= $action == 'view' || $action == 'delete' ? 'disabled' : '' ?>>
                <?= setaMsgErrorCampo('nome', $errors) ?>
            </div>

            <div class="col-3 mt-3">
                <label for="statusRegistro" class="form-label">Estado de registro</label>
                <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $action == 'view' || $action == 'delete' ? 'disabled' : '' ?>>
                    <option value="" <?= setValor('statusRegistro', $data) == "" ? "SELECTED" : "" ?>>...</option>
                    <option value="1" <?= setValor('statusRegistro', $data) == "1" ? "SELECTED" : "" ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro', $data) == "2" ? "SELECTED" : "" ?>>Inativo</option>
                </select>
                <?= setaMsgErrorCampo('statusRegistro', $errors) ?>
            </div>
        </div>

        <input type="hidden" name="id" value="<?= setValor("id", $data) ?>">
        <input type="hidden" name="action" value="<?= $action ?>">

        <div class="form-group col-12 mt-5">
            <?php if ($action != "view"): ?>
                <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
            <?php endif; ?>
            <a href="<?= base_url() ?>/Cargo" class="btn btn-secondary">Voltar</a>
        </div>

    <?= form_close() ?>
</main>

<?= $this->endSection() ?>
