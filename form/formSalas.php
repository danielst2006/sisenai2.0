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

// Recupera o termo de pesquisa, se houver
$pesquisa = isset($_POST['busca']) ? mysqli_real_escape_string($conn, $_POST['busca']) : '';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Sala</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 10%;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 40%;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 30%;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
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
        <h1 class="text-center mb-4">Cadastro de Sala</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Formulário de Pesquisa -->
            <form class="d-flex" action="formSalas.php" method="POST">
                <input class="form-control me-2" type="search" name="busca" placeholder="Pesquisar por nome ou andar" value="<?= htmlspecialchars($pesquisa); ?>">
                <button class="btn btn-outline-success" type="submit">Pesquisar</button>
            </form>

            <!-- Botão para abrir o modal de adição de nova sala -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Sala</button>
        </div>

        <!-- Tabela de salas -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Código</th>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Andar</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Definindo quantos registros por página
                $registros_por_pagina = 10;
                $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                $inicio = ($pagina - 1) * $registros_por_pagina;

                // Consulta SQL para buscar salas com filtro de pesquisa por nome ou andar
                $sql = "SELECT s.id_sala, s.nome as nome_sala, andar.nome as nome_andar, andar.id_andar 
                        FROM salas s 
                        JOIN andar ON andar_id = andar.id_andar
                        WHERE s.nome LIKE '%$pesquisa%' OR andar.nome LIKE '%$pesquisa%'
                        LIMIT $inicio, $registros_por_pagina";
                $resultado = mysqli_query($conn, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                ?>
                        <tr>
                            <td class='text-center'><?= htmlspecialchars($row['id_sala']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['nome_sala']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['nome_andar']); ?></td>
                            <td class='text-center'>
                                <div class="d-flex justify-content-center">
                                    <!-- Botão de edição -->
                                    <button class="btn btn-warning me-2 action-button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick='editRoom(<?= json_encode($row); ?>)'>
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Formulário de exclusão -->
                                    <form action='../controls/cadastrarSalas.php' method='POST' style='display:inline-block;'>
                                        <input type="hidden" name="id_sala" value="<?= htmlspecialchars($row['id_sala']); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger action-button" onclick="return confirm('Tem certeza que deseja excluir esta sala?')">
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
                        <td colspan="4" class="text-center">Nenhuma sala encontrada</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <?php
        // Consulta para contar o número total de salas considerando a pesquisa
        $sqlTotal = "SELECT COUNT(*) as total FROM salas s JOIN andar ON andar_id = andar.id_andar 
                    WHERE s.nome LIKE '%$pesquisa%' OR andar.nome LIKE '%$pesquisa%'";
        $resultadoTotal = mysqli_query($conn, $sqlTotal);
        $totalRegistros = mysqli_fetch_assoc($resultadoTotal)['total'];
        $num_pagina = ceil($totalRegistros / $registros_por_pagina);
        ?>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina > 1) ? 'formSalas.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                    <li class="page-item <?= ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formSalas.php?pagina=<?= $i; ?>"><?= $i; ?></a></li>
                <?php } ?>
                <li class="page-item <?= ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina < $num_pagina) ? 'formSalas.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal para adicionar/editar salas -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Sala</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="roomForm" action="../controls/cadastrarSala.php" method="POST">
                            <input type="hidden" id="id_sala" name="id_sala">
                            <input type="hidden" id="action" name="action" value="add">

                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome da Sala</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>

                            <div class="mb-3">
                                <label for="andar_id" class="form-label">Andar</label>
                                <select class="form-select" id="andar_id" name="andar_id" required>
                                    <?php
                                    $sql_andar = "SELECT id_andar, nome FROM andar";
                                    $resultado_andar = mysqli_query($conn, $sql_andar);
                                    ?>
                                    <option value="" disabled selected>Selecione o andar</option>
                                    <?php
                                    if (mysqli_num_rows($resultado_andar) > 0) {
                                        while ($andar = mysqli_fetch_assoc($resultado_andar)) {
                                            echo "<option value='" . htmlspecialchars($andar['id_andar']) . "'>" . htmlspecialchars($andar['nome']) . "</option>";
                                        }
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script>
        function clearForm() {
            document.getElementById('roomForm').reset();
            document.getElementById('id_sala').value = '';
            document.getElementById('action').value = 'add';
            document.getElementById('exampleModalLabel').innerText = 'Adicionar Nova Sala';
        }

        function editRoom(room) {
            document.getElementById('id_sala').value = room.id_sala;
            document.getElementById('nome').value = room.nome_sala;
            document.getElementById('andar_id').value = room.id_andar;
            document.getElementById('action').value = 'update';
            document.getElementById('exampleModalLabel').innerText = 'Atualizar Sala';
        }

        function submitForm() {
            document.getElementById('roomForm').submit();
        }
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>
