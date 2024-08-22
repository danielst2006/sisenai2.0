<?php 

session_start();

if (!isset($_SESSION['login'])) {
    // Armazena a URL original para redirecionar após o login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: /sisenai2.0/form/login.php');
    exit();
}




?>