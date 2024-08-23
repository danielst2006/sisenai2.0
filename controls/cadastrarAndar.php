<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['id_andar']) ? $_POST['id_andar'] : null;
    $nome = $_POST['nome'];

    if ($action == 'add') {
        $sql = "INSERT INTO andar (nome) VALUES ('$nome')";
    } elseif ($action == 'update') {
        $sql = "UPDATE andar SET nome='$nome' WHERE id_andar=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM andar WHERE id_andar=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formAndar.php?status=success');
    } else {
        header('Location: ../form/formAndar.php?status=error');
    }
    exit();
}

mysqli_close($conn)

?>
