<?php
header('Content-Type: application/xml'); // Garantir que a resposta seja XML

// Iniciar o buffer de saída para limpar qualquer conteúdo antes do XML
ob_start();

include_once "../bd/conn.php";

$sql = "SELECT t.turma_id, t.nome_turma, c.nome_curso, s.nome_sala, s.andar,
        p.nome_professor, t.horario_inicio, t.horario_final,
        t.data_inicio, t.data_fim, t.dias_aula 
        FROM turmas t
        JOIN professores p ON p.professor_id = t.professor_id
        JOIN cursos c ON c.curso_id = t.curso_id
        JOIN salas s ON s.sala_id = t.sala_id ORDER BY s.andar, s.nome_sala";

$consulta = mysqli_query($conn, $sql);

if (!$consulta) {
    echo '<error>Consulta falhou: ' . mysqli_error($conn) . '</error>';
} else {
    $xml = new SimpleXMLElement('<turmas/>');

    while ($user_data = mysqli_fetch_assoc($consulta)) {
        $turma = $xml->addChild('turma');
        
        $turma->addChild('nome_turma', $user_data['nome_turma']);
        $turma->addChild('nome_curso', $user_data['nome_curso']);
        $turma->addChild('nome_sala', $user_data['nome_sala']);
        $turma->addChild('andar', $user_data['andar']);
        $turma->addChild('nome_professor', $user_data['nome_professor']);
        $turma->addChild('horario_inicio', $user_data['horario_inicio']);
        $turma->addChild('horario_final', $user_data['horario_final']);
        $turma->addChild('data_inicio', $user_data['data_inicio']);
        $turma->addChild('data_fim', $user_data['data_fim']);
        $turma->addChild('dias_aula', $user_data['dias_aula']);
       
        
    }

    // Limpar o buffer de saída e enviar o XML
    ob_end_clean();
    echo $xml->asXML();
}
exit;