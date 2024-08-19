<?php 

include_once '../bd/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];
    $id = isset($_POST['idFerias']) ? intval($_POST['idFerias']) : null;
    $data_inicio = $_POST['data_inicio'];
    $data_final = $_POST['data_final'];
    $tipoFerias_id = intval($_POST['tipoFerias_id']);

    if ($action == 'add') {
        $sql = "INSERT INTO ferias (data_inicio, data_final, tipoFerias_id) VALUES ('$data_inicio', '$data_final', $tipoFerias_id)";
    } elseif ($action == 'update') {
        $sql = "UPDATE ferias SET data_inicio='$data_inicio', data_final='$data_final', tipoFerias_id=$tipoFerias_id WHERE idFerias=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM ferias WHERE idFerias=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formFerias.php?status=success');
    } else {
        header('Location: ../form/formFerias.php?status=error');
    }
    exit();
}

?>

