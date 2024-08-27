<?php
header('Content-Type: application/xml'); // Garantir que a resposta seja XML

// Iniciar o buffer de saída para limpar qualquer conteúdo antes do XML
ob_start();

include_once "../bd/conn.php";

// Query SQL adaptada para retornar as informações necessárias para o agendamento e exibição de turmas
$sql = "SELECT a.idAgendamento, a.data_inicio, a.horario_inicio, a.data_final, a.horario_fim, 
        a.usuario_idUsuario, a.unidade_curricular_id, a.turma_id, a.sala_id, a.professor_id, a.dias_aula, 
        ad.nome AS andar, a.status, t.nome_turma, c.nome_curso, s.nome AS nome_sala, p.nome AS nome_professor 
        FROM agendamento a 
        JOIN turmas t ON a.turma_id = t.turma_id 
        JOIN professores p ON a.professor_id = p.idProfessor 
        JOIN cursos c ON t.curso_id = c.curso_id 
        JOIN salas s ON a.sala_id = s.id_sala 
        JOIN unidade_curricular uc ON a.unidade_curricular_id = uc.idunidade_curricular 
        JOIN andar ad ON s.andar_id = ad.id_andar";

$consulta = mysqli_query($conn, $sql);

if (!$consulta) {
    echo '<error>Consulta falhou: ' . mysqli_error($conn) . '</error>';
} else {
    $xml = new SimpleXMLElement('<agendamento/>');

    while ($user_data = mysqli_fetch_assoc($consulta)) {
        $agendamento = $xml->addChild('agendamento');

        $agendamento->addChild('nome_turma', $user_data['nome_turma']);
        $agendamento->addChild('nome_curso', $user_data['nome_curso']);
        $agendamento->addChild('nome_sala', $user_data['nome_sala']);
        $agendamento->addChild('andar', $user_data['andar']);
        $agendamento->addChild('nome_professor', $user_data['nome_professor']);
        $agendamento->addChild('horario_inicio', $user_data['horario_inicio']);
        $agendamento->addChild('horario_final', $user_data['horario_final']);
        $agendamento->addChild('data_inicio', $user_data['data_inicio']);
        $agendamento->addChild('data_fim', $user_data['data_final']);
        $agendamento->addChild('dias_aula', $user_data['dias_aula']);
    }

    // Limpar o buffer de saída e enviar o XML
    ob_end_clean();
    echo $xml->asXML();
}
exit;

mysqli_close($conn);
