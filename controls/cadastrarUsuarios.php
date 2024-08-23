<?php
include_once "../bd/conn.php";

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'insert') {
    // Obter os dados do formulário
    $nome_usuario = isset($_POST['nome_usuario']) ? $_POST['nome_usuario'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $senha = isset($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : '';
    $tipo_contrato = isset($_POST['tipo_contrato']) ? $_POST['tipo_contrato'] : '';
    $tipo_usuario_id = isset($_POST['tipo_usuario_id']) ? $_POST['tipo_usuario_id'] : '';
    $codigo_acesso = isset($_POST['codigo_acesso']) ? $_POST['codigo_acesso'] : '';

    // Verificar se todos os campos obrigatórios estão preenchidoss
    if ($nome_usuario && $email && $telefone && $senha && $tipo_contrato && $tipo_usuario_id && $codigo_acesso) {
        // Verificar se o e-mail já está cadastrado
        $checkEmailQuery = "SELECT COUNT(*) AS total FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $checkEmailQuery);
        $row = mysqli_fetch_assoc($result);

        if ($row['total'] > 0) {
            // E-mail já existe
            header("Location: ../form/formUsuarios.php?status=error&message=" . urlencode("E-mail já cadastrado."));
            exit();
        }

        // Inserir no banco de dados
        $sql = "INSERT INTO usuarios (nome_usuario, email, telefone, senha, tipo_contrato, tipo_usuario_id, codigo_acesso) 
                VALUES ('$nome_usuario', '$email', '$telefone', '$senha', '$tipo_contrato', '$tipo_usuario_id', '$codigo_acesso')";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../form/formUsuarios.php?status=success");
            exit();
        } else {
            $error_message = mysqli_error($conn);
            header("Location: ../form/formUsuarios.php?status=error&message=" . urlencode($error_message));
            exit();
        }
    } else {
        header("Location: ../form/formUsuarios.php?status=error&message=" . urlencode("Todos os campos são obrigatórios."));
        exit();
    }
} elseif ($action == 'update') {
    // Obter os dados do formulário
    $idUsuario = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : '';
    $nome_usuario = isset($_POST['nome_usuario']) ? $_POST['nome_usuario'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $senha = isset($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : '';
    $tipo_contrato = isset($_POST['tipo_contrato']) ? $_POST['tipo_contrato'] : '';
    $tipo_usuario_id = isset($_POST['tipo_usuario_id']) ? $_POST['tipo_usuario_id'] : '';
    $codigo_acesso = isset($_POST['codigo_acesso']) ? $_POST['codigo_acesso'] : '';

    // Verificar se todos os campos obrigatórios estão preenchidos
    if ($idUsuario && $nome_usuario && $email && $telefone && $senha && $tipo_contrato && $tipo_usuario_id && $codigo_acesso) {
        // Verificar se o e-mail já está cadastrado para outro usuário
        $checkEmailQuery = "SELECT COUNT(*) AS total FROM usuarios WHERE email = '$email' AND idUsuario != '$idUsuario'";
        $result = mysqli_query($conn, $checkEmailQuery);
        $row = mysqli_fetch_assoc($result);

        if ($row['total'] > 0) {
            // E-mail já existe
            header("Location: ../form/formUsuarios.php?status=error&message=" . urlencode("E-mail já cadastrado."));
            exit();
        }

        // Atualizar no banco de dados
        $sql = "UPDATE usuarios SET nome_usuario='$nome_usuario', email='$email', telefone='$telefone', senha='$senha', 
                tipo_contrato='$tipo_contrato', tipo_usuario_id='$tipo_usuario_id', codigo_acesso='$codigo_acesso' 
                WHERE idUsuario='$idUsuario'";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../form/formUsuarios.php?status=success");
            exit();
        } else {
            $error_message = mysqli_error($conn);
            header("Location: ../form/formUsuarios.php?status=error&message=" . urlencode($error_message));
            exit();
        }
    } else {
        header("Location: ../form/formUsuarios.php?status=error&message=" . urlencode("Todos os campos são obrigatórios."));
        exit();
    }
} elseif ($action == 'delete') {
    $idUsuario = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : '';

    if ($idUsuario) {
        // Deletar do banco de dados
        $sql = "DELETE FROM usuarios WHERE idUsuario='$idUsuario'";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../form/formUsuarios.php?status=success");
            exit();
        } else {
            $error_message = mysqli_error($conn);
            header("Location: ../form/formUsuarios.php?status=error&message=" . urlencode($error_message));
            exit();
        }
    } else {
        header("Location: ../form/formUsuarios.php?status=error&message=" . urlencode("ID do usuário não fornecido."));
        exit();
    }
}

mysqli_close($conn)
?>
