<?php
session_start();
if (isset($_SESSION['login'])) {
    if ($_SESSION['tipo_usuario'] == "COPED" || $_SESSION['tipo_usuario'] == "ADM" || $_SESSION['tipo_usuario'] == "GESTOR") {
        // Usuário autorizado
    } else {
        header('Location: ../form/menu.php');
        exit;
    }
} else {
    header('Location: ../form/login.php');
    exit;
}

// Inclui o arquivo de menu
include_once '../head/menu.php';
include_once "../bd/conn.php";

// Verifica se foi realizada uma pesquisa
$pesquisa = isset($_POST['busca']) ? $_POST['busca'] : '';

// Paginação
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
$quantidade_pg = 5;
$inicio = ($quantidade_pg * $pagina) - $quantidade_pg;

// Consulta para contar o total de registros
$result_disponibilidade = "SELECT COUNT(*) AS total FROM disponibilidade_salas ds 
                            JOIN salas s ON ds.sala_id = s.id_sala 
                            WHERE s.nome LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_disponibilidade);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_disponibilidade = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_disponibilidade / $quantidade_pg);

// Consulta para buscar as disponibilidades
$sql = "SELECT ds.idDisponibilidade, s.nome AS sala, ds.data_inicio, ds.horario_inicio, ds.data_final, ds.horario_fim, ds.sala_id 
        FROM disponibilidade_salas ds 
        JOIN salas s ON ds.sala_id = s.id_sala 
        WHERE ds.idDisponibilidade LIKE CONCAT('%', '$pesquisa', '%') 
           OR ds.data_inicio LIKE CONCAT('%', '$pesquisa', '%') 
           OR ds.horario_inicio LIKE CONCAT('%', '$pesquisa', '%') 
           OR ds.data_final LIKE CONCAT('%', '$pesquisa', '%') 
           OR ds.horario_fim LIKE CONCAT('%', '$pesquisa', '%') 
           OR s.nome LIKE CONCAT('%', '$pesquisa', '%') 
        LIMIT $inicio, $quantidade_pg";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Disponibilidade de Salas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 10%;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 20%;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 15%;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 15%;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 15%;
        }

        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 15%;
        }

        .table th:nth-child(7),
        .table td:nth-child(7) {
            width: 10%;
        }

        .action-button {
            width: 30px;
            height: 30px;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

<body class="bg-light text-dark">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Cadastro de Disponibilidade de Salas</h1>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Formulário de Pesquisa -->
            <form class="d-flex" action="formDisponibilidadeSalas.php" method="POST">
                <input class="form-control me-2" type="search" name="busca" placeholder="Pesquisar por nome, área ou ano" value="<?= htmlspecialchars($pesquisa); ?>">
                <button class="btn btn-outline-success" type="submit">Pesquisar</button>
            </form>

            <!-- Botão para abrir o modal de adição de nova disponibilidade -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Disponibilidade</button>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Código</th>
                    <th class="text-center">Sala</th>
                    <th class="text-center">Data Início</th>
                    <th class="text-center">Data Fim</th>
                    <th class="text-center">Horário Início</th>
                    <th class="text-center">Horário Fim</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                ?>
                        <tr>
                            <td class='text-center'><?= htmlspecialchars($row['idDisponibilidade']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['sala']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['data_inicio']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['data_final']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['horario_inicio']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['horario_fim']); ?></td>
                            <td class='text-center'>
                                <div class="d-flex justify-content-center">
                                    <!-- Botão de edição -->
                                    <button class="btn btn-warning me-2 action-button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick='editDisponibilidade(<?= json_encode($row); ?>)'>
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Formulário de exclusão -->
                                    <form action="../controls/cadastrarDisponibilidade.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="idDisponibilidade" value="<?= htmlspecialchars($row['idDisponibilidade']); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger action-button" onclick="return confirm('Tem certeza que deseja excluir esta disponibilidade?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="text-center">Nenhuma disponibilidade encontrada</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina > 1) ? 'formDisponibilidadeSalas.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                    <li class="page-item <?= ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formDisponibilidadeSalas.php?pagina=<?= $i; ?>"><?= $i; ?></a></li>
                <?php } ?>
                <li class="page-item <?= ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina < $num_pagina) ? 'formDisponibilidadeSalas.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal para adicionar/editar disponibilidades -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Disponibilidade</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="disponibilidadeForm" action="../controls/cadastrarDisponibilidade.php" method="POST">
                            <input type="hidden" id="idDisponibilidade" name="idDisponibilidade">
                            <input type="hidden" id="action" name="action" value="add">

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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script>
        function editDisponibilidade(data) {
            document.getElementById('idDisponibilidade').value = data.idDisponibilidade;
            document.getElementById('sala_id').value = data.sala_id;
            document.getElementById('data_inicio').value = data.data_inicio;
            document.getElementById('horario_inicio').value = data.horario_inicio;
            document.getElementById('data_final').value = data.data_final;
            document.getElementById('horario_fim').value = data.horario_fim;
            document.getElementById('action').value = 'edit';
            document.querySelector('.modal-title').textContent = 'Editar Disponibilidade';
        }

        function clearForm() {
            document.getElementById('disponibilidadeForm').reset();
            document.getElementById('idDisponibilidade').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Nova Disponibilidade';
        }

        function submitForm() {
            document.getElementById('disponibilidadeForm').submit();
        }
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>
