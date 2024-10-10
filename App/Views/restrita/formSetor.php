<?= $this->extend('layout/layout_default') ?>

<?= $this->section('conteudo') ?>

<main class="container mt-5">


    <div class="container" style="margin-top: 100px;">
        <?= exibeTitulo("Setor", ['acao' => $action]) ?>
    </div>

    <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
    <?= form_open(base_url() . 'Setor/' . ($action == "delete" ? "delete" : "store")) ?>

        <!--  verifica se o id está no banco de dados e retorna esse id -->
        <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

        <div class="row">

            <div class="col-8">
                <label for="nome" class="form-label mt-3">Nome</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do setor" required autofocus value="<?= setValor('nome') ?>" <?= $this->getAcao() != 'insert' && $this->getAcao() != 'update' ? 'disabled' : '' ?>>
                <?= setaMsgErrorCampo('nome', $errors) ?>
            </div>

            <div class="col-4">
                <label for="statusRegistro" class="form-label mt-3">Status</label>
                <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $this->getAcao() != 'insert' && $this->getAcao() != 'update' ? 'disabled' : '' ?>>
                    <!--  verifica se o statusRegistro está no banco de dados e retorna esse statusRegistro -->
                    <option value=""  <?= setValor('statusRegistro') == ""  ? "SELECTED": "" ?>>...</option>
                    <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED": "" ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED": "" ?>>Inativo</option>
                </select>
                <?= setaMsgErrorCampo('statusRegistro', $errors) ?>

            </div>

            <div class="col-4 mt-3">
                <label for="funcionarios" class="form-label">Responsavel pelo setor</label>
                <select name="funcionarios" id="funcionarios" class="form-control" 
                <?= $this->getAcao() != 'insert' && $this->getAcao() != 'update' ? 'disabled' : '' ?>
                <?= !empty($aDados['aFuncionario']) ? 'required' : '' ?>   
                >
                    <option value="">...</option> <!-- Opção padrão -->
                    <?php foreach ($aDados['aFuncionario'] as $value): ?>
                        <option value="<?= $value['id'] ?>" <?= $value['id'] == setValor('id') ? 'selected' : '' ?>>
                            <?= $value['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= setaMsgErrorCampo('funcionarios', $errors) ?>

            </div>
        </div>

        <div class="form-group col-12 mt-5">
            <?php if ($this->getAcao() != "view"): ?>
                <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
            <?php endif; ?>

            <a href="<?= base_url() ?>/Setor" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</main>
