<?php

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['idAgendamento']) ? $_POST['idAgendamento'] : null;
    $data_inicio = $_POST['data_inicio'];
    $horario_inicio = $_POST['horario_inicio'];
    $data_final = $_POST['data_final'];
    $horario_fim = $_POST['horario_fim'];
    $usuario_idUsuario = $_POST['usuario_idUsuario'];
    $unidade_curricular_id = $_POST['unidade_curricular_id'];
    $turma_id = $_POST['turma_id'];
    $sala_id = $_POST['sala_id'];
    $professor_id = $_POST['professor_id'];
    $status = $_POST['status']; // Novo campo status

    echo "$id, $data_inicio, $horario_inicio, $data_final, $horario_fim, $usuario_idUsuario, $unidade_curricular_id, $turma_id, $sala_id, $professor_id, $status";
    
    // Processa o campo 'dias_aula' se ele existir
    $dias_aula = isset($_POST['dias_aula']) ? implode(',', $_POST['dias_aula']) : '';

    if ($action == 'add') {
        $sql = "INSERT INTO agendamento (data_inicio, horario_inicio, data_final, horario_fim, usuario_idUsuario, unidade_curricular_id, turma_id, sala_id, professor_id, dias_aula, status) 
                VALUES ('$data_inicio', '$horario_inicio', '$data_final', '$horario_fim', $usuario_idUsuario, $unidade_curricular_id, $turma_id, $sala_id, $professor_id, '$dias_aula', '$status')";
    } elseif ($action == 'update') {
        $sql = "UPDATE agendamento 
                SET data_inicio='$data_inicio', horario_inicio='$horario_inicio', data_final='$data_final', horario_fim='$horario_fim', usuario_idUsuario=$usuario_idUsuario, unidade_curricular_id=$unidade_curricular_id, turma_id=$turma_id, sala_id=$sala_id, professor_id=$professor_id, dias_aula='$dias_aula', status='$status' 
                WHERE idAgendamento=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM agendamento WHERE idAgendamento=$id";
    }

   

     $resultado = mysqli_query($conn, $sql);

   die   . mysqli_error($conn);

    // if ($resultado) {
    //     header('Location: ../form/formAgendamentos.php?status=success');
    // } else {
    //     header('Location: ../form/formAgendamentos.php?status=error');
    // }
    // exit();
}

mysqli_close($conn);

?>
