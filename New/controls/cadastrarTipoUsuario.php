<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['idTipo_usuario']) ? $_POST['idTipo_usuario'] : null;
    $tipo = $_POST['tipo'];

    if ($action == 'add') {
        $sql = "INSERT INTO tipo_usuario (tipo) VALUES ('$tipo')";
    } elseif ($action == 'update') {
        $sql = "UPDATE tipo_usuario SET tipo='$tipo' WHERE idTipo_usuario=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM tipo_usuario WHERE idTipo_usuario=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    
    if ($resultado) {
        header('Location: ../form/formTipoUsuario.php?status=success');
    } else {
        header('Location: ../form/formTipoUsuario.php?status=error');
    }
    exit();
}

?>
