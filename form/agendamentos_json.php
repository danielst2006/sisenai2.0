<?php
include_once "../bd/conn.php";

$sql = "SELECT a.idAgendamento AS id, 
               a.data_inicio AS start, 
               a.data_final AS end, 
               u.nome_usuario AS title
        FROM agendamento a 
        JOIN usuarios u ON a.usuario_idUsuario = u.idUsuario 
        WHERE u.nome_usuario LIKE CONCAT('%', '$pesquisa', '%') 
           OR a.data_inicio LIKE CONCAT('%', '$pesquisa', '%') 
           OR a.data_final LIKE CONCAT('%', '$pesquisa', '%')";

$resultado = mysqli_query($conn, $sql);

$events = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $events[] = $row;
}

echo json_encode($events);
?>
