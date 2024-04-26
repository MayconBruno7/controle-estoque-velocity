<?php

    session_start();

    unset($_SESSION["userId"]);
    unset($_SESSION["userNome"]);
    unset($_SESSION["userEmail"]);
    unset($_SESSION["userNivel"]);
    unset($_SESSION["userSenha"]);

    return header("Location: index.php");