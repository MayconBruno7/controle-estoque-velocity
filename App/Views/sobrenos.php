<?= $this->extend('layout/layout_default') ?>

<?= $this->section('conteudo') ?>

<section>
    <div class="container">
        <div class="blog-banner">
            <div class="mt-5 mb-5 text-left">
                <h1 style="color: #384aeb;">Sobre nós</h1>
            </div>
        </div>
    </div>
</section>

<main class="site-main">

    <section class="blog_area">
        <div class="container">
            <div class="row">

                <div class="col-12">
                    <div class="blog_left_sidebar">

                        <article class="row mt-5">
                            <p class="col-12 text-center">
                                <img class="author_img" src="assets/img/centro-de-distribuicao.jpg" alt="">
                            </p>
                            
                            <h4 class="col-12 text-center">Muriáe Shop</h4>
                            <p class="col-12 text-center">Maior distribuidor da Zona da Matta Mineira</p>
                            <p class="col-12 text-center social_icon" style="font-size: 24px;">
                                <a href="#" tile="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>

                            </p>
                            <p class="col-12">
                                Nosso fundador uma pessoa humilde do interior mineiro, mas um sonhardor, 
                                um visionário que sempre sonhou em criar algo inovador, que pududesse
                                tranformar a vida das pessoas e antigir o maior público possível. 
                            </p>
                            <p class="col-12">
                                Dai nasce a Muriaé Shop uma empresa que visa revolucionar o mercado 
                                de vendas online dando a todos uma experiência única em compras 
                                virtuais.
                            </p>

                            <div class="br"></div>

                        </article>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<?= $this->endSection() ?>