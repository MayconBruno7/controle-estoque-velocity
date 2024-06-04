<?php 
    
    use App\Library\Formulario;

?>

<?= Formulario::titulo('Cargos', false, false) ?>


    <main class="container mt-5">

        <form method="POST" action="<?= baseUrl() ?>Cargo/<?= $this->getAcao() ?>">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

            <div class="row">

                <div class="col-9">
                    <label for="nome" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do fornecedor" required autofocus value="<?= setValor('nome') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                </div>


                <div class="col-3 mt-3">
                    <label for="statusRegistro" class="form-label">Estado de registro</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value=""  <?= setValor('statusRegistro') == ""  ? "SELECTED": "" ?>>...</option>
                    <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED": "" ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED": "" ?>>Inativo</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-outline-primary">Gravar</button>&nbsp;&nbsp;
                <?= Formulario::botao('voltar') ?>
            </div>
        </form>
    </main>
