<?php

session_start();

if (isset($_SESSION['login'])) {
    if ($_SESSION['tipo_usuario'] == "COPED" || $_SESSION['tipo_usuario'] == "ADM") {
        // Permite o acesso
    } else {
        header('Location: ../form/menu.php');
        exit();
    }
} else {
    header('Location: ../form/login.php');
    exit();
}

// Inclui o arquivo de menu
include_once '../head/menu.php';
include_once "../bd/conn.php";

$pesquisa = $_POST['busca'] ?? '';
$professor_id = $_POST['professor_id'] ?? '';
$data_busca = $_POST['data_busca'] ?? ''; // Campo para a data

// Paginação
$pagina = $_GET['pagina'] ?? 1;
$quantidade_pg = 5;
$inicio = ($quantidade_pg * $pagina) - $quantidade_pg;

// Consulta para contar o total de registros
$result_agendamento = "SELECT COUNT(*) AS total FROM agendamento a 
                       JOIN usuarios u ON a.usuario_idUsuario = u.idUsuario 
                       JOIN unidade_curricular uc ON a.unidade_curricular_id = uc.idunidade_curricular 
                       JOIN turmas t ON a.turma_id = t.turma_id 
                       JOIN salas s ON a.sala_id = s.id_sala 
                       JOIN professores p ON a.professor_id = p.idProfessor
                       WHERE (u.nome_usuario LIKE '%$pesquisa%' 
                          OR a.data_inicio LIKE '%$pesquisa%' 
                          OR a.data_final LIKE '%$pesquisa%' 
                          OR uc.nome_unidade LIKE '%$pesquisa%' 
                          OR t.nome_turma LIKE '%$pesquisa%' 
                          OR s.nome LIKE '%$pesquisa%' 
                          OR p.nome LIKE '%$pesquisa%')
                          AND a.status = 'ATIVA'";

// Se foi fornecida uma data de busca, adiciona a condição de intervalo de data
if (!empty($data_busca)) {
    $result_agendamento .= " AND '$data_busca' BETWEEN a.data_inicio AND a.data_final";
}

if (!empty($professor_id)) {
    $result_agendamento .= " AND a.professor_id = '$professor_id'";
}

$consulta = mysqli_query($conn, $result_agendamento);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_agendamento = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_agendamento / $quantidade_pg);

// Consulta para buscar os agendamentos
$sql = "SELECT a.idAgendamento, a.data_inicio, a.data_final, a.horario_inicio, a.horario_fim, a.dias_aula,
               u.nome_usuario, uc.nome_unidade, t.nome_turma, s.nome, p.nome AS nome_professor, a.status
        FROM agendamento a 
        JOIN usuarios u ON a.usuario_idUsuario = u.idUsuario 
        JOIN unidade_curricular uc ON a.unidade_curricular_id = uc.idunidade_curricular 
        JOIN turmas t ON a.turma_id = t.turma_id 
        JOIN salas s ON a.sala_id = s.id_sala 
        JOIN professores p ON a.professor_id = p.idProfessor
        WHERE (u.nome_usuario LIKE CONCAT('%', '$pesquisa', '%') 
           OR a.data_inicio LIKE CONCAT('%', '$pesquisa', '%') 
           OR a.data_final LIKE CONCAT('%', '$pesquisa', '%') 
           OR uc.nome_unidade LIKE CONCAT('%', '$pesquisa', '%') 
           OR t.nome_turma LIKE CONCAT('%', '$pesquisa', '%') 
           OR s.nome LIKE CONCAT('%', '$pesquisa', '%') 
           OR p.nome LIKE CONCAT('%', '$pesquisa', '%'))
           AND a.status = 'ATIVA'";

// Se foi fornecida uma data de busca, adiciona a condição de intervalo de data
if (!empty($data_busca)) {
    $sql .= " AND '$data_busca' BETWEEN a.data_inicio AND a.data_final";
}

