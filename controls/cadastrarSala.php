<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['id_sala']) ? $_POST['id_sala'] : null;
    $nome = $_POST['nome'];
    $andar_id = $_POST['andar_id'];

    if ($action == 'add') {
        $sql = "INSERT INTO salas (nome, andar_id) VALUES ('$nome', $andar_id)";
    } elseif ($action == 'update') {
        $sql = "UPDATE salas SET nome='$nome', andar_id=$andar_id WHERE id_sala=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM salas WHERE id_sala=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formSalas.php?status=success');
    } else {
        header('Location: ../form/formSalas.php?status=error');
    }
    exit();
}

?>
