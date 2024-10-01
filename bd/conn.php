<?php 

   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "sisenai3";

   // $servername = "localhost";
   // $username = "root";
   // $password = "";
   // $dbname = "sisenai2";

   $conn = mysqli_connect($servername, $username, $password, $dbname);
   if(!$conn) die("Falha na Conexão: " . mysqli_connect_error());


?>
<?