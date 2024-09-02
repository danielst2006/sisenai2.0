<?php
include('../bd/conn.php');

// Função para determinar o recurso (Manhã, Tarde, Noite) com base nos horários de início e fim
function determineResource($startHour, $endHour) {
    $morningStart = strtotime("07:00:00");
    $morningEnd = strtotime("11:59:59");
    $afternoonStart = strtotime("13:00:00");
    $afternoonEnd = strtotime("17:59:59");
    $eveningStart = strtotime("18:00:00");
    $eveningEnd = strtotime("23:59:59");

    if ($startHour <= $morningEnd && $endHour >= $morningStart) {
        return "M"; // Manhã
    } elseif ($startHour <= $afternoonEnd && $endHour >= $afternoonStart) {
        return "T"; // Tarde
    } elseif ($startHour <= $eveningEnd && $endHour >= $eveningStart) {
        return "N"; // Noite
    } else {
        return null; // Ignora eventos que não se enquadrem nos intervalos
    }
}

// Consulta SQL para selecionar os dados necessários para os eventos
$sql = "SELECT a.idAgendamento, a.data_inicio, a.horario_inicio, a.data_final, a.horario_fim, 
        a.usuario_idUsuario, a.unidade_curricular_id, a.turma_id, a.sala_id, a.professor_id, a.dias_aula, 
        ad.nome AS andar, a.status, t.nome_turma, c.nome_curso, s.nome AS nome_sala, p.nome AS nome_professor 
        FROM agendamento a 
        JOIN turmas t ON a.turma_id = t.turma_id 
        JOIN professores p ON a.professor_id = p.idProfessor 
        JOIN cursos c ON t.curso_id = c.curso_id 
        JOIN salas s ON a.sala_id = s.id_sala 
        JOIN unidade_curricular uc ON a.unidade_curricular_id = uc.idunidade_curricular 
        JOIN andar ad ON s.andar_id = ad.id_andar 
        WHERE a.professor_id = 79";

$result = mysqli_query($conn, $sql);

$events = [];
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $startHour = strtotime($row["horario_inicio"]);
        $endHour = strtotime($row["horario_fim"]);

        $resource = determineResource($startHour, $endHour);
        if ($resource === null) continue;

        $events[] = [
            "id" => $row["idAgendamento"],
            "start" => $row["data_inicio"] . "T" . $row["horario_inicio"],
            "end" => $row["data_final"] . "T" . $row["horario_fim"],
            "resource" => $resource,
            "text" => $row["nome_professor"] . " - " . $row["nome_turma"],
        ];
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calendários Mensais (JavaScript Scheduler)</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/main.css?v=2024.3.5997" type="text/css" rel="stylesheet"/>
    <script src="../js/daypilot-all.min.js?v=2024.3.5997"></script>
    <style>
        .scheduler-container {
            margin-bottom: 30px;
        }
        .day-saturday, .day-sunday {
            background-color: #ffdddd !important; /* Cor de fundo vermelho claro */
        }
    </style>
</head>
<body>

    <div id="calendarios">
        <!-- Calendários mensais serão inseridos aqui -->
    </div>

    <script type="text/javascript">
        const events = <?php echo json_encode($events); ?>;
        const months = [
            {startDate: "2024-01-01", days: 31, name: "Janeiro"},
            {startDate: "2024-02-01", days: 29, name: "Fevereiro"},
            {startDate: "2024-03-01", days: 31, name: "Março"},
            {startDate: "2024-04-01", days: 30, name: "Abril"},
            {startDate: "2024-05-01", days: 31, name: "Maio"},
            {startDate: "2024-06-01", days: 30, name: "Junho"},
            {startDate: "2024-07-01", days: 31, name: "Julho"},
            {startDate: "2024-08-01", days: 31, name: "Agosto"},
            {startDate: "2024-09-01", days: 30, name: "Setembro"},
            {startDate: "2024-10-01", days: 31, name: "Outubro"},
            {startDate: "2024-11-01", days: 30, name: "Novembro"},
            {startDate: "2024-12-01", days: 31, name: "Dezembro"}
        ];

        function createScheduler(containerId, month) {
            const dp = new DayPilot.Scheduler(containerId, {
                locale: "pt-br",
                startDate: month.startDate,
                days: month.days,
                scale: "Day",
                cellWidth: 50,
                resources: [
                    {name: "Manhã", id: "M"},
                    {name: "Tarde", id: "T"},
                    {name: "Noite", id: "N"}
                ],
                timeHeaders: [
                    {groupBy: "Month", format: "MMMM yyyy"},
                    {groupBy: "Day", format: "d"}
                ],
                events: events.filter(event => {
                    const eventStartDate = new Date(event.start);
                    const eventEndDate = new Date(event.end);
                    const monthStartDate = new Date(month.startDate);
                    const monthEndDate = new Date(monthStartDate);
                    monthEndDate.setDate(month.days);

                    return (eventStartDate <= monthEndDate && eventEndDate >= monthStartDate);
                }),
                onEventMoved: function(args) {
                    updateEvent(args.e.data.id, args.newStart, args.newEnd);
                },
                onEventResized: function(args) {
                    updateEvent(args.e.data.id, args.newStart, args.newEnd);
                },
                onBeforeCellRender: function(args) {
                    const dayOfWeek = new Date(args.start).getDay();
                    if (dayOfWeek === 6) {
                        args.cell.cssClass = "day-saturday";
                    } else if (dayOfWeek === 0) {
                        args.cell.cssClass = "day-sunday";
                    }
                }
            });
            dp.init();
        }

        function updateEvent(id, start, end) {
    console.log(`Enviando dados: ID: ${id}, Start: ${start}, End: ${end}`);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_event.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    const params = `id=${encodeURIComponent(id)}&start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`;
    console.log(`Parâmetros enviados: ${params}`); // Verifique os parâmetros

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText); // Verifique a resposta do servidor
        }
    };
    xhr.send(params);
}


        function initCalendars() {
            const container = document.getElementById("calendarios");

            months.forEach((month, index) => {
                const schedulerContainer = document.createElement("div");
                schedulerContainer.className = "scheduler-container";
                schedulerContainer.id = `scheduler_${index}`;
                container.appendChild(schedulerContainer);

                const monthTitle = document.createElement("h3");
                monthTitle.innerText = month.name;
                container.insertBefore(monthTitle, schedulerContainer);

                createScheduler(schedulerContainer.id, month);
            });
        }

        initCalendars();
    </script>

</body>
</html>
