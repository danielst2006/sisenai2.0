<?php

session_start();
if (isset($_SESSION['login'])) {
    if ($_SESSION['tipo_usuario'] != "COPED" && $_SESSION['tipo_usuario'] != "ADM") {
        header('Location: ../form/menu.php');
        exit();
    }
} else {
    header('Location: ../form/login.php');
    exit();
}

// Inclui o arquivo de menu
include_once '../head/menu.php';
include_once "../bd/conn.php";


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Inclua seu head aqui -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Adaptação para o Dashboard */
        .dashboard-container {
            display: left;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 50px;
            margin-top: 30px;
            margin-bottom: -98px;
            /* Espaçamento adicional para não sobrepor o cabeçalho */
        }

        .live-classes,
        .coordinator-classes {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(65, 105, 225);
            border-radius: 20px;
            margin-bottom: 45px;
            /* Para espaçamento entre os blocos */
        }

        .stats-box {
            width: 30%;
            background-color: #f9f9f9;
            padding: 10px;
            box-shadow: 0 0 10px rgba(65, 105, 225);
            border-radius: 20px;
            text-align: center;
            margin-bottom: 35px;
            margin-top: 20px;
            /* Para espaçamento entre os blocos */
        }

        .stats-inline {
            display: inline-flex;
            margin-top: 0 15 px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            
        }

        th,
        td {
            text-align: left;
            font-family: "Arial, Copperplate";
        }

        h1 {
            text-align: left;
            font-family: "Serif, Georgia";
            font-size: 22px;
            margin-top: 35px;
        }

        h2 {
            text-align: center;
            font-family: "Serif, Georgia";
            font-size: 25px;
            margin-top: 35px;
        }

        h4 {
            text-align: center;
            font-family: "Serif, Georgia";
            font-size: 40px;
            margin-top: 35px;
            margin-bottom: 5px;
        }

        thead {
            background-color: darkblue;
        }

        tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>

    <?php

    //Listar a quantidade de agendadmento de turmas nos três turnos os dados estão no banco de dados e os turno em horários diferentes manhã 08:00 as 12:00, tarde 13:00 as 18:00 e noite 19:00 as 22:00

    $quantidade_manha = 0;
    $quantidade_tarde = 0;
    $quantidade_noite = 0;

    // Consulta SQL para contar as turmas nos períodos de manhã, tarde e noite
    $sql = "
    SELECT 
        SUM(CASE 
            WHEN TIME(horario_inicio) BETWEEN '07:00:00' AND '12:00:00' 
                 OR TIME(horario_fim) BETWEEN '07:00:00' AND '12:00:00' 
                 OR (TIME(horario_inicio) <= '07:00:00' AND TIME(horario_fim) >= '12:00:00')
            THEN 1 ELSE 0 END) AS quantidade_manha,
        
        SUM(CASE 
            WHEN TIME(horario_inicio) BETWEEN '13:00:00' AND '18:00:00' 
                 OR TIME(horario_fim) BETWEEN '13:00:00' AND '18:00:00' 
                 OR (TIME(horario_inicio) <= '13:00:00' AND TIME(horario_fim) >= '18:00:00')
            THEN 1 ELSE 0 END) AS quantidade_tarde,
        
        SUM(CASE 
            WHEN TIME(horario_inicio) BETWEEN '19:00:00' AND '23:59:59' 
                 OR TIME(horario_fim) BETWEEN '19:00:00' AND '23:59:59' 
                 OR (TIME(horario_inicio) <= '19:00:00' AND TIME(horario_fim) >= '23:59:59')
            THEN 1 ELSE 0 END) AS quantidade_noite

    FROM 
        agendamento
    WHERE 
        CURDATE() BETWEEN data_inicio AND data_final
        AND FIND_IN_SET(
            CASE DAYOFWEEK(CURDATE())
                WHEN 1 THEN 'domingo'
                WHEN 2 THEN 'segunda'
                WHEN 3 THEN 'terca'
                WHEN 4 THEN 'quarta'
                WHEN 5 THEN 'quinta'
                WHEN 6 THEN 'sexta'
                WHEN 7 THEN 'sabado'
            END, dias_aula) > 0;
