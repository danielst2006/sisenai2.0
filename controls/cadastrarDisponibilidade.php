<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['idDisponibilidade']) ? $_POST['idDisponibilidade'] : null;
    $sala_id = $_POST['sala_id'];
    $data_inicio = $_POST['data_inicio'];
    $horario_inicio = $_POST['horario_inicio'];
    $data_final = $_POST['data_final'];
    $horario_fim = $_POST['horario_fim'];

    if ($action == 'add') {
        $sql = "INSERT INTO disponibilidade_salas (sala_id, data_inicio, horario_inicio, data_final, horario_fim) 
                VALUES ('$sala_id', '$data_inicio', '$horario_inicio', '$data_final', '$horario_fim')";
    } elseif ($action == 'edit') {
        $sql = "UPDATE disponibilidade_salas 
                SET sala_id='$sala_id', data_inicio='$data_inicio', horario_inicio='$horario_inicio', 
                    data_final='$data_final', horario_fim='$horario_fim' 
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

mysqli_close($conn);

?>
