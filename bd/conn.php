<?php 

   // $servername = "40.233.68.103";
   // $username = "alvaro";
   // $password = "123";
   // $dbname = "sisenai";

   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "sisenai2";

   $conn = mysqli_connect($servername, $username, $password, $dbname);
   if(!$conn) die("Falha na Conexão: " . mysqli_connect_error());


?>
<?