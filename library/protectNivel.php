<?php 

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION["userNivel"]) || $_SESSION["userNivel"] != 1) {
        header("Location: index.php?msgError=Para acessar essa página é necessário ser um administrador.");
        exit; // Certifique-se de sair após o redirecionamento
    }
