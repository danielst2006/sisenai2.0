<?php 

session_start();

// echo $_SESSION['login'];

if(!isset($_SESSION['login'])){
    header("location: ../form/login.php");
    exit();
}

echo "Olá, ".$_SESSION['login'];
echo $_SESSION['tipo_usuario'];



?>