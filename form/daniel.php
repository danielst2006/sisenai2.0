<?php 

    session_start();

    if(isset($_SESSION['login'])){
        $login = $_SESSION['login'];
        $tipo = $_SESSION['tipo_usuario'];
        $id = $_SESSION['id'];
    }else{ 
        header('Location: ../form/login.php');
    }

    include_once "../bd/conn.php";
    //Agendamento do usuário
    $sql = "SELECT a.idAgendamento AS id, 
                   a.data_inicio AS start, 
                   a.data_final AS end, 
                   u.nome_usuario AS title
            FROM agendamento a 
            JOIN usuarios u ON a.usuario_idUsuario = u.idUsuario 
            WHERE u.idUsuario = $id";

    //selecionar turmas

    $sql = "SELECT t.idTurma AS id, 
                   t.nome_turma AS title
            FROM turma t 
            JOIN usuarios u ON t.usuario_idUsuario = u.idUsuario 
            WHERE u.idUsuario = $id";


    echo "Olá, " .$login;

    $resultado = mysqli_query($conn, $sql);

    

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

//criar uma tabela de agendamentos do usuario

    <table>
        <tr>
            <th>Codigo</th>
            <th>Data Inicio</th>
            <th>Data Final</th>
            <th>Usuário</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($resultado)){ ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['start']; ?></td>
                <td><?php echo $row['end']; ?></td>
                <td><?php echo $row['title']; ?></td>
            </tr>






            <?php } ?>
    </table>
   
</body>
</html>