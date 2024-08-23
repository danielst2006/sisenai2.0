<?php 

include_once '../bd/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];
    $curso_id = isset($_POST['curso_id']) ? $_POST['curso_id'] : null;
    $nome_curso = $_POST['nome_curso'];
    $area_tecnologica = $_POST['area_tecnologica'];
    $ano = $_POST['ano'];

    if ($action == 'add') {
        $sql = "INSERT INTO cursos (nome_curso, area_tecnologica, ano) VALUES ('$nome_curso', '$area_tecnologica', '$ano')";
    } elseif ($action == 'update') {
        $sql = "UPDATE cursos SET nome_curso='$nome_curso', area_tecnologica='$area_tecnologica', ano='$ano' WHERE curso_id=$curso_id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM cursos WHERE curso_id=$curso_id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formCurso.php?status=success');
    } else {
        header('Location: ../form/formCurso.php?status=error');
    }
    exit();
}

mysqli_close($conn)

?>
