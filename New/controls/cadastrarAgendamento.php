<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['idAgendamento']) ? $_POST['idAgendamento'] : null;
    $data_inicio = $_POST['data_inicio'];
    $data_final = $_POST['data_final'];
    $usuario_idUsuario = $_POST['usuario_idUsuario'];
    $unidade_curricular_id = $_POST['unidade_curricular_id'];
    $turma_id = $_POST['turma_id'];
    $sala_id = $_POST['sala_id'];
    $professor_id = $_POST['professor_id'];

    if ($action == 'add') {
        $sql = "INSERT INTO agendamento (data_inicio, data_final, usuario_idUsuario, unidade_curricular_id, turma_id, sala_id, professor_id) 
                VALUES ('$data_inicio', '$data_final', $usuario_idUsuario, $unidade_curricular_id, $turma_id, $sala_id, $professor_id)";
    } elseif ($action == 'update') {
        $sql = "UPDATE agendamento 
                SET data_inicio='$data_inicio', data_final='$data_final', usuario_idUsuario=$usuario_idUsuario, unidade_curricular_id=$unidade_curricular_id, turma_id=$turma_id, sala_id=$sala_id, professor_id=$professor_id 
                WHERE idAgendamento=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM agendamento WHERE idAgendamento=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formAgendamentos.php?status=success');
    } else {
        header('Location: ../form/formAgendamentos.php?status=error');
    }
    exit();
}

?>
