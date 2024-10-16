<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .calendar-day {
            border: 1px solid #ccc;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .calendar-day.weekend {
            background-color: #f8d7da;
            color: black;
        }

        .calendar-day.deselected {
            background-color: #f8d7da;
        }

        .calendar-day input[type="checkbox"] {
            display: none;
        }

        .month-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 30px;
        }
    </style>
</head>

<body class="container mt-4">
    <h1 class="text-center mb-4">Agendamento de Aula</h1>

    <form id="agendamentoForm" class="row g-3">
        <div class="col-md-6">
            <label for="data_inicio" class="form-label">Data Início:</label>
            <input type="date" id="data_inicio" name="data_inicio" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="data_final" class="form-label">Data Final:</label>
            <input type="date" id="data_final" name="data_final" class="form-control" required>
        </div>
        <div class="col-12">
            <button type="button" class="btn btn-primary" onclick="mostrarDatas()">Buscar Datas</button>
        </div>
    </form>

    <div id="calendars"></div>

    <!-- Bootstrap JS e dependências (Opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function formatarDataBrasileira(data) {
            let dia = data.getDate().toString().padStart(2, '0');
            let mes = (data.getMonth() + 1).toString().padStart(2, '0');
            let ano = data.getFullYear();
            return `${dia}/${mes}/${ano}`;
        }

        function obterNomeDiaSemana(data) {
            const diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            return diasSemana[data.getDay()];
        }

        function obterNomeMes(mes) {
            const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
            return meses[mes];
        }

        function mostrarDatas() {
            let inicio = new Date(document.getElementById('data_inicio').value);
            let final = new Date(document.getElementById('data_final').value);
            let calendarsDiv = document.getElementById('calendars');
            calendarsDiv.innerHTML = ''; // Limpa os calendários anteriores

            if (inicio > final) {
                alert('A data final não pode ser anterior à data de início.');
                return;
            }

            let currentDate = inicio;
            while (currentDate <= final) {
                let year = currentDate.getFullYear();
                let month = currentDate.getMonth();

                // Cria o título do mês
                let monthTitle = document.createElement('div');
                monthTitle.className = 'month-title';
                monthTitle.textContent = `${obterNomeMes(month)} ${year}`;
                calendarsDiv.appendChild(monthTitle);

                // Cria o calendário
                let calendarDiv = document.createElement('div');
                calendarDiv.className = 'calendar';

                // Adiciona os dias da semana no cabeçalho
                ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'].forEach(dia => {
                    let dayHeader = document.createElement('div');
                    dayHeader.className = 'calendar-day';
                    dayHeader.textContent = dia;
                    dayHeader.style.fontWeight = 'bold';
                    calendarDiv.appendChild(dayHeader);
                });

                // Preencher os dias em branco antes do primeiro dia do mês
                let firstDayOfMonth = new Date(year, month, 1);
                for (let i = 0; i < firstDayOfMonth.getDay(); i++) {
                    let emptyBox = document.createElement('div');
                    emptyBox.className = 'calendar-day';
                    calendarDiv.appendChild(emptyBox);
                }

                // Preencher os dias do mês
                while (currentDate.getMonth() === month && currentDate <= final) {
                    let dayOfWeek = currentDate.getDay();
                    let isWeekend = (dayOfWeek === 0 || dayOfWeek === 6); // Domingo ou Sábado
                    let dateText = formatarDataBrasileira(currentDate);

                    // Cria a caixa para cada dia
                    let dayBox = document.createElement('div');
                    dayBox.className = 'calendar-day ' + (isWeekend ? 'weekend deselected' : 'weekday');

                    // Cria o checkbox
                    let checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = dateText;
                    checkbox.id = 'checkbox-' + dateText;
                    checkbox.checked = !isWeekend;
                    checkbox.style.display = 'none'; // Esconde o checkbox

                    // Evento para clicar no dia e alternar seleção
                    dayBox.addEventListener('click', function () {
                        checkbox.checked = !checkbox.checked;
                        dayBox.classList.toggle('deselected');
                    });

                    // Adiciona o dia e a data na caixa
                    let labelDay = document.createElement('div');
                    labelDay.textContent = currentDate.getDate();

                    dayBox.appendChild(labelDay); // Data numérica
                    dayBox.appendChild(checkbox); // Checkbox

                    calendarDiv.appendChild(dayBox);

                    currentDate.setDate(currentDate.getDate() + 1);
                }

                calendarsDiv.appendChild(calendarDiv);
            }
        }
    </script>
</body>

</html>
