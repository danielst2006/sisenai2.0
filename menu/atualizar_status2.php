<?php
// Habilitar exibição de erros para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../bd/conn.php';  // Conexão com o banco de dados

// Lê os dados JSON enviados pelo JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Verifique se os dados foram recebidos corretamente
if (isset($data['idAgendamento']) && isset($data['status'])) {
    $idAgendamento = intval($data['idAgendamento']);
    $novoStatus = $data['status'];

    // Atualiza o status no banco de dados
    $sql = "UPDATE agendamento SET status = ? WHERE idAgendamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $novoStatus, $idAgendamento);

    if ($stmt->execute()) {
        // Retorna uma resposta de sucesso em JSON corretamente formatada
        header('Content-Type: application/json');
        echo json_encode(['sucesso' => true]);
    } else {
        // Retorna erro se a query falhar
        header('Content-Type: application/json');
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar status no banco de dados']);
    }

    $stmt->close();
    $conn->close();
} else {
    // Retorna erro se os dados não forem válidos
    header('Content-Type: application/json');
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos']);
}
?>
