<?= $this->extend('layout/layout_default') ?>

<?= $this->section('conteudo') ?>

<section>
    <div class="container">
        <div class="blog-banner">
            <div class="mt-5 mb-5 text-left">
                <h1 style="color: #384aeb;">Contato</h1>
            </div>
        </div>
    </div>
</section>

<section class="section-margin--small">
    <div class="container">

        <div class="row d-none d-sm-block mb-5 pb-4">
            <div class="col-md-12">
                <img src="assets/img/mapa.png" class="img-fluid" alt="">
            </div>
        </div>


        <div class="row">

            <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
                <div class="media contact-info">
                <span class="contact-info__icon"><i class="ti-home"></i></span>
                <div class="media-body">
                    <h3>Muriaé-MG</h3>
                    <p>Praça Aninna Bisegna, 40 - Centro</p>
                </div>
                </div>
                <div class="media contact-info">
                <span class="contact-info__icon"><i class="ti-headphone"></i></span>
                <div class="media-body">
                    <h3><a href="tel:+553237211026">+55 (32) 3721-1026</a></h3>
                    <p>De segunda a sexta das 8 às 18 horas</p>
                </div>
                </div>
                <div class="media contact-info">
                <span class="contact-info__icon"><i class="ti-email"></i></span>
                <div class="media-body">
                    <h3><a href="mailto:aldecirfonseca@ghotmail.com">aldecirfonseca@hotmail.com</a></h3>
                    <p>Envie-nos uma mensagem a qualquer momento!</p>
                </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-9">
                <form action="#/" class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <input class="form-control" name="name" id="name" type="text" placeholder="Seu nome">
                        </div>
                        <div class="form-group">
                            <input class="form-control" name="celular" id="celular" type="text" placeholder="Seu telefone para contato">
                        </div>
                        <div class="form-group">
                            <input class="form-control" name="email" id="email" type="email" placeholder="Seu e-mail">
                        </div>
                        <div class="form-group">
                            <input class="form-control" name="assunto" id="assunto" type="text" placeholder="Resumo do assunto">
                        </div>

                        <div class="form-group">
                            <textarea class="form-control different-control w-100" name="message" id="message" cols="30" rows="5" placeholder="Descreva de forma detalha o motivo do seu contato"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center text-md-right mt-3">
                    <button type="submit" class="button button--active button-contactForm">Enviar Mensagem</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>