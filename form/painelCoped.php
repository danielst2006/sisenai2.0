<?php 
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
        box-shadow: 0 0 10px rgba(65,105,225);
        border-radius: 20px;
        margin-bottom: 45px;
        /* Para espaçamento entre os blocos */
    }

    .stats-box {
        width: 30%;
        background-color: #f9f9f9;
        padding: 10px;
        box-shadow: 0 0 10px rgba(65,105,225);
        border-radius: 20px;
        text-align: center;
        margin-bottom: 35px;
        /* Para espaçamento entre os blocos */
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
    <center>
    <h4 style="color:darkblue;">Painel COPED</h4>
    </center>

    <div class="dashboard-container">
        <!-- Estatísticas -->
        <div class="stats-box">
            <h2 style="color:coral;">Total de Turmas no Período Atual</h2>
                <h4 style="color:crimson">10</h4>
        </div>
        <div class="stats-box">
            <h2 style="color:coral;">Turmas Acompanhadas p/ Coordenador</h2>
                <h4 style="color:crimson">5</h4>
        </div>
        <div class="stats-box">
            <h2 style="color:coral;">Turmas do Dia</h2>
            <h4 style="color:crimson">20</h4>
                <!-- <h1 style="color:darkblue">Matutino</h1>
                <h1 style="color:darkblue">Vespertino</h1>
                <h1 style="color:darkblue">Noturno</h1> -->
                
        </div>

        <!-- Tabelas -->
        <div class="live-classes">
            <h2 style="color:darkblue;">Turmas ao Vivo</h2>
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