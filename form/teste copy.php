<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .day-container {
            display: flex;
            flex-wrap: wrap;
        }
        .day-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 150px;
            height: 100px;
            text-align: center;
            border-radius: 8px;
            background-color: #28a745; /* Fundo verde por padrão */
            cursor: pointer;
            transition: background-color 0.3s; /* Transição suave ao mudar a cor */
        }
        .deselected {
            background-color: #f8d7da !important; /* Cor vermelha ao desmarcar */
        }
        .weekend {
            background-color: #f8d7da; /* Finais de semana inicialmente vermelhos */
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

    <div id="datasResultado" class="day-container mt-4"></div>

    <!-- Bootstrap JS e dependências (Opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Função para formatar a data no padrão brasileiro (DD/MM/AAAA)
        function formatarDataBrasileira(data) {
            let dia = data.getDate().toString().padStart(2, '0');
            let mes = (data.getMonth() + 1).toString().padStart(2, '0'); // Meses vão de 0 a 11
            let ano = data.getFullYear();
            return `${dia}/${mes}/${ano}`;
        }

        // Função para obter o nome do dia da semana em português
        function obterNomeDiaSemana(data) {
            const diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            return diasSemana[data.getDay()];
        }

        function mostrarDatas() {
            let inicio = new Date(document.getElementById('data_inicio').value);
            let final = new Date(document.getElementById('data_final').value);
            let resultadoDiv = document.getElementById('datasResultado');
            resultadoDiv.innerHTML = ''; // Limpa resultados anteriores

            if (inicio > final) {
                alert('A data final não pode ser anterior à data de início.');
                return;
            }

            let currentDate = inicio;
            while (currentDate <= final) {
                let dayOfWeek = currentDate.getDay();
                let isWeekend = (dayOfWeek === 0 || dayOfWeek === 6); // Domingo ou Sábado
                let dateText = formatarDataBrasileira(currentDate);
                let dayName = obterNomeDiaSemana(currentDate);

                // Cria a caixa para cada dia
                let dayBox = document.createElement('div');
                dayBox.className = 'day-box ' + (isWeekend ? 'weekend deselected' : 'weekday') + ' form-check';

                // Cria o checkbox
                let checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.value = dateText;
                checkbox.id = 'checkbox-' + dateText;
                checkbox.className = 'form-check-input';
                checkbox.checked = !isWeekend; // Sábados e domingos não selecionados
                checkbox.style.display = 'none'; // Esconde o checkbox, mas ele continua funcional

                // Adiciona um evento para clicar no dia e alternar seleção
                dayBox.addEventListener('click', function() {
                    checkbox.checked = !checkbox.checked; // Alterna o estado do checkbox
                    if (!checkbox.checked) {
                        dayBox.classList.add('deselected'); // Adiciona a classe que muda o fundo para vermelho
                    } else {
                        dayBox.classList.remove('deselected'); // Remove a classe que muda o fundo
                    }
                });

                // Cria o label para o checkbox com o nome do dia e a data
                let labelDay = document.createElement('div');
                labelDay.textContent = dayName; // Nome do dia da semana
                labelDay.className = 'fw-bold';

                let labelDate = document.createElement('label');
                labelDate.htmlFor = checkbox.id;
                labelDate.textContent = dateText; // Data formatada (DD/MM/AAAA)
                labelDate.className = 'form-check-label';

                // Adiciona o checkbox e as labels à caixa
                dayBox.appendChild(labelDay); // Nome do dia
                dayBox.appendChild(labelDate); // Data
                dayBox.appendChild(checkbox); // Checkbox (oculto)
                resultadoDiv.appendChild(dayBox);

                currentDate.setDate(currentDate.getDate() + 1); // Próximo dia
            }
        }
    </script>
</body>
</html>
