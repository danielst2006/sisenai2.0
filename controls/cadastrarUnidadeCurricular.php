<?php

include_once '../bd/conn.php';

// Verifica se a ação foi enviada pelo formulário
$action = isset($_POST['action']) ? $_POST['action'] : '';

$idunidade_curricular = isset($_POST['idunidade_curricular']) ? $_POST['idunidade_curricular'] : '';
$nome_unidade = isset($_POST['nome_unidade']) ? $_POST['nome_unidade'] : '';
$carga_horaria = isset($_POST['carga_horaria']) ? $_POST['carga_horaria'] : '';
$curso_id = isset($_POST['curso_id']) ? $_POST['curso_id'] : '';

// Inicializa a variável $sql
$sql = '';

if ($action == 'delete') {
    // Deletar unidade curricular
    $sql = "DELETE FROM unidade_curricular WHERE idunidade_curricular = '$idunidade_curricular'";
} elseif ($idunidade_curricular) {
    // Editar unidade curricular existente
    $sql = "UPDATE unidade_curricular 
            SET nome_unidade = '$nome_unidade', 
                carga_horaria = '$carga_horaria', 
                curso_id = '$curso_id' 
            WHERE idunidade_curricular = '$idunidade_curricular'";
} else {
    // Adicionar nova unidade curricular
    $sql = "INSERT INTO unidade_curricular (nome_unidade, carga_horaria, curso_id) 
            VALUES ('$nome_unidade', '$carga_horaria', '$curso_id')";
}

// Executa a consulta SQL
if ($sql) {
    if (mysqli_query($conn, $sql)) {
        header('Location: ../form/formUnidadeCurricular.php?status=success');
    } else {
        header('Location: ../form/formUnidadeCurricular.php?status=error');
    }
} else {
    // Se a variável $sql estiver vazia, redirecione para a página com uma mensagem de erro
    header('Location: ../form/formUnidadeCurricular.php?status=error');
}

// Fecha a conexão
mysqli_close($conn);

?>
