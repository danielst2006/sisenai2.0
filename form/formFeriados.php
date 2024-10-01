<?php
session_start();
if (isset($_SESSION['login'])) {
    if ($_SESSION['tipo_usuario'] != "COPED" && $_SESSION['tipo_usuario'] != "ADM") {
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

$pesquisa = isset($_POST['busca']) ? mysqli_real_escape_string($conn, $_POST['busca']) : '';

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina = $pagina > 0 ? $pagina : 1; // Garante que a página seja um número positivo
$quantidade_pg = 5;
$inicio = ($pagina - 1) * $quantidade_pg;

// Consulta para contar o total de registros
$result_curso = "SELECT COUNT(*) AS total FROM feriados WHERE nome LIKE '%$pesquisa%' OR dia_feriado LIKE '%$pesquisa%' OR tipo LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_curso);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_curso = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_curso / $quantidade_pg);

// Consulta para buscar os feriados com paginação
$sql = "SELECT idFeriados, nome, dia_feriado, tipo 
        FROM feriados 
        WHERE nome LIKE '%$pesquisa%' OR dia_feriado LIKE '%$pesquisa%' OR tipo LIKE '%$pesquisa%'
        LIMIT $inicio, $quantidade_pg";
$resultado = mysqli_query($conn, $sql);

if ($resultado === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Feriados</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 10%;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 35%;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 20%;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 20%;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 15%;
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
        <h1 class="text-center mb-4">Cadastro de Feriados</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Formulário de Pesquisa -->
            <form class="d-flex" action="formFeriados.php" method="POST">
                <input class="form-control me-2" type="search" name="busca" placeholder="Pesquisar por nome, dia ou tipo" value="<?= htmlspecialchars($pesquisa); ?>">
                <button class="btn btn-outline-success" type="submit">Pesquisar</button>
            </form>

            <!-- Botão para abrir o modal de adição de novo feriado -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Feriado</button>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Código</th>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Dia do Feriado</th>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        // Formata a data no formato brasileiro
                        $dia_feriado = date('d/m/Y', strtotime($row['dia_feriado']));
                ?>
                        <tr>
                            <td class='text-center'><?= htmlspecialchars($row['idFeriados']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['nome']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($dia_feriado); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['tipo']); ?></td>
                            <td class='text-center'>
                                <div class="d-flex justify-content-center">
                                    <!-- Botão de edição -->
                                    <button class="btn btn-warning me-2 action-button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick='editFeriado(<?= json_encode($row); ?>)'>
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Formulário de exclusão -->
                                    <form action="../controls/cadastrarFeriados.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="idFeriados" value="<?= htmlspecialchars($row['idFeriados']); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger action-button" onclick="return confirm('Tem certeza que deseja excluir este feriado?')">
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
                        <td colspan="5" class="text-center">Nenhum feriado encontrado</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina > 1) ? 'formFeriados.php?pagina=' . ($pagina - 1) . '&busca=' . urlencode($pesquisa) : '#'; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                    <li class="page-item <?= ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formFeriados.php?pagina=<?= $i; ?>&busca=<?= urlencode($pesquisa); ?>"><?= $i; ?></a></li>
                <?php } ?>
                <li class="page-item <?= ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina < $num_pagina) ? 'formFeriados.php?pagina=' . ($pagina + 1) . '&busca=' . urlencode($pesquisa) : '#'; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal para adicionar/editar feriados -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Feriado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="feriadosForm" action="../controls/cadastrarFeriados.php" method="POST">
                            <input type="hidden" id="idFeriados" name="idFeriados">
                            <input type="hidden" id="action" name="action" value="add">

                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Feriado</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="dia_feriado" class="form-label">Dia do Feriado</label>
                                <input type="date" class="form-control" id="dia_feriado" name="dia_feriado" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Feriado</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="" disabled selected>Escolha o tipo de feriado</option>
                                    <option value="Municipal">Municipal</option>
                                    <option value="Estadual">Estadual</option>
                                    <option value="Nacional">Nacional</option>
                                    <option value="Ponto Facultativo">Ponto Facultativo</option>
                                    <option value="Interno">Interno</option>
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script>
        function editFeriado(data) {
            document.getElementById('idFeriados').value = data.idFeriados;
            document.getElementById('nome').value = data.nome;

            var dataFormatada = data.dia_feriado.split('/').reverse().join('-');
            document.getElementById('dia_feriado').value = dataFormatada;

            document.getElementById('tipo').value = data.tipo;
            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Feriado';
        }

        function clearForm() {
            document.getElementById('feriadosForm').reset();
            document.getElementById('idFeriados').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Novo Feriado';
        }

        function submitForm() {
            document.getElementById('feriadosForm').submit();
        }
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>
