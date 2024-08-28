<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISENAI 2.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/sisenai2.0/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
  <header>
    <div class="navbar navbar-expand-md" style="background-color: rgb(218 224 233);">
      <!-- <button class="openbtn" style="background-color: rgb(218 224 233);" onclick="openNav()">☰</button> -->
      <button class="openbtn-profile ms-auto" style="background-color: rgb(218 224 233);" onclick="openProfileNav()">
        <img src="/sisenai2.0/imagens/senai.png" alt="Logo" class="logo">
        <i class="fas fa-user"></i>
      </button>
    </div>

    <!-- <div id="mySidebar" class="sidebar" style="background-color: rgb(231, 78, 22);">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
      <a href="index.php">Inicio</a>
       <a href="../sisenai2.0/form/login.php">Sair</a>

    </div> -->
    
    
    <!-- <div id="profileSidebar" class="sidebar-profile" style="background-color: rgb(22, 65, 147);">
      <a href="javascript:void(0)" class="closebtn-profile" onclick="closeProfileNav()">×</a>
      <a href="perfil.php">Perfil</a>
       <a href="../controls/logout.php">Sair</a>
    </div> -->
    

  <!-- Conteúdo da página aqui -->

  <!-- <footer class="footer text-center text-lg-start bg-dark text-white">
    <div class="text-center p-3">
        © Copyright 2024 - TechFix
    </div>
  </footer> -->

  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
      function openNav() {
          document.getElementById("mySidebar").style.width = "195px";
      }

      function closeNav() {
          document.getElementById("mySidebar").style.width = "0";
      }

      function openProfileNav() {
          document.getElementById("profileSidebar").style.width = "195px";
      }

      function closeProfileNav() {
          document.getElementById("profileSidebar").style.width = "0";
      }
  </script>
