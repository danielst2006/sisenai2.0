<?php

session_start();
if (isset($_SESSION['login'])) {
    if ($_SESSION['tipo_usuario'] != "COPED" && $_SESSION['tipo_usuario'] != "ADM") {
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

$pesquisa = isset($_POST['busca']) ? $_POST['busca'] : '';

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$quantidade_pg = 5;
$inicio = ($pagina - 1) * $quantidade_pg;

// Consulta para contar o total de registros
$pesquisaLike = "%$pesquisa%";
$query_total = "SELECT COUNT(*) AS total 
                FROM ferias f 
                JOIN tipo_ferias tf ON f.tipoFerias_id = tf.id_tipoferias 
                WHERE tf.tipo LIKE '$pesquisaLike'";
$result_curso = mysqli_query($conn, $query_total);

if (!$result_curso) {
    die("Erro na consulta: " . mysqli_error($conn));
}

$total_curso = mysqli_fetch_assoc($result_curso)['total'];
$num_pagina = ceil($total_curso / $quantidade_pg);

// Consulta para buscar as férias
$query = "SELECT f.idFerias, f.data_inicio, f.data_final, f.tipoFerias_id, tf.tipo 
          FROM ferias f 
          JOIN tipo_ferias tf ON f.tipoFerias_id = tf.id_tipoferias 
          WHERE f.idFerias LIKE '$pesquisaLike' 
             OR f.data_inicio LIKE '$pesquisaLike' 
             OR f.data_final LIKE '$pesquisaLike' 
             OR tf.tipo LIKE '$pesquisaLike' 
          LIMIT $inicio, $quantidade_pg";
$resultado = mysqli_query($conn, $query);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($conn));
}

// Função para formatar a data para o formato brasileiro
function formatarData($data) {
    if ($data) {
        $dataObj = new DateTime($data);
        return $dataObj->format('d/m/Y');
    }
    return '';
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Férias</title>
    <!-- Bootstrap CSS -->
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
            width: 20%;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 25%;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 25%;
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
        <h1 class="text-center mb-4">Cadastro de Férias</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Formulário de Pesquisa -->
            <form class="d-flex" action="formFerias.php" method="POST">
                <input class="form-control me-2" type="search" name="busca" placeholder="Pesquisar por tipo ou data" value="<?= htmlspecialchars($pesquisa); ?>">
                <button class="btn btn-outline-success" type="submit">Pesquisar</button>
            </form>

            <!-- Botão para abrir o modal de adição de nova férias -->
            <div>
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Férias</button>
                <a href="formTipoFerias.php" class="btn btn-secondary">Tipo de Férias</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Código</th>
                    <th class="text-center">Data Início</th>
                    <th class="text-center">Data Fim</th>
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
                            <td class='text-center'><?= htmlspecialchars($row['idFerias']); ?></td>
                            <td class='text-center'><?= formatarData($row['data_inicio']); ?></td>
                            <td class='text-center'><?= formatarData($row['data_final']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['tipo']); ?></td>
                            <td class='text-center'>
                                <div class="d-flex justify-content-center">
                                    <!-- Botão de edição -->
                                    <button class="btn btn-warning me-2 action-button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick='editFerias(<?= json_encode($row); ?>)'>
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Formulário de exclusão -->
                                    <form action="../controls/cadastrarFerias.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="idFerias" value="<?= htmlspecialchars($row['idFerias']); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger action-button" onclick="return confirm('Tem certeza que deseja excluir estas férias?')">
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
                        <td colspan="5" class="text-center">Nenhuma férias encontrada</td>
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
                    <a class="page-link" href="<?= ($pagina > 1) ? 'formFerias.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                    <li class="page-item <?= ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formFerias.php?pagina=<?= $i; ?>"><?= $i; ?></a></li>
                <?php } ?>
                <li class="page-item <?= ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina < $num_pagina) ? 'formFerias.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal para adicionar/editar férias -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Férias</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="feriasForm" action="../controls/cadastrarFerias.php" method="POST">
                            <input type="hidden" id="idFerias" name="idFerias">
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
                                <label for="tipoFerias" class="form-label">Tipo de Férias</label>
                                <select class="form-select" id="tipoFerias" name="tipoFerias_id" required>
                                    <?php
                                    $query_tipo = "SELECT id_tipoferias, tipo FROM tipo_ferias";
                                    $result_tipo = mysqli_query($conn, $query_tipo);

                                    if ($result_tipo) {
                                        while ($row = mysqli_fetch_assoc($result_tipo)) {
                                            echo "<option value='{$row['id_tipoferias']}'>{$row['tipo']}</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Nenhum tipo disponível</option>";
                                    }
                                    ?>
                                </select>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script>
        function editFerias(data) {
            document.getElementById('idFerias').value = data.idFerias;
            document.getElementById('data_inicio').value = data.data_inicio;
            document.getElementById('data_final').value = data.data_final;
            document.getElementById('tipoFerias').value = data.tipoFerias_id;
            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Férias';
        }

        function clearForm() {
            document.getElementById('feriasForm').reset();
            document.getElementById('idFerias').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Nova Férias';
        }

        function submitForm() {
            document.getElementById('feriasForm').submit();
        }
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>
