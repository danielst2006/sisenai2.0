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
            // Aplica a classe CSS com base no status
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

    // Verifica se os elementos existem antes de tentar atualizar o texto
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

            // Atualiza o texto e a cor do botão diretamente
            const novaCor = novoStatus === 'ATIVA' ? 'red' : 'green';
            const novoTexto = novoStatus === 'ATIVA' ? 'Cancelar' : 'Ativar';
            const proximoStatus = novoStatus === 'ATIVA' ? 'CANCELADA' : 'ATIVA';

            // Atualiza o estilo e o texto do botão
            buttonElement.style.backgroundColor = novaCor;
            buttonElement.textContent = novoTexto;

            // Atualiza o status na célula correspondente
            const statusCell = buttonElement.closest('tr').querySelector('.status-cell');
            statusCell.textContent = novoStatus;
            statusCell.style.color = novaCor;

            // Atualiza a ação do botão para a próxima alteração
            buttonElement.setAttribute('onclick', `atualizarStatus(event, '${idAgendamento}', '${proximoStatus}', this)`);
        } else {
            console.error('Erro ao atualizar status:', data.mensagem);
        }
    })
    .catch(error => console.error('Erro na requisição:', error));
}


function filtrarTurmasPorPeriodoAtual() {
    const today = new Date();
    const todayFormatted = today.toISOString().split('T')[0]; // Formata a data atual como 'YYYY-MM-DD'

    const dayOfWeekMap = {
        0: 'domingo',
        1: 'segunda',
        2: 'terca',
        3: 'quarta',
        4: 'quinta',
        5: 'sexta',
        6: 'sabado'
    };
    const dayOfWeek = dayOfWeekMap[today.getDay()]; // Obtém o dia da semana em português

    const currentHour = today.getHours();
    const currentMinute = today.getMinutes();

    turmasFiltradas = turmas.filter(turma => {
        const startDate = turma.data_inicio;
        const endDate = turma.data_fim;

        const isWithinDateRange = (startDate <= todayFormatted && endDate >= todayFormatted); // Verifica se a data atual está dentro do intervalo de datas

        const diasAulaArray = turma.dias_aula.split(',').map(dia => dia.trim().toLowerCase());

        const isTodayAula = diasAulaArray.includes(dayOfWeek);

        if (!isWithinDateRange || !isTodayAula) {
            return false;
        }

        const [startHour, startMinute] = turma.horario_inicio.split(':').map(Number);
        const [endHour, endMinute] = turma.horario_final.split(':').map(Number);

        if (currentHour >= 6 && currentHour < 12) {
            // Manhã
            return startHour >= 6 && startHour < 12 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute));
        } else if (currentHour >= 12 && currentHour < 18) {
            // Tarde
            return startHour >= 12 && startHour < 18 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute));
        } else if (currentHour >= 18 && currentHour < 24) {
            // Noite
            return startHour >= 18 && startHour < 24 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute));
        }
        return false;
    });

    paginaAtual = 0; // Reseta a página atual
    mostrarRegistros(paginaAtual);
}
