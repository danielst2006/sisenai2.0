<?php
session_start();
include_once "../bd/conn.php"; // Certifique-se de incluir o arquivo de conexão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['idAgendamento']) && isset($data['status'])) {
        $idAgendamento = intval($data['idAgendamento']);
        $status = $data['status'];

        // Atualiza o status no banco de dados
        $sql = "UPDATE agendamento SET status = ? WHERE idAgendamento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $status, $idAgendamento);

        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar o status.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos fornecidos.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
}
?>
