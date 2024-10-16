document.addEventListener('DOMContentLoaded', () => {
    fetchTurmas(); // Carrega as turmas ao iniciar a página
    setInterval(fetchTurmas, 9000); // Atualiza as turmas a cada 60 segundos (60000 ms)
    setInterval(atualizarTempo, 1000); // Atualiza o relógio a cada segundo (1000 ms)
    setInterval(alternarPagina, 10000); // Alterna a página a cada 20 segundos (20000 ms)
});

let registrosPorPagina = 5;
let paginaAtual = 0;
let turmas = [];
let turmasFiltradas = [];

function fetchTurmas() {
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
    turmas = Array.from(turmasList).map(turma => ({
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
        status: turma.getElementsByTagName('status')[0]?.textContent || '',
    }));
    filtrarTurmasPorPeriodoAtual();
    mostrarRegistros(paginaAtual);
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
        td.colSpan = 7; // Número de colunas na tabela
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
            tr.innerHTML = `
                <td>${registro.nome_turma}</td>
                <td>${registro.nome_curso}</td>
                <td>${registro.nome_sala}</td>
                <td>${registro.nome_andar}</td>
                <td>${registro.nome_professor}</td>
                <td>${registro.horario_inicio}</td>
                <td>${registro.status === 'ATIVA' ? 'CONFIRMADO' : registro.status}</td>
            `;
            tbody.appendChild(tr);
        });
    }
}

function alternarPagina() {
    const totalPaginas = Math.ceil(turmasFiltradas.length / registrosPorPagina);

    if (totalPaginas > 0) {
        paginaAtual = (paginaAtual + 1) % totalPaginas;
        mostrarRegistros(paginaAtual);
    } else {
        console.warn("Nenhuma página a ser exibida.");
    }
}

const daysOfWeek = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
const months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro"];

function atualizarTempo() {
    const currentDateElement = document.getElementById('current-date');
    const currentTimeElement = document.getElementById('current-time');
    const currentDateTime = new Date();

    const dayOfWeek = daysOfWeek[currentDateTime.getDay()];
    const day = currentDateTime.getDate();
    const month = months[currentDateTime.getMonth()];
    const year = currentDateTime.getFullYear();

    let hours = currentDateTime.getHours();
    let minutes = currentDateTime.getMinutes();
    let seconds = currentDateTime.getSeconds();

    hours = (hours < 10 ? "0" : "") + hours;
    minutes = (minutes < 10 ? "0" : "") + minutes;
    seconds = (seconds < 10 ? "0" : "") + seconds;

    currentDateElement.textContent = `${dayOfWeek}, ${day} de ${month} de ${year}`;
    currentTimeElement.textContent = `${hours}:${minutes}`; // Adiciona segundos ao relógio
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

        // Verifica o período
        const [startHour, startMinute] = turma.horario_inicio.split(':').map(Number);
        const [endHour, endMinute] = turma.horario_final.split(':').map(Number);

        if (currentHour >= 6 && currentHour < 12) {
            // Manhã
            if (startHour >= 6 && startHour < 12 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute))) {
                return true;
            }
        } else if (currentHour >= 12 && currentHour < 18) {
            // Tarde
            if (startHour >= 12 && startHour < 18 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute))) {
                return true;
            }
        } else if (currentHour >= 18 && currentHour < 24) {
            // Noite
            if (startHour >= 18 && startHour < 24 && (endHour > currentHour || (endHour === currentHour && endMinute >= currentMinute))) {
                return true;
            }
        }
        return false;
    });

    paginaAtual = 0; // Reseta a página atual
    mostrarRegistros(paginaAtual);
}
