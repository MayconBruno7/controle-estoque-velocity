<?php
    
    require_once "library/Database.php";
        
    try {
        
        // Criando o objeto Db para classe de base de dados
        $db = new Database();

        // preparação da query que será executada no banco de dados
        $data = $db->dbSelect("SELECT * FROM itens ORDER BY id");

    // Se houver algum erro de conexão com o banco de dados será disparado pelo bloco catch
    } catch (Exception $ex) {
        echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    }

    // carrega o cabecalho
    require_once "comuns/cabecalho.php";
    require_once "library/protectUser.php";
?>

    <title>Controle de estoque</title>

    <div class="container" id="texto-index-body">
        <h3> -------- </h3>

        <br>

        <p> 
        --------
        </p>

        <br>    <br>

        <!-- card principal -->
        <div class="card text-center">
            <div class="card-header">
                Projeto
            </div>

            <div class="card-body">
                <h5 class="card-title">Data de inicio</h5>
                <p class="card-text">31/08/2023</p>
                <a href="noteProject.php" class="btn btn-primary">Documentação do projeto</a>
            </div>

            <div class="card-footer text-body-secondary">
                ...
            </div>
        </div>
    </div>

    <div class="mt-5">
        <?php
            require_once "comuns/rodape.php";
        ?>
    </div>