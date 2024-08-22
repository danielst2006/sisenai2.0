<?php
// Inclui o arquivo de menu
include_once '../head/menu.php';
include_once "../bd/conn.php";


if (isset($_POST['busca'])) {
    $pesquisa = $_POST['busca'];
} else {
    $pesquisa = '';
}

// Paginação
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
$quantidade_pg = 5;
$inicio = ($quantidade_pg * $pagina) - $quantidade_pg;

// Consulta para contar o total de registros
$result_agendamento = "SELECT COUNT(*) AS total FROM agendamento a 
                       JOIN usuarios u ON a.usuario_idUsuario = u.idUsuario 
                       JOIN unidade_curricular uc ON a.unidade_curricular_id = uc.idunidade_curricular 
                       JOIN turmas t ON a.turma_id = t.turma_id 
                       JOIN salas s ON a.sala_id = s.id_sala 
                       JOIN professores p ON a.professor_id = p.idProfessor
                       WHERE u.nome_usuario LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_agendamento);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_agendamento = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_agendamento / $quantidade_pg);

// Consulta para buscar os agendamentos
$sql = "SELECT a.idAgendamento, a.data_inicio, a.data_final, u.nome_usuario, 
               uc.nome_unidade, t.nome_turma, s.nome, p.nome AS nome_professor
        FROM agendamento a 
        JOIN usuarios u ON a.usuario_idUsuario = u.idUsuario 
        JOIN unidade_curricular uc ON a.unidade_curricular_id = uc.idunidade_curricular 
        JOIN turmas t ON a.turma_id = t.turma_id 
        JOIN salas s ON a.sala_id = s.id_sala 
        JOIN professores p ON a.professor_id = p.idProfessor
        WHERE u.nome_usuario LIKE CONCAT('%', '$pesquisa', '%') 
           OR a.data_inicio LIKE CONCAT('%', '$pesquisa', '%') 
           OR a.data_final LIKE CONCAT('%', '$pesquisa', '%') 
           OR uc.nome_unidade LIKE CONCAT('%', '$pesquisa', '%') 
           OR t.nome_turma LIKE CONCAT('%', '$pesquisa', '%') 
           OR s.nome LIKE CONCAT('%', '$pesquisa', '%') 
           OR p.nome LIKE CONCAT('%', '$pesquisa', '%')";
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
                <h1 class="text-center">Agendamentos</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formAgendamentos.php" method="post" class="mb-4">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <input type="search" class="form-control" placeholder="Pesquisar" id="pesquisar" name="busca">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="flex-grow-1">
                                <?php
                                // Exibe uma mensagem de sucesso ou erro com base no parâmetro de status na URL
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
                                // Esconde a mensagem de alerta após 5 segundos
                                setTimeout(function() {
                                    var alertBox = document.getElementById('alertBox');
                                    if (alertBox) {
                                        alertBox.style.display = 'none';
                                    }
                                }, 5000); // 5000 ms = 5 segundos
                            </script>

                            <div>
                                <!-- Botão para abrir o modal de adição de novos agendamentos -->
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
                                    <th class="text-center">Agente</th>
                                    <th class="text-center">Unidade Curricular</th>
                                    <th class="text-center">Turma</th>
                                    <th class="text-center">Sala</th>
                                    <th class="text-center">Professor</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Verifica se há agendamentos e exibe cada um em uma linha da tabela
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                        // Converte a data de início e a data de término para o formato brasileiro
                                        $data_inicio_br = date('d/m/Y H:i:s', strtotime($row['data_inicio']));
                                        $data_final_br = date('d/m/Y H:i:s', strtotime($row['data_final']));
                                ?>
                                        <tr>
                                            <td class='text-center'><?= htmlspecialchars($row['idAgendamento']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($data_inicio_br); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($data_final_br); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_usuario']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_unidade']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_turma']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_professor']); ?></td>
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <!-- Botão de edição -->
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editAgendamento(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarAgendamento.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='idAgendamento' value='<?= htmlspecialchars($row['idAgendamento']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>

                                                        <!-- Botão de exclusão com confirmação -->
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir este agendamento?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- Mensagem quando não há agendamentos -->
                                    <tr>
                                        <td colspan='9'>Nenhum agendamento encontrado</td>
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
                                                <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="data_final" class="form-label">Data Fim</label>
                                                <input type="datetime-local" class="form-control" id="data_final" name="data_final" required>
                                            </div>

                                            <script>
                                                document.getElementById('data_final').addEventListener('change', function() {
                                                    var dataInicio = document.getElementById('data_inicio').value;
                                                    var dataFinal = this.value;

                                                    if (dataInicio && dataFinal) {
                                                        if (new Date(dataInicio) > new Date(dataFinal)) {
                                                            alert('A data de início não pode ser posterior à data de fim.');
                                                            this.value = ''; // Limpa a data de fim se a condição for inválida
                                                        }
                                                    }
                                                });
                                            </script>

                                            <div class="mb-3">
                                                <label for="usuario_idUsuario" class="form-label">Usuário</label>
                                                <select class="form-select" id="usuario_idUsuario" name="usuario_idUsuario" required>
                                                    <?php
                                                    // Consulta para listar usuários do tipo COPED
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
                                                <label for="unidade_curricular_id" class="form-label">Unidade Curricular</label>
                                                <select class="form-select" id="unidade_curricular_id" name="unidade_curricular_id" required>
                                                    <?php
                                                    // Consulta para listar unidades curriculares
                                                    $query = "SELECT idunidade_curricular, nome_unidade FROM unidade_curricular";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['idunidade_curricular']}'>{$row['nome_unidade']}</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>Nenhuma unidade curricular disponível</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="turma_id" class="form-label">Turma</label>
                                                <select class="form-select" id="turma_id" name="turma_id" required>
                                                    <?php
                                                    // Consulta para listar turmas
                                                    $query = "SELECT turma_id, nome_turma FROM turmas";
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
                                                <label for="sala_id" class="form-label">Sala</label>
                                                <select class="form-select" id="sala_id" name="sala_id" required>
                                                    <?php
                                                    // Consulta para listar salas
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
                                                    // Consulta para listar professores
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
        // Função para preencher o formulário no modal para edição
        function editAgendamento(data) {
            document.getElementById('idAgendamento').value = data.idAgendamento;
            document.getElementById('data_inicio').value = data.data_inicio; // Insere o valor original
            document.getElementById('data_final').value = data.data_final; // Insere o valor original
            // document.getElementById('usuario_idUsuario').value = data.usuario_idUsuario;
            // document.getElementById('unidade_curricular_id').value = data.unidade_curricular_id;
            // document.getElementById('turma_id').value = data.turma_id;
            // document.getElementById('sala_id').value = data.sala_id;
            // document.getElementById('professor_id').value = data.professor_id;

            let usuarioSelect = document.getElementById('usuario_idUsuario');

            // Percorre as opções do select e define a que corresponde ao nome do curso
            for (let i = 0; i < usuarioSelect.options.length; i++) {
                if (usuarioSelect.options[i].text === data.nome_usuario) {
                    usuarioSelect.selectedIndex = i;
                    break;
                }
            }


            let unidadeSelect = document.getElementById('unidade_idUsuario');

            // Percorre as opções do select e define a que corresponde ao nome do curso
            for (let i = 0; i < unidadeSelect.options.length; i++) {
                if (unidadeSelect.options[i].text === data.nome_unidade) {
                    unidadeSelect.selectedIndex = i;
                    break;
                }
            }

            let turmaSelect = document.getElementById('turma_idUsuario');

            // Percorre as opções do select e define a que corresponde ao nome do curso
            for (let i = 0; i < turmaSelect.options.length; i++) {
                if (turmaSelect.options[i].text === data.nome_turma) {
                    turmaSelect.selectedIndex = i;
                    break;
                }
            }

            let salaSelect = document.getElementById('sala_idUsuario');

            // Percorre as opções do select e define a que corresponde ao nome do curso
            for (let i = 0; i < salaSelect.options.length; i++) {
                if (salaSelect.options[i].text === data.nome) {
                    salaSelect.selectedIndex = i;
                    break;
                }
            }

            let professorSelect = document.getElementById('professor_idUsuario');

            // Percorre as opções do select e define a que corresponde ao nome do curso
            for (let i = 0; i < professorSelect.options.length; i++) {
                if (professorSelect.options[i].text === data.nome) {
                    professorSelect.selectedIndex = i;
                    break;
                }
            }



            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Agendamento';


        }


        // Função para limpar o formulário no modal para adicionar novos agendamentos
        function clearForm() {
            document.getElementById('agendamentoForm').reset();
            document.getElementById('idAgendamento').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Novo Agendamento';
        }

        // Função para submeter o formulário
        function submitForm() {
            document.getElementById('agendamentoForm').submit();
        }
    </script>
</body>

</html>