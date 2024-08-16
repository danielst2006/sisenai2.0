<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="60">
   
    <title>Exibição de Imagens e Lista de Turmas</title>
    <link rel="stylesheet" href="style01.css">
</head>
<body>
<div class="header1">
    <span id="current-date" class="date"></span>
    <span id="current-time" class="time"></span>
    <img src="../imagens/senai.png" alt="Logo" class="logo">
</div>

<div class="main-content">
    <div class="table-size">
        <div class="table-container" style="overflow-x: auto;">
            <table id="turma-table">
                <thead>
                <tr>
                    <th>Nome do Turma</th>
                    <th>Curso</th>
                    <th>Sala</th>
                    <th>Professor</th>
                    <th>Horário Início</th>
                    <th>Horário Fim</th>
                </tr>
                </thead>
                <tbody id="turmas-body">
                <!-- Conteúdo da tabela de turmas será inserido aqui via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>



<script src="script.js"></script>

</body>
</html>
