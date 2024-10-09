<?= $this->extend('layout/layout_default') ?>

<?= $this->section('conteudo') ?>

<section>
    <div class="container">
        <div class="blog-banner">
            <div class="mt-5 mb-5 text-left">
                <h1 style="color: #384aeb;">Login / Criar nova conta</h1>
            </div>
        </div>
    </div>
</section>

<section class="login_box_area section-margin">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="login_box_img">
                    <div class="hover">
                        <h4>Novo em nosso site?</h4>
                        <p>
                            Crie uma conta para poder curtir, comentar, marcar como lido nossos conteúdos criados para você.
                        </p>
                        <a class="button button-account" href="criarNovaConta">Crie sua conta aqui</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="login_form_inner">
                    <h3>Entre com seu Login</h3>
                    <form class="row login_form" action="#/" id="contactForm">
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="E-mail" onfocus="this.placeholder = ''" onblur="this.placeholder = 'E-mail'">
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Senha" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Senha'">
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="creat_account">
                                <input type="checkbox" id="f-option2" name="selector">
                                <label for="f-option2">Mantenha-me conectado</label>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <button type="submit" value="submit" class="button button-login w-100">Entrar</button>
                            <a href="#">Esqueceu a senha?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>