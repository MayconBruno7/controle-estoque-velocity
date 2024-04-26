<?php
    require_once "comuns/cabecalho.php";
    require_once "helpers/Formulario.php";
    ?>

    <main class="container mt-5">

        <div class="row">
            <div class="col-12">
                <h2>Fale Conosco</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php if (isset($_GET['msgSucesso'])): ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><?= $_GET['msgSucesso'] ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>

                <?php if (isset($_GET['msgError'])): ?>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?= $_GET['msgError'] ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>
            </div>
        </div>

        <form class="g-3" action="faleConoscoEnvio.php" method="POST">

            <div class="row">

                <div class="col-12 mt-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome"
                        placeholder="Seu nome" required autofocus>
                </div>

                <div class="col-9 mt-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="text" class="form-control" name="email" id="email" 
                        placeholder="Seu e-mail" required>
                </div>

                <div class="col-3 mt-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" name="telefone" id="telefone" 
                        placeholder="Seu telefone" required>
                </div>

                <div class="col-12 mt-3">
                    <label for="assunto" class="form-label">Assunto</label>
                    <input type="text" class="form-control" name="assunto" id="assunto" 
                        placeholder="Assunto a ser tratado" required>
                </div>
                <div class="col-12 mt-3">
                    <label for="mensagem" class="form-label">Mensagem</label>
                    <textarea class="form-control" rows="10" name="mensagem" id="mensagem" 
                        placeholder="Descreva mais sobre o assunto que deseja tratar conosoco."></textarea>
                </div>
            </div>

            <div class="col-auto mt-5">
                <button type="submit" class="btn btn-primary btn-sm">Enviar</button>
            </div>
        </form>

    </main>

    <script src="assets/ckeditor5/ckeditor5-build-classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#mensagem'))
            .catch( error => {
                console.error(error);
            });
    </script>

    <?php

    require_once "comuns/rodape.php";