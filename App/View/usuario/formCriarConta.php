<?php

use App\Library\Formulario;

?>

<script type="text/javascript" src="<?= baseUrl(); ?>assets/js/usuario.js"></script>

<main class="container">

    <?= Formulario::titulo("Criar Conta", false, false) ?>

    <section class="mb-5">

        <form method="POST" action="<?= baseUrl() ?>Login/novaContaVisitante">

            <div class="row">

                <div class="form-group col-12 col-md-8">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome"  class="form-control" maxlength="50" 
                    value="<?= setValor('nome') ?>" 
                    required autofocus placeholder="Nome completo do usuário">
                </div>

                <div class="form-group col-12 col-md-8 mt-2">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="text" name="email" id="email"  class="form-control" maxlength="100" 
                        value="<?= setValor('email') ?>" 
                        required placeholder="E-mail: seu-nome@dominio.com">
                </div>

                <div class="form-group col-12 col-md-8 mt-2">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" name="senha" id="senha"  class="form-control" maxlength="250" 
                        value="<?= setValor('senha') ?>" 
                        required placeholder="Informe uma senha"
                        onkeyup="checa_segur_senha('senha', 'msgSenha', 'btGravar');">
                    <div id="msgSenha" class="msgNivel_senha"></div>
                </div>

                <div class="form-group col-12 col-md-8 mt-2">
                    <label for="confSenha" class="form-label">Confere a senha</label>
                    <input type="password" name="confSenha" id="confSenha"  class="form-control" maxlength="250" 
                        value="<?= setValor('senha') ?>" 
                        required placeholder="Confirme a senha"
                        onkeyup="checa_segur_senha('confSenha', 'msgConfSenha', 'btGravar');">
                    <div id="msgConfSenha" class="msgNivel_senha"></div>
                </div>

                <input type="hidden" name="id" value="<?= setValor('id') ?>">
                <input type="hidden" name="statusRegistro" id="statusRegistro" value="1">
                <input type="hidden" name="nivel" id="nivel" value="11">

                <div class="form-group col-12 mt-5">
                    <a href="<?= baseUrl() ?>/Home/Login" class="btn btn-outline-secondary btn-sm ml-3">Voltar</a>
                    <?php if ($this->getAcao() != "view"): ?>
                        <button type="submit" value="submit" id="btGravar" class="btn btn-primary btn-sm">Gravar</button>
                    <?php endif; ?>
                </div>

            </div>

        </form>

    </section>
    
</main>