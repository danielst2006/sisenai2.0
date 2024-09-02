<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airport Flight Display</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Estilo de fonte semelhante ao de painéis de aeroporto */
            background-color: #1c2833;
            color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .airport-display {
            display: flex;
            width: 80%;
            max-width: 1200px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            background: rgba(0, 0, 0, 0.7); /* Fundo preto translúcido */
        }

        .column {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        .column div {
            padding: 15px;
            text-align: center;
            margin-bottom: 10px; /* Margem vertical entre as linhas */
            flex-grow: 1; /* Faz com que as linhas cresçam para ocupar o espaço vertical */
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(44, 62, 80, 0.3); /* Fundo translúcido para linhas */
            border-radius: 5px;
            transition: background 0.3s ease; /* Transição suave ao passar o mouse */
            color: #ecf0f1;
            font-size: 1.2em;
        }

        .column .header {
            font-weight: bold;
            background: rgba(41, 128, 185, 0.9); /* Fundo azul translúcido para o cabeçalho */
            color: #ecf0f1;
            margin-bottom: 10px;
            flex-grow: 0; /* Mantém o cabeçalho em tamanho fixo */
            border-radius: 5px;
            text-transform: uppercase; /* Letras maiúsculas */
            letter-spacing: 2px; /* Espaçamento entre letras */
        }

        .column .row {
            background: rgba(52, 73, 94, 0.5);
            color: #ecf0f1;
            border-radius: 5px;
        }

        .column .row:last-child {
            margin-bottom: 0;
        }

        .column .row:nth-child(even) {
            background: rgba(52, 73, 94, 0.7);
        }

        .column div:hover {
            background: rgba(52, 152, 219, 0.5); /* Efeito ao passar o mouse */
        }
    </style>
</head>
<body>
    <div class="airport-display">
        <div class="column">
            <div class="header">Flight</div>
            <div class="row">AA123</div>
            <div class="row">BA456</div>
            <div class="row">AF789</div>
            <div class="row">LH987</div>
            <div class="row">IB234</div>
        </div>
        <div class="column">
            <div class="header">Destination</div>
            <div class="row">New York</div>
            <div class="row">London</div>
            <div class="row">Paris</div>
            <div class="row">Berlin</div>
            <div class="row">Madrid</div>
        </div>
        <div class="column">
            <div class="header">Time</div>
            <div class="row">14:30</div>
            <div class="row">15:45</div>
            <div class="row">16:00</div>
            <div class="row">16:30</div>
            <div class="row">17:15</div>
        </div>
        <div class="column">
            <div class="header">Status</div>
            <div class="row">On Time</div>
            <div class="row">Delayed</div>
            <div class="row">Boarding</div>
            <div class="row">Canceled</div>
            <div class="row">On Time</div>
        </div>
    </div>
</body>
</html>
