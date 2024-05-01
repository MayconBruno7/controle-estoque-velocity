<?php 

    // carrega o cabecalho
    require_once "comuns/cabecalho.php";
?>

    <title> Cadastro </title>

    <!-- Inicio da parte do formulário -->
    <main>
        <h3 class="d-flex justify-content-center mt-5"> Formulário de cadastro de usuários </h3>

        <!-- Formulário que manda as informações adicionadas pelo usuário para a página insertCadastro.php -->
        <form action="insertCadastro.php" method="POST">

            <div class="container col-2 mt-3" id="login">

                <div class="row mt-3">
                    <div class="col-12">
                        <label for="nome" class="form-control-label mb-2"> Nome: </label>
                        <input type="nome" class="form-control" name="nome" id="nome" required autofocus>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="email" class="form-control-label mb-2"> E-mail: </label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>  
                </div>
                

                <div class="row">
                    <div class="col-12">
                        <label for="senha" class="form-control-label mb-2"> Senha: </label>
                        <input type="password" class="form-control" name="senha" id="senha" required>
                    </div>
                </div>

                <div class="row mt-4 mb-3">
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary"> Cadastrar </a>
                    </div>

                    <div class="col-6">
                        <a href="index.php" class="btn btn-primary"> Voltar </a>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <!-- Fim da parte do formulário -->

    <!-- Inicio do rodapé da página -->
    <footer class="form">
        <p>Departamento de Informática Rosário da Limeira - MG</p>
        <span>© 2024 Company, Inc</span>
    </footer>
</body>
</html>
