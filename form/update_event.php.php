<?php
include('../bd/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['start'], $_POST['end'])) {
        // Exibe os dados recebidos para verificação
        echo "Recebido ID: " . $_POST['id'] . " Start: " . $_POST['start'] . " End: " . $_POST['end'] . "\n";

        $id = $_POST['id'];
        $start = $_POST['start'];
        $end = $_POST['end'];

        // Extrai a data e hora do novo início e fim
        $startDate = substr($start, 0, 10);
        $startTime = substr($start, 11, 8);
        $endDate = substr($end, 0, 10);
        $endTime = substr($end, 11, 8);

        echo "Início: " . $startDate . " " . $startTime . " - Fim: " . $endDate . " " . $endTime . "\n";

        // Atualiza o agendamento no banco de dados
        $sql = "UPDATE agendamento 
                SET data_inicio = ?, horario_inicio = ?, data_final = ?, horario_fim = ? 
                WHERE idAgendamento = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssi', $startDate, $startTime, $endDate, $endTime, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Agendamento atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar o agendamento: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Dados incompletos.";
    }
} else {
    echo "Método de requisição inválido.";
}

mysqli_close($conn);
?>
