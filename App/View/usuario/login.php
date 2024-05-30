<?php

use App\Library\Formulario;

?>

<!-- <section>
    <div class="container">
        <div class="blog-banner">
            <div class="mt-5 mb-5 text-left">
                <h1 style="color: #384aeb;">Login / Criar nova conta</h1>
            </div>
        </div>
    </div>
</section> -->

<!-- <div class="col-lg-6">
                <div class="login_box_img">
                    <div class="hover">
                        <h4>Novo em nosso site?</h4>
                        <p>
                            Crie uma conta para poder curtir, comentar, marcar como lido nossos conteúdos criados para você.
                        </p>
                        <a class="button button-account" href="<?= baseUrl() ?>Home/criarConta">Crie sua conta aqui</a>
                    </div>
                </div>
            </div> -->

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
                        
                        <div class="col-12 form-group mt-1 mb-3">
                            <button type="submit" value="submit" class="btn btn-outline-secondary">Entrar</button>
                            <a href="<?= baseUrl() ?>Login/solicitaRecuperacaoSenha">Esqueceu a senha?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>