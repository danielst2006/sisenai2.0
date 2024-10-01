<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Chaves</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }
            body {
                -webkit-print-color-adjust: exact;
                font-size: 10px;
            }
            .no-print {
                display: none;
            }
            .table th, .table td {
                padding: 4px;
                font-size: 10px;
            }
            .col-curso {
                width: 20%;
            }
            .table tbody tr {
                page-break-inside: avoid;
            }
            .print-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .print-header img {
                max-width: 200px;
            }
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 200px;
        }
        .table th, .table td {
            padding: 4px;
            font-size: 9px;
        }
        .col-curso {
            width: 30%;
        }
        .conflito {
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../imagens/senai-logo.png" alt="Logo">
        </div>
        <div class="print-header">
            <h1>Controle de Chaves</h1>
            <p>Data Selecionada: <?= isset($_GET['data']) ? date('d/m/Y', strtotime($_GET['data'])) : date('d/m/Y') ?></p>
            <p>Turno: <?= isset($_GET['turno']) ? ucfirst($_GET['turno']) : 'Matutino' ?></p>
        </div>
        <form method="GET" action="" class="no-print">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="turno">Escolha o turno:</label>
                    <select name="turno" id="turno" class="form-control">
                        <option value="matutino" <?= (isset($_GET['turno']) && $_GET['turno'] == 'matutino') ? 'selected' : '' ?>>Matutino (08:00 - 12:00)</option>
                        <option value="vespertino" <?= (isset($_GET['turno']) && $_GET['turno'] == 'vespertino') ? 'selected' : '' ?>>Vespertino (12:00 - 18:00)</option>
                        <option value="noturno" <?= (isset($_GET['turno']) && $_GET['turno'] == 'noturno') ? 'selected' : '' ?>>Noturno (18:00 - 00:00)</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="data">Escolha a data:</label>
                    <input type="date" name="data" id="data" class="form-control" value="<?= isset($_GET['data']) ? $_GET['data'] : date('Y-m-d') ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>SALA</th>
                    <th class="col-curso">CURSO</th>
                    <th>TURMA</th>
                    <th>DOCENTE</th>
                    <th>HORÁRIO RETIRADA</th>
                    <th>ASSINATURA</th>
                    <th>HORÁRIO DEVOLUÇÃO</th>
                    <th>ASSINATURA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../bd/conn.php'; // Inclui a conexão com o banco de dados

                if (!$conn) {
                    die("Falha na conexão: " . mysqli_connect_error());
                }

                $turno = isset($_GET['turno']) ? $_GET['turno'] : 'matutino';
                $dataEscolhida = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
                $diaSemana = date('l', strtotime($dataEscolhida));

                $diasSemana = [
                    'Monday' => 'segunda',
                    'Tuesday' => 'terca',
                    'Wednesday' => 'quarta',
                    'Thursday' => 'quinta',
                    'Friday' => 'sexta',
                    'Saturday' => 'sabado',
                    'Sunday' => 'domingo'
                ];

                $diaSelecionado = $diasSemana[$diaSemana] ?? 'segunda';

                $horarioInicio = '';
                $horarioFim = '';

                if ($turno == 'matutino') {
                    $horarioInicio = '08:00:00';
                    $horarioFim = '12:00:00';
                } elseif ($turno == 'vespertino') {
                    $horarioInicio = '12:00:00';
                    $horarioFim = '18:00:00';
                } elseif ($turno == 'noturno') {
                    $horarioInicio = '18:00:00';
                    $horarioFim = '23:59:59';
                }

        $sql = "SELECT a.idAgendamento, a.data_inicio, c.nome_curso AS nome_curso , a.data_final, a.horario_inicio AS horario_inicio, a.horario_fim AS horario_final, a.dias_aula,
                t.nome_turma AS nome_turma, s.nome AS nome_sala , p.nome AS nome_professor, a.status
         FROM salas s 
         LEFT JOIN agendamento a ON a.sala_id = s.id_sala AND TIME(a.horario_inicio) >= '$horarioInicio' AND TIME(a.horario_fim) <= '$horarioFim' AND a.dias_aula LIKE '%$diaSelecionado%' AND a.status LIKE '%ATIVA%'
         LEFT JOIN professores p ON a.professor_id = p.idProfessor
         LEFT JOIN turmas t ON a.turma_id = t.turma_id
         LEFT JOIN cursos c ON t.curso_id = c.curso_id";

                // $sql = "SELECT s.nome_sala, c.nome_curso, t.nome_turma, p.nome_professor, t.horario_inicio, t.horario_final
                //         FROM salas s
                //         LEFT JOIN turmas t ON s.sala_id = t.sala_id AND TIME(t.horario_inicio) >= '$horarioInicio' AND TIME(t.horario_final) <= '$horarioFim' AND t.dias_aula LIKE '%$diaSelecionado%'
                //         LEFT JOIN cursos c ON t.curso_id = c.curso_id
                //         LEFT JOIN professores p ON t.professor_id = p.professor_id";

                $result = mysqli_query($conn, $sql);

                $turmas = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $turmas[] = $row;
                }

                $conflitos = [];
                foreach ($turmas as $key => $turma) {
                    foreach ($turmas as $subkey => $subturma) {
                        if ($key != $subkey && $turma['nome_sala'] == $subturma['nome_sala'] &&
                            $turma['horario_inicio'] < $subturma['horario_final'] &&
                            $subturma['horario_inicio'] < $turma['horario_final']) {
                            $conflitos[$key] = true;
                            $conflitos[$subkey] = true;
                        }
                    }
                }

                foreach ($turmas as $key => $turma) {
                    $conflitoClass = isset($conflitos[$key]) ? 'conflito' : '';
                    echo "<tr class='$conflitoClass'>
                            <td>{$turma['nome_sala']}</td>
                            <td class='col-curso'>" . (!empty($turma['nome_curso']) ? $turma['nome_curso'] : '') . "</td>
                            <td>" . (!empty($turma['nome_turma']) ? $turma['nome_turma'] : '') . "</td>
                            <td>" . (!empty($turma['nome_professor']) ? $turma['nome_professor'] : '') . "</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
