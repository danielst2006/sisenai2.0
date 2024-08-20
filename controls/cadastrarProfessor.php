<?php 

include_once '../bd/conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $action = $_POST['action'];
    $id = isset($_POST['idProfessor']) ? $_POST['idProfessor'] : null;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $usuario_id = $_POST['usuario_id'];
    $area = $_POST['area'];
    $tipo_contrato = $_POST['tipo_contrato'];

    if ($action == 'add') {
        $sql = "INSERT INTO professores (nome, email, telefone, usuario_id, area, tipo_contrato) 
                VALUES ('$nome', '$email', '$telefone', $usuario_id, '$area', '$tipo_contrato')";
    } elseif ($action == 'update') {
        $sql = "UPDATE professores 
                SET nome='$nome', email='$email', telefone='$telefone', usuario_id=$usuario_id, area='$area', tipo_contrato='$tipo_contrato' 
                WHERE idProfessor=$id";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM professores WHERE idProfessor=$id";
    }

    $resultado = mysqli_query($conn, $sql);
    if ($resultado) {
        header('Location: ../form/formProfessor.php?status=success');
    } else {
        header('Location: ../form/formProfessor.php?status=error');
    }
    exit();
}

// Código para exibir os registros com o nome do usuário associado
$sql_select = "SELECT p.idProfessor, p.nome, p.email, p.telefone, p.area, p.tipo_contrato, u.nome_usuario AS usuario_nome 
               FROM professores p
               JOIN usuarios u ON p.usuario_id = u.idUsuario";

$resultado_select = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($resultado_select) > 0) {
    while($row = mysqli_fetch_assoc($resultado_select)) {
        echo "ID: " . $row["idProfessor"]. " - Nome: " . $row["nome"]. " - Email: " . $row["email"]. 
             " - Telefone: " . $row["telefone"]. " - Área: " . $row["area"]. 
             " - Tipo de Contrato: " . $row["tipo_contrato"]. " - Usuário: " . $row["usuario_nome"]. "<br>";
    }
} else {
    echo "0 resultados";
}

mysqli_close($conn);

?>
