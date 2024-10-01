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
                <div id="current-date"></div>
                <div id="current-time"></div>
                <tbody id="turmas-body">

                    <!-- Conteúdo da tabela de turmas será inserido aqui via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            carregarTurmas();
        });

        let registrosPorPagina = 20;
        let paginaAtual = 0;
        let turmas = [];
        let turmasFiltradas = [];

        // Função para carregar as turmas
        function carregarTurmas() {
            fetch('load_turmas.php')
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const xmlDoc = parser.parseFromString(data, "application/xml");
                    const errorNode = xmlDoc.querySelector('parsererror');
                    if (errorNode) {
                        console.error('Erro ao analisar XML:', errorNode.textContent);
                    } else {
                        iniciarTabela(xmlDoc);
                    }
                })
                .catch(error => console.error('Erro ao buscar dados:', error));
        }

        function iniciarTabela(xmlDoc) {
            const turmasList = xmlDoc.getElementsByTagName('turma');
            turmas = Array.from(turmasList).map(turma => {
                const idAgendamento = turma.getElementsByTagName('idAgendamento')[0]?.textContent || '';
                return {
                    idAgendamento: idAgendamento,
                    nome_turma: turma.getElementsByTagName('nome_turma')[0]?.textContent || '',
                    nome_curso: turma.getElementsByTagName('nome_curso')[0]?.textContent || '',
                    nome_sala: turma.getElementsByTagName('nome_sala')[0]?.textContent || '',
                    nome_andar: turma.getElementsByTagName('andar')[0]?.textContent || '',
                    nome_professor: turma.getElementsByTagName('nome_professor')[0]?.textContent || '',
                    horario_inicio: turma.getElementsByTagName('horario_inicio')[0]?.textContent || '',
                    horario_final: turma.getElementsByTagName('horario_final')[0]?.textContent || '',
                    data_inicio: turma.getElementsByTagName('data_inicio')[0]?.textContent || '',
                    data_fim: turma.getElementsByTagName('data_fim')[0]?.textContent || '',
                    dias_aula: turma.getElementsByTagName('dias_aula')[0]?.textContent || '',
                    status: turma.getElementsByTagName('status')[0]?.textContent || ''
                };
            });
            filtrarTurmasPorPeriodoAtual();
            mostrarRegistros(paginaAtual);
            setInterval(alternarPagina, 20000);
            setInterval(atualizarTempo, 2000);
        }

        function mostrarRegistros(pagina) {
            const tbody = document.getElementById('turmas-body');
            tbody.innerHTML = ''; // Limpa registros existentes

            const inicio = pagina * registrosPorPagina;
            const fim = inicio + registrosPorPagina;
            const registros = turmasFiltradas.slice(inicio, fim);

            if (registros.length === 0) {
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 8; // Número de colunas na tabela (ajustado para incluir os botões)
                td.textContent = 'INTERVALO';
                td.style.textAlign = 'center';
                tr.appendChild(td);
                tbody.appendChild(tr);
            } else {
                registros.forEach(registro => {
                    const tr = document.createElement('tr');
                    if (registro.status === 'ATIVA') {
                        tr.classList.add('linha-confirmada');
                    } else if (registro.status === 'CANCELADA') {
                        tr.classList.add('linha-cancelada');
                    }

                    const statusColor = registro.status === 'ATIVA' ? 'red' : 'green';
                    const statusButtonText = registro.status === 'ATIVA' ? 'Cancelar' : 'Ativar';
                    const newStatus = registro.status === 'ATIVA' ? 'CANCELADA' : 'ATIVA';

                    tr.innerHTML = `
                        <td>${registro.nome_turma}</td>
                        <td>${registro.nome_curso}</td>
                        <td>${registro.nome_sala}</td>
                        <td>${registro.nome_andar}</td>
                        <td>${registro.nome_professor}</td>
                        <td>${registro.horario_inicio}</td>
                        <td style="color: ${statusColor}; font-weight: bold;" class="status-cell">${registro.status}</td>
                        <td><button class="status-button" style="background-color: ${statusColor}; color: white;" onclick="atualizarStatus(event, '${registro.idAgendamento}', '${newStatus}', this)">${statusButtonText}</button></td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        }

        function alternarPagina() {
            const totalPaginas = Math.ceil(turmasFiltradas.length / registrosPorPagina);
            paginaAtual = (paginaAtual + 1) % totalPaginas;
            mostrarRegistros(paginaAtual);
        }

        function atualizarTempo() {
            const currentDateElement = document.getElementById('current-date');
            const currentTimeElement = document.getElementById('current-time');

            if (currentDateElement && currentTimeElement) {
                const currentDateTime = new Date();

                const daysOfWeek = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
                const months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

                const dayOfWeek = daysOfWeek[currentDateTime.getDay()];
                const day = currentDateTime.getDate();
                const month = months[currentDateTime.getMonth()];
                const year = currentDateTime.getFullYear();

                let hours = currentDateTime.getHours();
                let minutes = currentDateTime.getMinutes();
                hours = (hours < 10 ? "0" : "") + hours;
                minutes = (minutes < 10 ? "0" : "") + minutes;

                currentDateElement.textContent = `${dayOfWeek}, ${day} de ${month} de ${year}`;
                currentTimeElement.textContent = `${hours}:${minutes}`;
            } else {
                console.error('Elementos current-date ou current-time não encontrados no DOM.');
            }
        }

        function atualizarStatus(event, idAgendamento, novoStatus, buttonElement) {
    // Prevenir o comportamento padrão do botão
    event.preventDefault();

    // Verifica se o idAgendamento é válido
    if (!idAgendamento || isNaN(parseInt(idAgendamento))) {
        console.error('idAgendamento inválido:', idAgendamento);
        return;
    }

    console.log('Enviando dados para o PHP: idAgendamento:', idAgendamento, 'Novo Status:', novoStatus);

    // Faz a requisição para atualizar o status
    fetch('atualizar_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ idAgendamento: idAgendamento, status: novoStatus }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            console.log('Status atualizado com sucesso');
        } else {
            console.error('Erro ao atualizar status:', data.mensagem);
        }

        // Força a atualização da página imediatamente após o clique no botão
        window.location.reload(); // Recarrega a página
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        // Recarrega a página mesmo em caso de erro
        window.location.reload();
    });
}

        function filtrarTurmasPorPeriodoAtual() {
            const today = new Date();
            const todayFormatted = today.toISOString().split('T')[0];

            const dayOfWeekMap = {
                0: 'domingo',
                1: 'segunda',
                2: 'terca',
                3: 'quarta',
                4: 'quinta',
                5: 'sexta',
                6: 'sabado'
            };
            const dayOfWeek = dayOfWeekMap[today.getDay()];

            const currentHour = today.getHours();
            const currentMinute = today.getMinutes();

            turmasFiltradas = turmas.filter(turma => {
                const startDate = turma.data_inicio;
                const endDate = turma.data_fim;

                const isWithinDateRange = (startDate <= todayFormatted && endDate >= todayFormatted);

                const diasAulaArray = turma.dias_aula.split(',').map(dia => dia.trim().toLowerCase());

                const isTodayAula = diasAulaArray.includes(dayOfWeek);

                if (!isWithinDateRange || !isTodayAula) {
                    return false;
                }

                const [startHour, startMinute] = turma.horario_inicio.split(':').map(Number);
                const [endHour, endMinute] = turma.horario_final.split(':').map(Number);

                if (currentHour >= 6 && currentHour < 12) {
                    return startHour >= 6 && startHour < 12 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute));
                } else if (currentHour >= 12 && currentHour < 18) {
                    return startHour >= 12 && startHour < 18 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute));
                } else if (currentHour >= 18 && currentHour < 24) {
                    return startHour >= 18 && startHour < 24 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute));
                }
                return false;
            });

            paginaAtual = 0;
            mostrarRegistros(paginaAtual);
        }
    </script>
</body>

</html>
