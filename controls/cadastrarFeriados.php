<?php 

include_once '../bd/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'];
    $id = isset($_POST['idFeriados']) ? $_POST['idFeriados'] : null;
    $nome = $_POST['nome'];
    $dia_feriado = $_POST['dia_feriado'];
    $tipo = $_POST['tipo'];

    if ($action == 'add') {
        // Adiciona um novo feriado
        $sql = "INSERT INTO feriados (nome, dia_feriado, tipo) VALUES ('$nome', '$dia_feriado', '$tipo')";
    } elseif ($action == 'update') {
        // Atualiza um feriado existente
        $sql = "UPDATE feriados SET nome='$nome', dia_feriado='$dia_feriado', tipo='$tipo' WHERE idFeriados=$id";
    } elseif ($action == 'delete') {
        // Deleta um feriado
        $sql = "DELETE FROM feriados WHERE idFeriados=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formFeriados.php?status=success');
    } else {
        header('Location: ../form/formFeriados.php?status=error');
    }
    exit();
}

mysqli_close($conn)

?>
