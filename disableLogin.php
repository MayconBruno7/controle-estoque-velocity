<?php

    try {

        $conn = new PDO(
            "mysql:host=localhost;dbname=controle_estoque",
            "root",
            "",

            // define qual codificação o banco de dados irá utilizar
            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
        );

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }

    session_start();

    // verifica se está sendo recebido alguma informação do formulário pelo metodo POSR
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $usuario    = $_POST["nome"];
        $senha      = $_POST["senha"];

        // Consulta ao banco de dados para verificar as credenciais
        $query = "SELECT * FROM usuarios WHERE nome = :nome AND senha = :senha";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":nome", $usuario);
        $stmt->bindParam(":senha", $senha);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Login bem-sucedido, redirecione para a página de boas-vindas ou outra página protegida.
            $_SESSION["nome"] = $resultado["nome"];
            header("Location: details.php");
            exit;

        } else {
            return header("Location: index.php?msgError=Usuário ou senha incorretos.");
        }
    }

    // carrega o cabecalho
    require_once "comuns/cabecalho.php";
?>

    <title> Login </title>

    <main>

        <h3 class="d-flex justify-content-center mt-5"> Formulário de login </h3>

        <!-- formulário de login para acesso a página -->
        <form action="#" method="POST">
            <!-- define uma margin-top: 3px; e diz que esse container vai ocupar dois pixels em relação ao tamanho da página -->
            <div class="container mt-3 col-2" id="login">
                <div class="row">
                    <div class="col-12">
                        <label for="nome" class="form-control-label"> Nome: </label>
                        <input type="text" class="form-control" name="nome" id="nome" required autofocus>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="senha" class="form-control-label"> Senha: </label>
                        <input type="password" class="form-control" name="senha" id="senha" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-6">
                        <button class="btn btn-primary"> Entrar </button>
                    </div>

                    <div class="col-6">
                        <p><a href="cadastro.php" class="btn btn-primary"> Cadastrar </a></p>
                    </div>
                </div>

                <p class="mt-3"><a href="#"> Esqueceu a senha? </a></p>

                <div class="row">
                    <div class="col-12">
                        <?php if (isset($_GET['msgSucesso'])): ?> <!-- Verifica se existe alguma msgSucesso -->

                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong><?= $_GET['msgSucesso'] ?></strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                        <?php endif; ?>

                        <?php if (isset($_GET['msgError'])): ?> <!-- Verifica se existe alguma msgError -->

                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><?= $_GET['msgError'] ?></strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                        <?php endif; ?> <!-- Finaliza o php if -->
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- inicio do rodapé -->
    <footer class="form">
        <p>Departamento de Informática Rosário da Limeira - MG</p>
        <span>© 2023 Company, Inc</span>
    </footer>
</body>
</html>

