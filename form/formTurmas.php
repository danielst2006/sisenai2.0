<?php

session_start();

if(isset($_SESSION['login'])){
    if($_SESSION['tipo_usuario'] == "COPED" || $_SESSION['tipo_usuario'] == "ADM"){

    } else {
        header('Location: ../form/menu.php');
    }
} else {
    header('Location: ../form/login.php');
}

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
$result_turma = "SELECT COUNT(*) AS total FROM turmas t 
                 JOIN cursos c ON t.curso_id = c.curso_id 
                 WHERE t.nome_turma LIKE '%$pesquisa%' 
                    OR c.nome_curso LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_turma);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_turma = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_turma / $quantidade_pg);

// Consulta para buscar as turmas
$sql = "SELECT t.turma_id, t.nome_turma, t.data_inicio, t.data_fim, t.horario_inicio, t.horario_final, t.status, c.nome_curso 
FROM turmas t 
JOIN cursos c ON t.curso_id = c.curso_id 
WHERE t.turma_id LIKE CONCAT('%', '$pesquisa', '%') 
   OR t.nome_turma LIKE CONCAT('%', '$pesquisa', '%') 
   OR t.data_inicio LIKE CONCAT('%', '$pesquisa', '%') 
   OR t.data_fim LIKE CONCAT('%', '$pesquisa', '%') 
   OR c.nome_curso LIKE CONCAT('%', '$pesquisa', '%')";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Turmas</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Turmas</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formTurmas.php" method="post" class="mb-4">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <input type="search" class="form-control" placeholder="Pesquisar" id="pesquisar" name="busca">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1-11 0" />
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
                                <!-- Botão para abrir o modal de adição de novas turmas -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Turma</button>
                            </div>
                        </div>

                        <!-- Tabela de turmas -->
                        <table class='table rounded-table'>
                            <thead>
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Nome da Turma</th>
                                    <th class="text-center">Data Início</th>
                                    <th class="text-center">Data Fim</th>
                                    <th class="text-center">Horário Início</th>
                                    <th class="text-center">Horário Final</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Curso</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Verifica se há turmas e exibe cada uma em uma linha da tabela
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                        // Converte as datas para o formato brasileiro
                                        $data_inicio = date("d/m/Y", strtotime($row['data_inicio']));
                                        $data_fim = date("d/m/Y", strtotime($row['data_fim']));
                                ?>
                                        <tr>
                                            <td class='text-center'><?= htmlspecialchars($row['turma_id']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_turma']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($data_inicio); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($data_fim); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['horario_inicio']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['horario_final']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['status']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_curso']); ?></td>
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <!-- Botão de edição -->
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editTurma(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarTurmas.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='turma_id' value='<?= htmlspecialchars($row['turma_id']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>

                                                        <!-- Botão de exclusão com confirmação -->
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir esta turma?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- Mensagem quando não há turmas -->
                                    <tr>
                                        <td colspan='10'>Nenhuma turma encontrada</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formTurmas.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formTurmas.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formTurmas.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Modal para adicionar/editar turmas -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Turma</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="turmaForm" action="../controls/cadastrarTurmas.php" method="POST">
                                            <input type="hidden" id="turma_id" name="turma_id">
                                            <input type="hidden" id="action" name="action" value="add">

                                            <div class="mb-3">
                                                <label for="nome_turma" class="form-label">Nome da Turma</label>
                                                <input type="text" class="form-control" id="nome_turma" name="nome_turma" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="data_inicio" class="form-label">Data Início</label>
                                                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="data_fim" class="form-label">Data Fim</label>
                                                <input type="date" class="form-control" id="data_fim" name="data_fim" required>
                                            </div>

                                            <script>
                                                function validarDatas() {
                                                    var dataInicio = document.getElementById('data_inicio').value;
                                                    var dataFim = document.getElementById('data_fim').value;

                                                    if (dataInicio && dataFim) {
                                                        if (new Date(dataInicio) > new Date(dataFim)) {
                                                            alert('A data de início não pode ser posterior à data de fim.');
                                                            document.getElementById('data_fim').value = ''; // Limpa o campo de data fim se for inválido
                                                        }
                                                    }
                                                }

                                                document.getElementById('data_fim').addEventListener('change', validarDatas);
                                            </script>

                                            <div class="mb-3">
                                                <label for="horario_inicio" class="form-label">Horário Início</label>
                                                <input type="time" class="form-control" id="horario_inicio" name="horario_inicio" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="horario_final" class="form-label">Horário Final</label>
                                                <input type="time" class="form-control" id="horario_final" name="horario_final" required>
                                            </div>

                                            <script>
                                                function validarHorarios() {
                                                    var horarioInicio = document.getElementById('horario_inicio').value;
                                                    var horarioFim = document.getElementById('horario_final').value;

                                                    if (horarioInicio && horarioFim) {
                                                        if (horarioInicio > horarioFim) {
                                                            alert('O horário de início não pode ser posterior ao horário de fim.');
                                                            document.getElementById('horario_final').value = ''; // Limpa o campo de horário final se for inválido
                                                        }
                                                    }
                                                }

                                                document.getElementById('horario_final').addEventListener('change', validarHorarios);
                                            </script>

                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="ATIVA">Ativa</option>
                                                    <option value="CANCELADA">Cancelada</option>
                                                    <option value="INATIVA">Inativa</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="curso_id" class="form-label">Curso</label>
                                                <select class="form-select" id="curso_id" name="curso_id" required>
                                                    <?php
                                                    // Consulta para listar cursos
                                                    $query = "SELECT curso_id, nome_curso FROM cursos";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['curso_id']}'>{$row['nome_curso']}</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>Nenhum curso disponível</option>";
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
        function editTurma(data) {
            document.getElementById('turma_id').value = data.turma_id;
            document.getElementById('nome_turma').value = data.nome_turma;
            document.getElementById('data_inicio').value = data.data_inicio;
            document.getElementById('data_fim').value = data.data_fim;
            document.getElementById('horario_inicio').value = data.horario_inicio;
            document.getElementById('horario_final').value = data.horario_final;
            document.getElementById('status').value = data.status;

            let cursoSelect = document.getElementById('curso_id');

            // Percorre as opções do select e define a que corresponde ao nome do curso
            for (let i = 0; i < cursoSelect.options.length; i++) {
                if (cursoSelect.options[i].text === data.nome_curso) {
                    cursoSelect.selectedIndex = i;
                    break;
                }
            }

            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Turma';
        }


        // Função para limpar o formulário no modal para adicionar novas turmas
        function clearForm() {
            document.getElementById('turmaForm').reset();
            document.getElementById('turma_id').value = '';
            document.getElementById('status').value = 'ATIVA'; // Define valor padrão para status
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Nova Turma';
        }

        // Função para submeter o formulário
        function submitForm() {
            document.getElementById('turmaForm').submit();
        }
    </script>
</body>

</html>