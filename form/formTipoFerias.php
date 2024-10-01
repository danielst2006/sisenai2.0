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

// Inicializa variáveis
$pesquisa = isset($_POST['busca']) ? mysqli_real_escape_string($conn, $_POST['busca']) : '';

// Paginação
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$quantidade_pg = 5;
$inicio = ($quantidade_pg * $pagina) - $quantidade_pg;

// Consulta para contar o total de registros
$sql_total = "SELECT COUNT(*) AS total FROM tipo_ferias WHERE tipo LIKE '%$pesquisa%'";
$result = mysqli_query($conn, $sql_total);
$total_tipo = mysqli_fetch_assoc($result)['total'];
$num_pagina = ceil($total_tipo / $quantidade_pg);

// Consulta para buscar os tipos de férias com paginação
$sql = "SELECT id_tipoferias, tipo FROM tipo_ferias WHERE tipo LIKE '%$pesquisa%' LIMIT $inicio, $quantidade_pg";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Tipos de Férias</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ajusta a largura das colunas */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 20%;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 60%;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 20%;
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
        <h1 class="text-center mb-4">Cadastro de Tipos de Férias</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Formulário de Pesquisa -->
            <form class="d-flex" action="formTipoFerias.php" method="POST">
                <input class="form-control me-2" type="search" name="busca" placeholder="Pesquisar por tipo" value="<?= htmlspecialchars($pesquisa); ?>">
                <button class="btn btn-outline-success" type="submit">Pesquisar</button>
            </form>

            <!-- Botão para abrir o modal de adição de novo tipo de férias -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Tipo</button>
        </div>

        <!-- Tabela de tipos de férias -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Código</th>
                    <th class="text-center">Tipo de Férias</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                ?>
                        <tr>
                            <td class='text-center'><?= htmlspecialchars($row['id_tipoferias']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['tipo']); ?></td>
                            <td class='text-center'>
                                <div class='d-flex justify-content-center'>
                                    <!-- Botão de edição -->
                                    <button class="btn btn-warning me-2 action-button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick='editTipo(<?= json_encode($row); ?>)'>
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Formulário de exclusão -->
                                    <form action='../controls/cadastrarTipoFerias.php' method='POST' style='display:inline-block;'>
                                        <input type="hidden" name="id_tipoferias" value="<?= htmlspecialchars($row['id_tipoferias']); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger action-button" onclick="return confirm('Tem certeza que deseja excluir este tipo de férias?')">
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
                        <td colspan="3" class="text-center">Nenhum tipo de férias encontrado</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $pagina <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= $pagina > 1 ? 'formTipoFerias.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                    <li class="page-item <?= $pagina == $i ? 'active' : ''; ?>"><a class="page-link" href="formTipoFerias.php?pagina=<?= $i; ?>"><?= $i; ?></a></li>
                <?php } ?>
                <li class="page-item <?= $pagina >= $num_pagina ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= $pagina < $num_pagina ? 'formTipoFerias.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal para adicionar/editar tipo de férias -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Tipo de Férias</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="tipoFeriasForm" action="../controls/cadastrarTipoFerias.php" method="POST">
                            <input type="hidden" id="id_tipoferias" name="id_tipoferias">
                            <input type="hidden" id="action" name="action" value="add">

                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Férias</label>
                                <input type="text" class="form-control" id="tipo" name="tipo" required>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="submitForm()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script>
        function editTipo(data) {
            document.getElementById('id_tipoferias').value = data.id_tipoferias;
            document.getElementById('tipo').value = data.tipo;
            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Tipo de Férias';
        }

        function clearForm() {
            document.getElementById('tipoFeriasForm').reset();
            document.getElementById('id_tipoferias').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Novo Tipo de Férias';
        }

        function submitForm() {
            document.getElementById('tipoFeriasForm').submit();
        }
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>
