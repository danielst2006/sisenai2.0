<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['idunidade_curricular']) ? $_POST['idunidade_curricular'] : null;
    $nome_unidade = $_POST['nome_unidade'];
    $carga_horaria = $_POST['carga_horaria'];
    $curso_id = $_POST['curso_id'];

    if ($action == 'add') {
        $sql = "INSERT INTO unidade_curricular (nome_unidade, carga_horaria, curso_id) VALUES ('$nome_unidade', '$carga_horaria', '$curso_id')";
    } elseif ($action == 'update') {
        $sql = "UPDATE unidade_curricular SET nome_unidade='$nome_unidade', carga_horaria='$carga_horaria', curso_id='$curso_id' WHERE idunidade_curricular=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM unidade_curricular WHERE idunidade_curricular=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formUnidadeCurricular.php?status=success');
    } else {
        header('Location: ../form/formUnidadeCurricular.php?status=error');
    }
    exit();
}

mysqli_close($conn)

?>
