<?php 

    use App\Library\Session;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= baseUrl() ?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/styleEstoque.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link rel="icon" href="<?= baseUrl() ?>assets/img/brasao-pmrl-icon.jpeg" type="image/jpeg">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="<?= baseUrl() ?>assets/js/jquery-3.3.1.js"></script>
    <script src="<?= baseUrl() ?>assets/bootstrap/js/bootstrap.min.js"></script>
    
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
                <a href="<?= baseUrl() ?>listaProdutos.php"><img src="<?= baseUrl() ?>assets/img/brasao-pmrl.png" width= 95px; height= 85; alt="Brasão Prefeitura de Rosário da Limeira"></a>
                <div>
                    <ul class="navbar-nav">
                        <?php if (Session::get('usuarioId') != false): ?>
                        <li class="nav-item dropdown">
                            <?php if (Session::get('usuarioNivel') == 1): ?>
                            <a class="nav-link dropdown-toggle" href="<?= baseUrl() ?>#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $_SESSION["usuarioLogin"] ?>
                            </a>
                            <?php endif; ?>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= baseUrl() ?>listaUsuario.php">Lista de usuários</a></li>
                                <li><a class="dropdown-item" href="<?= baseUrl() ?>listaFuncionarios.php">Lista funcionários</a></li>
                                <li ><a class="dropdown-item"" href="<?= baseUrl() ?>listaCargo.php">Lista cargos</a></li>
                                <li><a class="dropdown-item" href="<?= baseUrl() ?>listaProdutos.php">Cadastrar produtos</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>listaProdutos.php">Estoque</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>listaFornecedor.php">Fornecedores</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>listaMovimentacoes.php">Movimentações</a>
                        </li>
                    
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>listaSetor.php">Setores</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>Login/signOut">Sair</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
