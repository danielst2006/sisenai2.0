<?php
include_once '../bd/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $id = isset($_POST['id_agenda']) ? $_POST['id_agenda'] : null;
    $data_inicio = $_POST['data_inicio'];
    $data_final = $_POST['data_final'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'pendente';

    if ($action == 'add') {
        $sql = "INSERT INTO agenda (data_inicio, data_final, status) VALUES ('$data_inicio', '$data_final', '$status')";
    } elseif ($action == 'update') {
        $sql = "UPDATE agenda SET data_inicio='$data_inicio', data_final='$data_final', status='$status' WHERE id_agenda=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM agenda WHERE id_agenda=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formAgenda.php?status=success');
    } else {
        header('Location: ../form/formAgenda.php?status=error');
    }
    exit();
}

mysqli_close($conn);
?>
