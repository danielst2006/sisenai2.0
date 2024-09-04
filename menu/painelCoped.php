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

// Consulta para listar a quantidade de turmas nos turnos
$quantidade_manha = 0;
$quantidade_tarde = 0;
$quantidade_noite = 0;

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

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $quantidade_manha = $row['quantidade_manha'] ?? 0;
    $quantidade_tarde = $row['quantidade_tarde'] ?? 0;
    $quantidade_noite = $row['quantidade_noite'] ?? 0;
}

$total_turmas = $quantidade_manha + $quantidade_tarde + $quantidade_noite;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 20px;
        }

        .header-text {
            margin-bottom: 40px;
        }

        .card-custom {
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .card-header-custom {
            background-color: #343a40;
            color: #ffffff;
            border-radius: 20px 20px 0 0;
            padding: 20px;
        }

        .card-body-custom {
            padding: 30px;
        }

        .display-4 {
            font-weight: bold;
        }

        .text-danger {
            color: #e74c3c !important;
        }

        .text-warning {
            color: #f39c12 !important;
        }

        .text-info {
            color: #3498db !important;
        }

        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .table-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #343a40;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: #2c3e50;
            color: #ffffff;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .icon-large {
            font-size: 24px;
            vertical-align: middle;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h4 class="text-center text-primary header-text">Painel COPED</h4>

        <!-- Dashboard Stats -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-custom h-100 d-flex flex-column ">
                    <div class="card-header-custom text-center">
                        <i class="fas fa-chalkboard-teacher icon-large"></i> Total de Turmas
                    </div>
                    <div class="card-body card-body-custom text-center">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-secondary">Manhã</h5>
                                <p class="display-4 text-danger"><?php echo $quantidade_manha; ?></p>
                            </div>
                            <div class="col">
                                <h5 class="text-secondary">Tarde</h5>
                                <p class="display-4 text-warning"><?php echo $quantidade_tarde; ?></p>
                            </div>
                            <div class="col">
                                <h5 class="text-secondary">Noite</h5>
                                <p class="display-4 text-info"><?php echo $quantidade_noite; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100 d-flex flex-column">
                    <div class="card-header-custom text-center bg-success">
                        <i class="fas fa-user-tie icon-large"></i> Minhas Turmas
                    </div>
                    <div class="card-body card-body-custom text-center">
                        <p class="display-4 text-success">5</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100 d-flex flex-column">
                    <div class="card-header-custom text-center bg-warning">
                        <i class="fas fa-calendar-day icon-large"></i> Turmas de Hoje
                    </div>
                    <div class="card-body card-body-custom text-center">
                        <p class="display-4 text-warning"><?php echo $total_turmas; ?></p>
                    </div>
                </div>
            </div>
        </div> <br> <br>

        <!-- Turmas ao Vivo -->
        <div class="table-container">
            <h2><i class="fas fa-video icon-large"></i> Turmas ao Vivo</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>TEMA</th>
                        <th>ÁREA</th>
                        <th>SALA</th>
                        <th>ANDAR</th>
                        <th>RESPONSÁVEL</th>
                        <th>INÍCIO</th>
                        <th>FIM</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody id="turmas-body">
                    <!-- Conteúdo da tabela de turmas será inserido aqui via JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Turmas que o Coordenador Acompanha -->
        <div class="table-container">
            <h2><i class="fas fa-user-check icon-large"></i> Turmas que o Coordenador Acompanha</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Curso</th>
                        <th>Professor</th>
                        <th>Sala</th>
                        <th>Status</th>
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

        <!-- Salas em Uso e Andares -->
        <div class="table-container">
            <h2><i class="fas fa-building icon-large"></i> Salas em Uso e Andares</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sala</th>
                        <th>Andar</th>
                        <th>Cursos</th>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../menu/scriptCoped.js"></script>
</body>

</html>
