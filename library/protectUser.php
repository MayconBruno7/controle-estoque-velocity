<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION['userId'])) {
        header("Location: viewLogin.php?msgError=É necessário estar logado para acessar essa página.");
    }