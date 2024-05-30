<?php

    use App\Library\Formulario;
    use App\Model\ProdutoModel;

    $acao->form($this->getAcao());

    $dataModFormatada = "";

?>

<script src="<?= baseUrl() ?>assets/ckeditor5/ckeditor.js"></script>

<div class="container">
    
    <?= Formulario::titulo('Produto', false, true) ?>

    <form method="POST" action="<?= baseUrl() ?>Produto/<?= $this->getAcao() ?>">

        <div class="row">

            <div class="col-8">
                <label for="nome" class="form-label">Nome</label>
                <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do item" required autofocus value="<?= isset($dados->nome) ? $dados->nome : "" ?>" <?= $this->getAcao() == 'view' ? 'disabled' : '' ?>><?= isset($dados->motivo) ? $dados->motivo : "" ?>
            </div>

            <div class="col-4">
                <label for="quantidade" class="form-label">Quantidade</label>
                <!--  verifica se a quantidade está no banco de dados e retorna essa quantidade -->
                <input type="number" class="form-control" name="qtd_item" id="quantidade" min="1" max="100"value="<?= isset($dados->quantidade) ? $dados->quantidade : "" ?>" disabled >
                <input type="hidden" name="quantidade" id="hidden" value="<?= isset($dados->quantidade) ? $dados->quantidade : "" ?>" >
            </div>

            <div class="col-6 mt-3">
                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= $this->getAcao() != 'insert' && $this->getAcao() != 'update' ? 'disabled' : ''?> <?= $this->getAcao() == 'delete' || $this->getAcao() == 'view' ? 'disabled' : '' ?>><?= isset($dados->motivo) ? $dados->motivo : "" ?>> 
                    <option value="">...</option>
                    <?php foreach($dadosFornecedor as $fornecedor) : ?>
                        <option value="<?= $fornecedor['id'] ?>" <?= $fornecedor['id'] == $fornecedor_id ? 'selected' : '' ?>>
                            <?= $fornecedor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="statusRegistro" class="form-label">Status</label>
                <select class="form-control" name="statusRegistro" id="statusRegistro" required>
                    <option value=""  <?= setValor('statusRegistro') == ""  ? "SELECTED": "" ?>>...</option>
                    <option value="1" <?= setValor('statusRegistro') == "1" ? "SELECTED": "" ?>>Ativo</option>
                    <option value="2" <?= setValor('statusRegistro') == "2" ? "SELECTED": "" ?>>Inativo</option>
                </select>
            </div>

            <div class="col-3 mt-3">
                <label for="condicao" class="form-label">Estado do item</label>
                <select name="condicao" id="condicao" class="form-control" required <?= $this->getAcao() == 'delete' || $this->getAcao() == 'view' ? 'disabled' : '' ?>><?= isset($dados->motivo) ? $dados->motivo : "" ?>>
                    <!--  verifica se o statusItem está no banco de dados e retorna esse status -->
                    <option value=""  <?= isset($dados->condicao) ? $dados->condicao == "" ? "selected" : "" : "" ?>>...</option>
                    <option value="1" <?= isset($dados->condicao) ? $dados->condicao == 1  ? "selected" : "" : "" ?>>Novo</option>
                    <option value="2" <?= isset($dados->condicao) ? $dados->condicao == 2  ? "selected" : "" : "" ?>>Usado</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" name="descricao" id="descricao" placeholder="Descrição do item" <?= $this->getAcao() == 'delete' || $this->getAcao() == 'view' ? 'disabled' : '' ?><?= isset($dados->motivo) ? $dados->motivo : "" ?>><?= isset($dados->descricao) ? $dados->descricao : "" ?></textarea>
            </div>

            <!-- se a ação for view não aparece a hora formatada no formprodutos -->
            <?php  if ($this->getAcao() == 'view' || $this->getAcao() == 'delete' || $this->getAcao() == 'update') { ?>
            <div class="col-6 mt-3">
                <label for="dataMod" class="form-label">Data da ultima modificação</label>
                <input type="text" class="form-control" name="dataMod" id="dataMod" value="<?= $dataModFormatada ?>" disabled>
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
                    // Recupera o histórico de alterações do item
                    $historicoQuery = "SELECT * FROM historico_produtos WHERE id_produtos = ?";
                    $historicoData = $db->dbSelect($historicoQuery, 'all', [$_GET['id']]);

                    foreach ($historicoData as $historicoItem): ?>
                        <?php
                        // Encontrar o fornecedor correto com base no fornecedor_id do histórico
                        $fornecedorNome = '';
                        foreach ($dadosFornecedor as $fornecedor) {
                            if ($fornecedor['id'] == $historicoItem['id']) {
                                $fornecedorNome = $fornecedor['nome'];
                                
                            }
                        }
                        ?>
                        <!-- Usar o nome do fornecedor encontrado -->
                        <option value="<?= $historicoItem['id'] ?>" data-nome="<?= $historicoItem['nome_produtos'] ?>" data-fornecedor="<?= $historicoItem['fornecedor_id']; ?>" data-descricao="<?= $historicoItem['descricao_anterior'] ?>" data-quantidade="<?= $historicoItem['quantidade_anterior'] ?>" data-status="<?= $historicoItem['status_anterior'] ?>" data-statusitem="<?= $historicoItem['statusItem_anterior'] ?>">
                            <?= $historicoItem['dataMod'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

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