<?php
// Inclui o arquivo de menu
include_once '../head/menu.html';
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
$sql = "SELECT ds.idDisponibilidade, s.nome AS sala, ds.data_inicio, ds.data_final 
        FROM disponibilidade_salas ds 
        JOIN salas s ON ds.sala_id = s.id_sala 
        WHERE ds.idDisponibilidade LIKE CONCAT('%', '$pesquisa', '%') 
           OR ds.data_inicio LIKE CONCAT('%', '$pesquisa', '%') 
           OR ds.data_final LIKE CONCAT('%', '$pesquisa', '%') 
           OR s.nome LIKE CONCAT('%', '$pesquisa', '%')";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Disponibilidade de Salas</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Disponibilidade de Salas</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formDisponibilidade.php" method="post" class="mb-4">
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
                                if (isset($_GET['status'])) {
                                    if ($_GET['status'] == 'success') {
                                        echo '<div class="alert alert-success mb-0" style="display: inline-block" role="alert">Operação realizada com sucesso!</div>';
                                    } else if ($_GET['status'] == 'error') {
                                        echo '<div class="alert alert-danger mb-0" style="display: inline-block" role="alert">Erro ao realizar a operação</div>';
                                    }
                                }
                                ?>
                            </div>

                            <div>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Disponibilidade</button>
                            </div>
                        </div>

                        <table class='table rounded-table'>
                            <thead>
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Sala</th>
                                    <th class="text-center">Data Início</th>
                                    <th class="text-center">Data Fim</th>
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
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editDisponibilidade(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <form action='../controls/cadastrarDisponibilidade.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='idDisponibilidade' value='<?= htmlspecialchars($row['idDisponibilidade']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir esta disponibilidade?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan='5'>Nenhuma disponibilidade encontrada</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formDisponibilidade.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formDisponibilidade.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formDisponibilidade.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

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
                                                <label for="data_inicio" class="form-label">Data Início</label>
                                                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="data_final" class="form-label">Data Fim</label>
                                                <input type="date" class="form-control" id="data_final" name="data_final" required>
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
        function editDisponibilidade(data) {
            document.getElementById('idDisponibilidade').value = data.idDisponibilidade;
            document.getElementById('sala_id').value = data.sala_id;
            document.getElementById('data_inicio').value = data.data_inicio;
            document.getElementById('data_final').value = data.data_final;
            document.getElementById('action').value = 'edit';
            document.querySelector('.modal-title').textContent = 'Editar Disponibilidade';
        }

        // Função para limpar o formulário no modal para adicionar novas disponibilidades
        function clearForm() {
            document.getElementById('disponibilidadeForm').reset();
            document.getElementById('idDisponibilidade').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Nova Disponibilidade';
        }

        // Função para submeter o formulário
        function submitForm() {
            document.getElementById('disponibilidadeForm').submit();
        }
    </script>
</body>

</html>