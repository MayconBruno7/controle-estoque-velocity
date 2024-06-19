<?php 

    use App\Library\Formulario;

?>
    <main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
                <?= Formulario::Titulo('Setores', false, false) ?>
            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form method="POST" action="<?= baseUrl() ?>Setor/<?= $this->getAcao() ?>">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

            <div class="row">

                <div class="col-8">
                    <label for="nome" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do setor" required autofocus value="<?= setValor('nome') ?>" <?= $this->getAcao() != 'insert' && $this->getAcao() != 'update' ? 'disabled' : '' ?>>
                </div>

                <div class="col-4">
                    <label for="statusRegistro" class="form-label mt-3">Status</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required <?= $this->getAcao() != 'insert' && $this->getAcao() != 'update' ? 'disabled' : '' ?>>
                        <!--  verifica se o statusRegistro está no banco de dados e retorna esse statusRegistro -->
                        <option value=""  <?= setValor('statusRegistro') == ""  ? "SELECTED": "" ?>>...</option>
                        <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED": "" ?>>Ativo</option>
                        <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED": "" ?>>Inativo</option>
                    </select>
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
                </div>
            </div>

            <div class="form-group col-12 mt-5">
                <?= Formulario::botao('voltar') ?>
                <?php if ($this->getAcao() != "view"): ?>
                    <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
                <?php endif; ?>
            </div>
        </form>
    </main>
