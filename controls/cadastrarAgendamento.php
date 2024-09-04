<?php

include_once '../bd/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];
    $id = isset($_POST['idAgendamento']) ? $_POST['idAgendamento'] : null;
    $data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : null;
    $horario_inicio = isset($_POST['horario_inicio']) ? $_POST['horario_inicio'] : null;
    $data_final = isset($_POST['data_final']) ? $_POST['data_final'] : null;
    $horario_fim = isset($_POST['horario_fim']) ? $_POST['horario_fim'] : null;
    $usuario_idUsuario = isset($_POST['usuario_idUsuario']) ? $_POST['usuario_idUsuario'] : null;
    $unidade_curricular_id = isset($_POST['unidade_curricular_id']) ? $_POST['unidade_curricular_id'] : null;
    $turma_id = isset($_POST['turma_id']) ? $_POST['turma_id'] : null;
    $sala_id = isset($_POST['sala_id']) ? $_POST['sala_id'] : null;
    $professor_id = isset($_POST['professor_id']) ? $_POST['professor_id'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null; // Campo status conforme a tabela
    
    // Processa o campo 'dias_aula' se ele existir
    $dias_aula = isset($_POST['dias_aula']) ? implode(',', $_POST['dias_aula']) : '';

    if ($action == 'add') {
        $sql = "INSERT INTO agendamento (data_inicio, horario_inicio, data_final, horario_fim, usuario_idUsuario, unidade_curricular_id, turma_id, sala_id, professor_id, dias_aula, status) 
                VALUES ('$data_inicio', '$horario_inicio', '$data_final', '$horario_fim', $usuario_idUsuario, $unidade_curricular_id, $turma_id, $sala_id, $professor_id, '$dias_aula', '$status')";
    } elseif ($action == 'update' && $id !== null) {
        // Se o ID estiver presente, atualiza o agendamento existente
        $sql = "UPDATE agendamento 
                SET status='$status' 
                WHERE idAgendamento=$id";
    } elseif ($action == 'delete' && $id !== null) {
        // Deleta o agendamento
        $sql = "DELETE FROM agendamento WHERE idAgendamento=$id";
    }

    // Executa a query e verifica o resultado
    if (isset($sql)) {
        $resultado = mysqli_query($conn, $sql);
        if ($resultado) {
            header('Location: ../form/formAgendamentos.php?status=success');
        } else {
            header('Location: ../form/formAgendamentos.php?status=error');
        }
    }

    exit();
}

mysqli_close($conn);

?>
