<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['id_tipoferias']) ? $_POST['id_tipoferias'] : null;
    $tipo = $_POST['tipo'];

    if ($action == 'add') {
        $sql = "INSERT INTO tipo_ferias (tipo) VALUES ('$tipo')";
    } elseif ($action == 'update') {
        $sql = "UPDATE tipo_ferias SET tipo='$tipo' WHERE id_tipoferias=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM tipo_ferias WHERE id_tipoferias=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formTipoFerias.php?status=success');
    } else {
        header('Location: ../form/formTipoFerias.php?status=error');
    }
    exit();
}

mysqli_close($conn)

?>
