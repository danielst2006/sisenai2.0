<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['turma_id']) ? $_POST['turma_id'] : null;
    $nome_turma = $_POST['nome_turma'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_final = $_POST['horario_final'];
    $dias_aula = $_POST['dias_aula'];
    $status = $_POST['status'];
    $curso_id = $_POST['curso_id'];

    if ($action == 'add') {
        $sql = "INSERT INTO turmas (nome_turma, data_inicio, data_fim, horario_inicio, horario_final, dias_aula, status, curso_id) 
                VALUES ('$nome_turma', '$data_inicio', '$data_fim', '$horario_inicio', '$horario_final', '$dias_aula', '$status', $curso_id)";
    } elseif ($action == 'update') {
        $sql = "UPDATE turmas 
                SET nome_turma='$nome_turma', data_inicio='$data_inicio', data_fim='$data_fim', horario_inicio='$horario_inicio', horario_final='$horario_final', dias_aula='$dias_aula', status='$status', curso_id=$curso_id 
                WHERE turma_id=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM turmas WHERE turma_id=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formTurmas.php?status=success');
    } else {
        header('Location: ../form/formTurmas.php?status=error');
    }
    exit();
}

?>