if (!empty($professor_id)) {
    $sql .= " AND a.professor_id = '$professor_id'";
}

$sql .= " LIMIT $inicio, $quantidade_pg";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Agendamentos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">AGENDAMENTOS</h1><br>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formAgendamentos.php" method="post" class="mb-4">
                            <div class="input-group input-group-sm" style="max-width: 300px; float: left; margin-right: 10px;">
                                <input type="search" class="form-control" placeholder="Pesquisar" id="pesquisar" name="busca">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div style="float: left; margin-right: 10px;">
                                <input type="date" class="form-control" id="data_busca" name="data_busca" value="<?= htmlspecialchars($data_busca); ?>">
                            </div>
                            <div style="float: left;">
                                <select class="form-select" id="professor_id" name="professor_id" required onchange="this.form.submit()">
                                    <option value="">Selecione um professor</option>
                                    <?php
                                    $query = "SELECT idProfessor, nome FROM professores ORDER BY nome";
                                    $result = mysqli_query($conn, $query);

                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $selected = ($row['idProfessor'] == $professor_id) ? 'selected' : '';
                                            echo "<option value='{$row['idProfessor']}' $selected>{$row['nome']}</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Nenhum professor disponível</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div style="clear: both;"></div>
                        </form>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="flex-grow-1">
                            <?php
                            if (isset($_GET['status'])) {
                                if ($_GET['status'] == 'success') {
                                    echo '<div id="alertBox" class="alert alert-success mb-0" style="display: inline-block" role="alert">Operação realizada com sucesso!</div>';
                                } else if ($_GET['status'] == 'error') {
                                    echo '<div id="alertBox" class="alert alert-danger mb-0" style="display: inline-block" role="alert">Erro ao realizar a operação</div>';
                                }
                            }
                            ?>
                        </div>

                        <script>
                            setTimeout(function() {
                                var alertBox = document.getElementById('alertBox');
                                if (alertBox) {
                                    alertBox.style.display = 'none';
                                }
                            }, 5000);
                        </script>

                        <div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Agendamento</button>
                        </div>
                    </div>

                    <!-- Tabela de agendamentos -->
                    <table class='table rounded-table'>
                        <thead>
                            <tr>
                                <th class="text-center">Código</th>
                                <th class="text-center">Data Início</th>
                                <th class="text-center">Data Fim</th>
                                <th class="text-center">Hora Início</th>
                                <th class="text-center">Hora Fim</th>
                                <th class="text-center">Agente</th>
                                <th class="text-center">Unidade Curricular</th>
                                <th class="text-center">Turma</th>
                                <th class="text-center">Sala</th>
                                <th class="text-center">Professor</th>
                                <th class="text-center">Dias de Aula</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($resultado) > 0) {
                                while ($row = mysqli_fetch_assoc($resultado)) {
                                    $data_inicio_br = date('d/m/Y', strtotime($row['data_inicio']));
                                    $horario_inicio_br = date('H:i', strtotime($row['horario_inicio']));
                                    $data_final_br = date('d/m/Y', strtotime($row['data_final']));
                                    $horario_fim_br = date('H:i', strtotime($row['horario_fim']));
                                    $dias_aula_br = str_replace(",", ", ", htmlspecialchars($row['dias_aula']));
                                    $status = htmlspecialchars($row['status']);
                            ?>
                                    <tr>
                                        <td class='text-center'><?= htmlspecialchars($row['idAgendamento']); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($data_inicio_br); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($data_final_br); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($horario_inicio_br); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($horario_fim_br); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($row['nome_usuario']); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($row['nome_unidade']); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($row['nome_turma']); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($row['nome']); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($row['nome_professor']); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($dias_aula_br); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($status); ?></td>
                                        <td class='text-center'>
                                            <div class='d-flex justify-content-center'>
                                                <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editAgendamento(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>
                                                <form action='../controls/cadastrarAgendamento.php' method='POST' style='display:inline-block;'>
                                                    <input type='hidden' name='idAgendamento' value='<?= htmlspecialchars($row['idAgendamento']); ?>'>
                                                    <input type='hidden' name='action' value='delete'>
                                                    <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir este agendamento?")'><i class='fas fa-times'></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan='13'>Nenhum agendamento encontrado</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($pagina > 1) ? 'formAgendamentos.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formAgendamentos.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                            <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formAgendamentos.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Modal para adicionar/editar agendamentos -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Agendamento</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <form id="agendamentoForm" action="../controls/cadastrarAgendamento.php" method="POST">
                                        <input type="hidden" id="idAgendamento" name="idAgendamento">
                                        <input type="hidden" id="action" name="action" value="add">

                                        <div class="mb-3">
                                            <label for="data_inicio" class="form-label">Data Início</label>
                                            <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="data_final" class="form-label">Data Fim</label>
                                            <input type="date" class="form-control" id="data_final" name="data_final" required>
                                        </div>


                                        <div class="mb-3">
                                            <label for="horario_inicio" class="form-label">Horário Início</label>
                                            <input type="time" class="form-control" id="horario_inicio" name="horario_inicio" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="horario_fim" class="form-label">Horário Fim</label>
                                            <input type="time" class="form-control" id="horario_fim" name="horario_fim" required>
                                        </div>

                                        <!-- Seção para selecionar os dias de aula -->
                                        <div class="mb-3">
                                            <label for="dias_aula">Dias de Aula</label>
                                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                <div>
                                                    <input type="checkbox" id="segunda" name="dias_aula[]" value="segunda">
                                                    <label for="segunda">Seg</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" id="terca" name="dias_aula[]" value="terca">
                                                    <label for="terca">Ter</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" id="quarta" name="dias_aula[]" value="quarta">
                                                    <label for="quarta">Qua</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" id="quinta" name="dias_aula[]" value="quinta">
                                                    <label for="quinta">Qui</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" id="sexta" name="dias_aula[]" value="sexta">
                                                    <label for="sexta">Sex</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" id="sabado" name="dias_aula[]" value="sabado">
                                                    <label for="sabado">Sáb</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="turma_id" class="form-label">Turma</label>
                                            <select class="form-select" id="turma_id" name="turma_id" required onchange="fetchUnidadesCurriculares()">
                                                <option value="">Selecione uma turma</option>
                                                <?php
                                                // Consulta para listar turmas
                                                $query = "SELECT turma_id, nome_turma FROM turmas order by nome_turma";
                                                $result = mysqli_query($conn, $query);

                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='{$row['turma_id']}'>{$row['nome_turma']}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>Nenhuma turma disponível</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="unidade_curricular_id" class="form-label">Unidade Curricular</label>
                                            <select class="form-select" id="unidade_curricular_id" name="unidade_curricular_id" required>
                                                <option value="">Selecione a turma primeiro</option>
                                                <!-- As opções serão carregadas dinamicamente via AJAX -->
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="usuario_idUsuario" class="form-label">Agente</label>
                                            <select class="form-select" id="usuario_idUsuario" name="usuario_idUsuario" required>
                                                <?php
                                                $query = "SELECT idUsuario, nome_usuario FROM usuarios u JOIN tipo_usuario tu ON u.tipo_usuario_id = tu.idTipo_usuario WHERE tu.tipo = 'COPED'";
                                                $result = mysqli_query($conn, $query);

                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='{$row['idUsuario']}'>{$row['nome_usuario']}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>Nenhum usuário disponível</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="sala_id" class="form-label">Sala</label>
                                            <select class="form-select" id="sala_id" name="sala_id" required>
                                                <?php
                                                $query = "SELECT id_sala, nome FROM salas";
                                                $result = mysqli_query($conn, $query);

                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='{$row['id_sala']}'>{$row['nome']}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>Nenhuma sala disponível</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="professor_id" class="form-label">Professor</label>
                                            <select class="form-select" id="professor_id" name="professor_id" required>
                                                <?php
                                                $query = "SELECT idProfessor, nome FROM professores";
                                                $result = mysqli_query($conn, $query);

                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='{$row['idProfessor']}'>{$row['nome']}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>Nenhum professor disponível</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="ATIVA">Ativa</option>
                                                <option value="CANCELADA">Cancelada</option>
                                                <option value="INATIVA">Inativa</option>
                                            </select>
                                        </div>

                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-primary" onclick="submitForm()">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script>
    function editAgendamento(data) {
        document.getElementById('idAgendamento').value = data.idAgendamento;
        document.getElementById('data_inicio').value = data.data_inicio;
        document.getElementById('horario_inicio').value = data.horario_inicio;
        document.getElementById('data_final').value = data.data_final;
        document.getElementById('horario_fim').value = data.horario_fim;

        // Marca os checkboxes dos dias de aula
        let diasAula = data.dias_aula.split(',');
        diasAula.forEach(dia => {
            document.getElementById(dia).checked = true;
        });

        let turmaSelect = document.getElementById('turma_id');
        for (let i = 0; i < turmaSelect.options.length; i++) {
            if (turmaSelect.options[i].text === data.nome_turma) {
                turmaSelect.selectedIndex = i;
                break;
            }
        }

        // Chama a função para carregar as unidades curriculares
        fetchUnidadesCurriculares(function() {
            let unidadeSelect = document.getElementById('unidade_curricular_id');
            for (let i = 0; i < unidadeSelect.options.length; i++) {
                if (unidadeSelect.options[i].text === data.nome_unidade) {
                    unidadeSelect.selectedIndex = i;
                    break;
                }
            }
        });

        let usuarioSelect = document.getElementById('usuario_idUsuario');
        for (let i = 0; i < usuarioSelect.options.length; i++) {
            if (usuarioSelect.options[i].text === data.nome_usuario) {
                usuarioSelect.selectedIndex = i;
                break;
            }
        }

        let salaSelect = document.getElementById('sala_id');
        for (let i = 0; i < salaSelect.options.length; i++) {
            if (salaSelect.options[i].text === data.nome) {
                salaSelect.selectedIndex = i;
                break;
            }
        }

        let professorSelect = document.getElementById('professor_id');
        for (let i = 0; i < professorSelect.options.length; i++) {
            if (professorSelect.options[i].text === data.nome_professor) {
                professorSelect.selectedIndex = i;
                break;
            }
        }

        document.getElementById('status').value = data.status;

        document.getElementById('action').value = 'update';
        document.querySelector('.modal-title').textContent = 'Editar Agendamento';
    }

    function clearForm() {
        document.getElementById('agendamentoForm').reset();
        document.getElementById('idAgendamento').value = '';
        document.getElementById('action').value = 'add';
        document.querySelector('.modal-title').textContent = 'Adicionar Novo Agendamento';
    }

    function submitForm() {
        document.getElementById('agendamentoForm').submit();
    }

    function fetchUnidadesCurriculares() {
        var turma_id = document.getElementById('turma_id').value;

        if (turma_id) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_unidades_curriculares.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    document.getElementById('unidade_curricular_id').innerHTML = this.responseText;
                }
            };
            xhr.send("turma_id=" + turma_id);
        } else {
            document.getElementById('unidade_curricular_id').innerHTML = '<option value="">Selecione a turma primeiro</option>';
        }
    }

    function fetchUnidadesCurriculares(callback) {
        var turma_id = document.getElementById('turma_id').value;

        if (turma_id) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_unidades_curriculares.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    document.getElementById('unidade_curricular_id').innerHTML = this.responseText;
                    if (callback) callback();
                }
            };
            xhr.send("turma_id=" + turma_id);
        } else {
            document.getElementById('unidade_curricular_id').innerHTML = '<option value="">Selecione a turma primeiro</option>';
            if (callback) callback();
        }
    }
</script>
</body>

<?php mysqli_close($conn); ?>

</html>
