<?php

use App\Library\Formulario;

?>

<section class="about section-margin">

    <div class="container mt-5">
        <div class="row justify-content-center">

            <div class="col-6">
                <div class="login_form_inner">
                    <h3>Entre com seu Login</h3>

                    <form method="POST" class="row login_form" action="<?= baseUrl() ?>Login/signIn" id="contactForm">

                        <div class="col-md-12 form-group mt-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" onfocus="this.placeholder = ''" onblur="this.placeholder = 'E-mail'" required autofocus>
                        </div>
                        <div class="col-md-12 form-group mt-3">
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Senha'" required>
                        </div>

                        <!--
                        <div class="col-md-12 form-group mt-3">
                            <div class="creat_account">
                                <input type="checkbox" id="f-option2" name="selector">
                                <label for="f-option2">Mantenha-me conectado</label>
                            </div>
                        </div>
                        -->

                        <div class="col-12 mt-3">
                            <?= Formulario::exibeMsgError() ?>
                        </div>

                        <div class="col-12 mt-3">
                            <?= Formulario::exibeMsgSucesso() ?>
                        </div>
                        
                        <div class="container col-12 mt-1 mb-3">
                            <button type="submit" value="submit" class="btn btn-primary">Entrar</button>
                            <a class="btn btn-outline-secondary" href="<?= baseUrl() ?>Home/criarConta">Crie sua conta aqui</a>

                            <a href="<?= baseUrl() ?>Login/solicitaRecuperacaoSenha">Esqueceu a senha?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>