<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['idDisponibilidade']) ? $_POST['idDisponibilidade'] : null;
    $sala_id = $_POST['sala_id'];
    $data_inicio = $_POST['data_inicio'];
    $data_final = $_POST['data_final'];

    if ($action == 'add') {
        $sql = "INSERT INTO disponibilidade_salas (sala_id, data_inicio, data_final) 
                VALUES ('$sala_id', '$data_inicio', '$data_final')";
    } elseif ($action == 'update') {
        $sql = "UPDATE disponibilidade_salas 
                SET sala_id='$sala_id', data_inicio='$data_inicio', data_final='$data_final' 
                WHERE idDisponibilidade=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM disponibilidade_salas WHERE idDisponibilidade=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formDisponibilidadeSalas.php?status=success');
    } else {
        header('Location: ../form/formDisponibilidadeSalas.php?status=error');
    }
    exit();
}

?>

