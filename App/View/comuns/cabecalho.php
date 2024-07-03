<?php 

use App\Library\Session;
use App\Library\Formulario;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= baseUrl() ?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link rel="icon" href="<?= baseUrl() ?>assets/img/brasao-pmrl-icon.jpeg" type="image/jpeg">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="<?= baseUrl() ?>assets/js/jquery-3.3.1.js"></script>
    <script src="<?= baseUrl() ?>assets/bootstrap/js/bootstrap.min.js"></script>
    
</head>
<body>

    <div id="itens-nav">
       
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
        
            <div class="container-fluid">
            <a href="<?= Session::get('usuarioId') ? baseUrl() . Formulario::retornaHomeAdminOuHome() : '#' ?>">
                <img src="<?= baseUrl() ?>assets/img/brasao-pmrl.png" width="95" height="85" alt="Brasão Prefeitura de Rosário da Limeira">
            </a>
                <div>
                    <ul class="navbar-nav">
                        <?php if (Session::get('usuarioId') != false): ?>
                        <li class="dropdown">
                            <?php if (Session::get('usuarioNivel') == 1): ?>
                            <a class="nav-link dropdown-toggle" href="<?= baseUrl() ?>#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $_SESSION["usuarioLogin"] ?>
                            </a>
                            <?php endif; ?>
                            <ul class="dropdown-menu text-danger">
                                <li><a class="dropdown-item" href="<?= baseUrl() ?>Usuario">Lista de usuários</a></li>
                                <li><a class="dropdown-item" href="<?= baseUrl() ?>Funcionario">Lista funcionários</a></li>
                                <li ><a class="dropdown-item" href="<?= baseUrl() ?>Cargo">Lista cargos</a></li>
                                <li><a class="dropdown-item" href="<?= baseUrl() ?>Relatorio">Relatórios</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <li><a class="nav-link text-secondary" href="<?= baseUrl() ?>Produto">Estoque</a></li>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="<?= baseUrl() ?>Setor">Setores</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="<?= baseUrl() ?>Fornecedor">Fornecedores</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="<?= baseUrl() ?>Movimentacao">Movimentações</a>
                        </li>

                        <li class="nav-item">
                            <li><a class="nav-link text-secondary" href="<?= baseUrl() ?>FaleConosco/formularioEmail">Suporte técnico</a></li>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-danger" href="<?= baseUrl() ?>Login/signOut">Sair</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
