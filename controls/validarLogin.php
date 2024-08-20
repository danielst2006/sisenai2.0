<?php
if (isset($_POST['login'])) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];


    // echo $login, $senha;

    include_once "../bd/conn.php";
    $sql = "SELECT nome_usuario, senha, t.tipo AS tipos FROM usuarios JOIN tipo_usuario t ON tipo_usuario_id = idTipo_usuario  WHERE nome_usuario = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $login);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($num_registros = mysqli_num_rows($result)) {
            $linha = mysqli_fetch_assoc($result);
            
            if (password_verify($senha, $linha['senha'])) {
                session_start();
                $_SESSION['login'] = $login;
                $_SESSION['tipo_usuario'] = $linha['tipos'];
                header("location: ../form/menu.php");
                exit();
            } else {
                header("location: ../form/login.php");
            }
        } else {
            header("location: ../form/login.php");
        }
    } else {
        header("location: ../form/login.php");
    }
}
?>