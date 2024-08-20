<?php
session_start();
include_once '../models/conexao.php';

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_de_usuario = $_SESSION['nome_de_usuario'];

    // Lidar com o upload da foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        $foto_perfil = $_FILES['foto_perfil'];
        $extensao = pathinfo($foto_perfil['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid() . '.' . $extensao;
        $caminho_destino = '../uploads/' . $nome_arquivo;

        // Verificar tipo de arquivo e tamanho
        $tipos_permitidos = ['jpg', 'jpeg', 'png'];
        if (in_array($extensao, $tipos_permitidos) && $foto_perfil['size'] <= 5 * 1024 * 1024) { // 5 MB
            if (move_uploaded_file($foto_perfil['tmp_name'], $caminho_destino)) {
                // Atualizar o caminho da foto no banco de dados
                $sql_foto = "UPDATE usuarios SET foto_perfil = '$nome_arquivo' WHERE nome_de_usuario = '$nome_de_usuario'";
                mysqli_query($conn, $sql_foto);
            }
        }
    }

    // Atualizar os outros dados do usuário apenas se forem enviados
    $campos = [];
    if (!empty($_POST['nome'])) {
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $campos[] = "nome = '$nome'";
    }
    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $campos[] = "email = '$email'";
    }
    if (!empty($_POST['telefone'])) {
        $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
        $campos[] = "telefone = '$telefone'";
    }
    if (!empty($_POST['senha'])) {
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $campos[] = "senha = '$senha_hash'";
    }

    if (!empty($campos)) {
        $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE nome_de_usuario = '$nome_de_usuario'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem_sucesso'] = "Perfil atualizado com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Erro ao atualizar perfil.";
        }
    }

    mysqli_close($conn);
    header("Location: ../views/perfil.php");
    exit();
}
?>
