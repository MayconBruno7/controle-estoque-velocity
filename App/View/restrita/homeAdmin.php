<?php

use App\Library\Formulario;
use App\Library\Session;

?>

<main>
    <section class="card">
        <header class="card-header">
            <h2>Bem-vindo ao sistema controle de estoque</h2>
            <p>Usuário: Administrador</p>
        </header>
        <div class="card-body">
            <p>Somos uma empresa especializada no desenvolvimento de sistemas de controle de estoque, comprometida em transformar a gestão de inventários de empresas de todos os portes e setores. Nossa missão é oferecer soluções tecnológicas inovadoras que aumentem a eficiência operacional, reduzam custos e melhorem a tomada de decisões estratégicas.</p>

            <h3>Contato:</h3>
            <p>
                Telefone: (32) 988854681<br>
                Email: sistemacontroleestoque@gmail.com
            </p>

            <h3>Suporte e Treinamento</h3>
            <p>Oferecemos treinamento completo e suporte contínuo para garantir que você aproveite ao máximo todas as funcionalidades do sistema.</p>
            
        </div>
    </section>
</main>

<style>

    main {
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* Estilo do Card */
    .card {
        background: #f5f5f5;
        margin: auto;
        max-width: 600px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background: #004080;
        color: #fff;
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card-body {
        padding: 30px;
    }

    .card-body p {
        margin-bottom: 20px;
        line-height: 1.6;
        text-align: justify;
    }

    .card-body h3 {
        color: #004080;
        margin-bottom: 10px;
    }

</style>
