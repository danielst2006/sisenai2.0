<?php
session_start();

if (isset($_SESSION['login'])) {
    if ($_SESSION['tipo_usuario'] == "COPED" || $_SESSION['tipo_usuario'] == "ADM") {
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
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Andar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ajusta a largura das colunas */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 15%;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 70%;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
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
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center mb-4">Cadastro de Andar</h1>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Formulário de Pesquisa -->
                    <form class="d-flex" action="formAndar.php" method="GET">
                        <input class="form-control me-2" type="search" name="search" placeholder="Pesquisar por nome ou código" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-outline-success" type="submit">Pesquisar</button>
                    </form>

                    <!-- Botão para abrir o modal de adição de novo andar -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Andar</button>
                </div>

                <!-- Tabela de andares -->
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include_once '../bd/conn.php';

                        // Verifica a conexão com o banco de dados
                        if (!$conn) {
                            die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
                        }

                        $registros_por_pagina = 5;
                        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                        $inicio = ($pagina - 1) * $registros_por_pagina;

                        // Verifica se foi feita uma busca por nome ou código
                        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

                        // Monta a query considerando a pesquisa (se houver)
                        $sql = "SELECT * FROM andar";
                        if (!empty($search)) {
                            $sql .= " WHERE nome LIKE '%$search%' OR id_andar LIKE '%$search%'";
                        }
                        $sql .= " LIMIT $inicio, $registros_por_pagina";

                        // Debug para verificar a query
                        // echo "<pre>$sql</pre>";

                        $resultado = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                                ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($row['id_andar']); ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['nome']); ?></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <!-- Botão de edição -->
                                            <button class="btn btn-warning me-2 action-button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick='editFloor(<?= json_encode($row); ?>)'>
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>

                                            <!-- Formulário de exclusão -->
                                            <form action="../controls/cadastrarAndar.php" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="id_andar" value="<?= htmlspecialchars($row['id_andar']); ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="btn btn-danger action-button" onclick="return confirm('Tem certeza que deseja excluir este andar?')">
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
                                <td colspan="3" class="text-center">Nenhum andar encontrado</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Paginação -->
                <?php
                $sqlTotal = "SELECT COUNT(*) as total FROM andar";
                if (!empty($search)) {
                    $sqlTotal .= " WHERE nome LIKE '%$search%' OR id_andar LIKE '%$search%'";
                }
                $resultadoTotal = mysqli_query($conn, $sqlTotal);
                $totalRegistros = mysqli_fetch_assoc($resultadoTotal)['total'];
                $num_pagina = ceil($totalRegistros / $registros_por_pagina);
                if ($num_pagina == 0) {
                    $num_pagina = 1;
                }
                ?>

                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo ($pagina > 1) ? 'formAndar.php?pagina=' . ($pagina - 1) . '&search=' . urlencode($search) : '#'; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                            <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formAndar.php?pagina=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                        <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formAndar.php?pagina=' . ($pagina + 1) . '&search=' . urlencode($search) : '#'; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Modal para adicionar/editar andares -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Andar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form id="floorForm" action="../controls/cadastrarAndar.php" method="POST">
                                    <input type="hidden" id="id_andar" name="id_andar">
                                    <input type="hidden" id="action" name="action" value="add">

                                    <div class="mb-3">
                                        <label for="nome" class="form-label">Nome do Andar</label>
                                        <input type="text" class="form-control" id="nome" name="nome" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Função para limpar o formulário ao adicionar novo andar
        function clearForm() {
            document.getElementById('floorForm').reset();
            document.getElementById('action').value = 'add';
            document.getElementById('exampleModalLabel').innerText = 'Adicionar Novo Andar';
        }

        // Função para preencher o formulário ao editar um andar
        function editFloor(floor) {
            document.getElementById('id_andar').value = floor.id_andar;
            document.getElementById('nome').value = floor.nome;
            document.getElementById('action').value = 'update';
            document.getElementById('exampleModalLabel').innerText = 'Atualizar Andar';
        }
    </script>

</body>

<?php mysqli_close($conn); ?>

</html>
