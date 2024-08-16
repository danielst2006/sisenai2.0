<?php 

   $servername = "40.233.68.103";
   $username = "alvaro";
   $password = "123";
   $dbname = "sisenai";

   $conn = mysqli_connect($servername, $username, $password, $dbname);
   if(!$conn) die("Falha na Conexão: " . mysqli_connect_error());


?>
<?