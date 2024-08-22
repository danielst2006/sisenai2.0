<?php 
session_start();

if(isset($_SESSION['login'])){
    if($_SESSION['tipo_usuario'] == "COPED" || $_SESSION['tipo_usuario'] == "ADM"){

    } else {
        header('Location: ../form/menu.php');
    }
} else {
    header('Location: ../form/login.php');
}
include_once "../head/menu.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Inclua seu head aqui -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Adaptação para o Dashboard */
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
            margin-top: 20px; /* Espaçamento adicional para não sobrepor o cabeçalho */
        }

        .live-classes, .coordinator-classes {
            width: 48%;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 20px; /* Para espaçamento entre os blocos */
        }

        .stats-box {
            width: 30%;
            background-color: #f9f9f9;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px; /* Para espaçamento entre os blocos */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        thead {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Estatísticas -->
        <div class="stats-box">
            <h3>Total de Turmas no Período Atual</h3>
            <p>10</p>
        </div>
        <div class="stats-box">
            <h3>Turmas Acompanhadas pelo Coordenador</h3>
            <p>5</p>
        </div>
        <div class="stats-box">
            <h3>Turmas do Dia</h3>
            <p>20</p>
        </div>

        <!-- Tabelas -->
        <div class="live-classes">
            <h2>Turmas ao Vivo</h2>
            <table>
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
                        <td>Turma 101</td>
                        <td>Tec. Informática</td>
                        <td>João da Silva</td>
                        <td>Sala 1</td>
                        <td>Ativa</td>
                    </tr>
                    <tr>
                        <td>Turma 102</td>
                        <td>Segurança do Trabalho</td>
                        <td>Maria Oliveira</td>
                        <td>Sala 2</td>
                        <td>Ativa</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="coordinator-classes">
            <h2>Turmas que o Coordenador Acompanha</h2>
            <table>
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

        <div class="coordinator-classes">
            <h2>Salas em Uso e Andares</h2>
            <table>
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
</body>
</html>
