<?php

    use App\Library\Formulario;

?>

<div class="container">
    
    <div class="container" style="margin-top: 100px;">
        <?= Formulario::titulo('Produto', false, false) ?>
    </div>

    <?php

        if ($this->getAcao() != 'insert') {
            ?>
            <div class="row">
                <div class="col-12 d-flex justify-content-start">
                    <a href="<?= baseUrl() ?>HistoricoProdutoMovimentacao/index/<?= $this->getAcao() ?>/<?= $this->getId() ?>" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Visualizar">Visualizar Histórico de Movimentações</a>
                </div>
            </div>
        <?php
        }
    ?>

    <form method="POST" action="<?= baseUrl() ?>Produto/<?= $this->getAcao() ?>">

        <div class="row">

            <div class="col-8">
                <label for="nome" class="form-label">Nome</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do item" required autofocus value="<?= setValor('nome') ?>" <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
            </div>

            <div class="col-4">
                <label for="quantidade" class="form-label">Quantidade</label>
                <!--  verifica se a quantidade está no banco de dados e retorna essa quantidade -->
                <input type="number" class="form-control" name="qtd_item" id="quantidade" min="1" max="100" value="<?= setValor('quantidade') ?>" disabled >
                <input type="hidden" name="quantidade" id="hidden" value="<?= setValor('quantidade') ?>" >
            </div>

            <div class="mt-3 mb-3 col-6">
                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                <select class="form-control" name="fornecedor_id" id="fornecedor_id"  
                <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>
                <?= !empty($aDados['aFornecedor']) ? 'required' : '' ?>                
                >
                    <option value="" <?= setValor('fornecedor') == ""  ? "SELECTED": "" ?>>...</option>
                    <?php foreach ($aDados['aFornecedor'] as $value) : ?>
                        <option value="<?= $value['id'] ?>" <?= setValor('fornecedor') == $value['id'] ? "SELECTED": "" ?>><?= $value['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="statusRegistro" class="form-label">Status</label>
                <select class="form-control" name="statusRegistro" id="statusRegistro" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value=""  <?= setValor('statusRegistro') == ""  ? "SELECTED": "" ?>>...</option>
                    <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED": "" ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED": "" ?>>Inativo</option>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="condicao" class="form-label">Estado do item</label>
                <select name="condicao" id="condicao" class="form-control" required <?= $this->getAcao() == 'delete' || $this->getAcao() == 'view' ? 'disabled' : '' ?>><?= setValor('condicao') ?>>
                    <!--  verifica se o statusItem está no banco de dados e retorna esse status -->
                    <option value=""  <?= setValor('condicao') == "" ? "selected" : ""  ?>>...</option>
                    <option value="1" <?= setValor('condicao') == 1  ? "selected" : ""  ?>>Novo</option>
                    <option value="2" <?= setValor('condicao') == 2  ? "selected" : ""  ?>>Usado</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" name="descricao" id="descricao" placeholder="Descrição do item" <?= $this->getAcao() == 'delete' || $this->getAcao() == 'view' ? 'disabled' : '' ?>><?= setValor('descricao') ?></textarea>
            </div>

            <!-- se a ação for view não aparece a hora formatada no formprodutos -->
            <?php  if ($this->getAcao() == 'view' || $this->getAcao() == 'delete' || $this->getAcao() == 'update') { ?>
            <div class="col-6 mt-3">
                <label for="dataMod" class="form-label">Data da ultima modificação</label>
                <input type="text" class="form-control" name="dataMod" id="dataMod" value="<?= setValor('dataMod') ?>" disabled>

                <input type="hidden" class="form-control" name="dataMod" id="dataMod" value="<?= setValor('dataMod') ?>">
            </div>
            <?php 
            } 
            ?>

            <?php if ($this->getAcao() != 'insert' && $this->getAcao() != 'delete' && $this->getAcao() != 'view') : ?>
            <div class="col-6 mt-3">
                <label for="historico" class="form-label">Histórico de Alterações</label>
                <select name="historico" id="historico" class="form-control" <?= $this->getAcao() != 'delete' && $this->getAcao() != 'insert' && $this->getAcao() != 'view' ? '' : 'disabled'?>>
                    <option value="">Selecione uma alteração</option>
                    <?php 
                    
                    foreach ($aDados['aHistoricoProduto'] as $historicoItem): ?>
                        <?php

                        // Encontrar o fornecedor correto com base no fornecedor_id do histórico
                        $fornecedorNome = '';
                        
                        if (setValor('fornecedor') == $historicoItem['fornecedor_id']) {
                            $fornecedorNome = $fornecedor['nome'];
                        }
                    
                        ?>

                        <!-- Usar o nome do fornecedor encontrado -->
                        <option value="<?= $historicoItem['id'] ?>" data-nome="<?= $historicoItem['nome_produtos'] ?>" data-fornecedor="<?= $historicoItem['fornecedor_id']; ?>" data-descricao="<?= $historicoItem['descricao_anterior'] ?>" data-quantidade="<?= $historicoItem['quantidade_anterior'] ?>" data-status="<?= $historicoItem['status_anterior'] ?>" data-statusitem="<?= $historicoItem['statusItem_anterior'] ?>">
                            <?= $historicoItem['dataMod'] != '0000-00-00 00:00:00' ? $historicoItem['dataMod'] : 'Primeira alteração' ?>
                        </option>
                    <?php endforeach; ?>
                    
                </select>
            </div>
            <?php endif; ?>

            <input type="hidden" name="id" id="id" value="<?= setValor('id') ?>">

            <div class="form-group col-12 mt-5">
                <?= Formulario::botao('voltar') ?>
                <?php if ($this->getAcao() != "view"): ?>
                    <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
                <?php endif; ?>
            </div>
            
        </div>

    </form>

</div>

<script src="<?= baseUrl() ?>assets/ckeditor5/ckeditor.js"></script>

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#descricao'), {})
            .then(editor => { // Definindo o editor CKEditor aqui
                document.getElementById('historico').addEventListener('change', function() {
                    var option = this.options[this.selectedIndex];
                    
                    // Definindo os outros valores conforme necessário
                    document.getElementById('nome').value = option.getAttribute('data-nome');
                    document.getElementById('quantidade').value = option.getAttribute('data-quantidade');
                    // Definindo o texto do setor e fornecedor nos elementos
                    document.getElementById('fornecedor_id').value = option.getAttribute('data-fornecedor');
                    document.getElementById('statusRegistro').value = option.getAttribute('data-status');
                    document.getElementById('condicao').value = option.getAttribute('data-statusitem');
                    editor.setData(option.getAttribute('data-descricao')); 
                    console.log(option);
                });
            })
            .catch(error => {
                console.error(error);
            });
    });

</script>