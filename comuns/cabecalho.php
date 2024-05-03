<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styleEstoque.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.3.1.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    
    <style>

        .form {
            background-color: #fff;
            display: block;
            padding: 1rem;
            width: 100%;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .form-title {
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-weight: 600;
            text-align: center;
            color: #000;
        }

        .input-container {
            position: relative;
        }

        .input-container input, .form button {
            outline: none;
            border: 1px solid #e5e7eb;
            margin: 8px 0;
        }

        .input-container input {
            background-color: #fff;
            padding: 1rem;
            padding-right: 3rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            width: 100%;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .input-container span {
            display: grid;
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            padding-left: 1rem;
            padding-right: 1rem;
            place-content: center;
        }

        .input-container span svg {
            color: #9CA3AF;
            width: 100%;
            height: 1rem;
            margin-right: 15px;
        }

        .submit {
            display: block;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
            background-color: rgb(189, 188, 192);
            color: #ffffff;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            width: 100%;
            border-radius: 0.5rem;
            text-transform: uppercase;
        }

        .signup-link {
            color: #6B7280;
            font-size: 0.875rem;
            line-height: 1.25rem;
            text-align: center;
        }

        .signup-link a {
            text-decoration: underline;
        }

    </style>
    
</head>
<body>

    <div id="itens-nav">
       
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
        
            <div class="container-fluid">
                <a href="listaItens.php"><img src="img/brasao-pmrl.png" width= 95px; height= 85; alt="Brasão Prefeitura de Rosário da Limeira"></a>
                <div>
                    <ul class="navbar-nav">
                        <?php if(isset($_SESSION["userId"])) : ?>
                        <li class="nav-item dropdown">
                            <?php if($_SESSION["userNivel"] == 1) : ?>
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $_SESSION["userNome"] ?>
                            </a>
                            <?php endif; ?>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="listaUsuario.php">Lista de usuários</a></li>
                                <li><a class="dropdown-item" href="listaProdutos.php?usuario=adm">Cadastrar produtos</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="listaProdutos.php?usuario=padrao">Estoque</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="listaFornecedor.php">Fornecedores</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="listaMovimentacoes.php">Movimentações</a>
                        </li>
                    
                        <li class="nav-item">
                            <a class="nav-link" href="listaFuncionarios.php">Funcionários</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="listaSetor.php">Setores</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="listaCargo.php">Cargo</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-danger" aria-current="page" href="logoff.php">Sair</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
