<?php

use App\Library\Formulario;
use App\Library\Session;

?>

<main>
    <section class="card">
        <header class="card-header">
            <h2>Bem-vindo ao Sistema de Controle de Estoque</h2>
        </header>
        <div class="card-body">
            <h4>Sobre nós</h4>
            <p>
                Somos uma empresa especializada no desenvolvimento de sistemas de controle de estoque, comprometida em transformar a gestão de inventários de empresas de todos os portes e setores. Nossa missão é oferecer soluções tecnológicas inovadoras que aumentem a eficiência operacional, reduzam custos e melhorem a tomada de decisões estratégicas.<br>
            </p>

            <h4>Contato: </h4>
            <p>
            Telefone: <br>
            Email: <br>
            </p>

            <h4>Suporte e Treinamento</h4>
            <p>
                Oferecemos treinamento completo e suporte contínuo para garantir que você aproveite ao máximo todas as funcionalidades do sistema.
            </p>
            
        </div>
    </section>
</main>

<style>

    main {
        padding: 20px;
    }

    /* Estilo do Card */
    .card {
        background: #fff;
        margin: 20px auto;
        max-width: 800px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .card-header {
        background: #004080;
        color: #fff;
        padding: 20px;
        text-align: center;
    }

    .card-body {
        padding: 20px;
    }

    .card-body p {
        margin: 10px 0;
        text-align: justify;
    }

</style>