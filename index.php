<?php 




session_start();


if (isset($_SESSION['login'])) {
    // Armazena a URL original para redirecionar após o login
   
}else{

    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: ../sisenai2.0/head/menuIndex.php');
    exit();

}


if($_SESSION['tipo_usuario'] == "ADM" || $_SESSION['tipo_usuario'] == "COPED") {

    include_once "head/menu.php";

} elseif (($_SESSION['tipo_usuario'] == "GESTOR")){

    include_once "head/menu.php";

} else {

    include_once "head/menu.php";
}







?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENAI - Qualificação Profissional</title>
    <style>
        .image-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .btn-painel {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="image-container">
    <img src="http://www.sistemafieto.com.br/gestor/Repositorio/CRPSistemaCmsBannerBanner/638505808837587450.png" alt="SENAI - A Hora é Essa">
    <a href="../sisenai2.0/menu/show_images.php" class="btn btn-primary btn-painel">Ir para o Painel</a>
</div>

</body>
</html>