";

    // Executa a consulta usando mysqli_query
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Se houver resultado, obtém os valores
        $row = mysqli_fetch_assoc($result);
        $quantidade_manha = $row['quantidade_manha'] ?? 0;
        $quantidade_tarde = $row['quantidade_tarde'] ?? 0;
        $quantidade_noite = $row['quantidade_noite'] ?? 0;
    }

    // Calcula o total de turmas
    $total_turmas = $quantidade_manha + $quantidade_tarde + $quantidade_noite;




    ?>

    <center>
        <h4 style="color:darkblue;">Painel COPED</h4>
    </center>

    <div class="dashboard-container">
        <!-- Estatísticas -->
        <div class="stats-box">
            <h2 style="color:coral;">Total de Turmas no Período Atual</h2>
            <h4 style="color:crimson">Manhã: <?php echo $quantidade_manha; ?></h4>
            <h4 style="color:crimson">Tarde: <?php echo $quantidade_tarde; ?></h4>
            <h4 style="color:crimson">Noite: <?php echo $quantidade_noite; ?></h4>
        </div>
        <div class="stats-box">
            <h2 style="color:coral;">Turmas Acompanhadas p/ Coordenador</h2>
            <h4 style="color:crimson">5</h4>
        </div>
        <div class="stats-box">
            <h2 style="color:coral;">Turmas de Hoje</h2>
            <h4 style="color:crimson"><?php echo $total_turmas;?></h4>
            <!-- <h1 style="color:darkblue">Matutino</h1>
                <h1 style="color:darkblue">Vespertino</h1>
                <h1 style="color:darkblue">Noturno</h1> -->

        </div>

        <!-- Tabelas -->
        <div class="live-classes">
            <h2 style="color:darkblue;">Turmas ao Vivo</h2>
            <table id="turma-table">
                <thead>
                <tr>
                    
                    <th>TEMA</th>
                    <th>ÁREA</th>
                    <th>SALA</th>
                    <th>ANDAR</th>
                    <th>RESPONSAVEL</th>
                    <th>INÍCIO</th>
                    <th>FIM</th>
		    
                </tr>
                </thead>
                <tbody id="turmas-body">
                <!-- Conteúdo da tabela de turmas será inserido aqui via JavaScript -->
                </tbody>
            </table>

            <script src="../menu/script.js"></script>
        </div>

        <div class="coordinator-classes">
            <h2 style="color:darkblue;">Turmas que o Coordenador Acompanha</h2>
            <table>
                <thead>
                    <tr>
                        <th style="color:white;">Turma</th>
                        <th style="color:white;">Curso</th>
                        <th style="color:white;">Professor</th>
                        <th style="color:white;">Sala</th>
                        <th style="color:white;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Turma 201</td>
                        <td>Administração</td>
                        <td>Carlos Roberto</td>
                        <td>Sala 3</td>
                        <td>Em andamento</td>
                    </tr>
                    <tr>
                        <td>Turma 202</td>
                        <td>Logística</td>
                        <td>Paula Santos</td>
                        <td>Sala 4</td>
                        <td>Concluída</td>
                    </tr>
                    <tr>
                        <td>Turma 203</td>
                        <td>Segurança do Trabalho</td>
                        <td>Maria Oliveira</td>
                        <td>Sala 2</td>
                        <td>Agendada</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="coordinator-classes">
            <h2 style="color:darkblue;">Salas em Uso e Andares</h2>
            <table>
                <thead>
                    <tr>
                        <th style="color:white;">Sala</th>
                        <th style="color:white;">Andar</th>
                        <th style="color:white;">Cursos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Sala 1</td>
                        <td>1º Andar</td>
                        <td>Tec. Informática</td>
                    </tr>
                    <tr>
                        <td>Sala 2</td>
                        <td>2º Andar</td>
                        <td>Segurança do Trabalho</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>