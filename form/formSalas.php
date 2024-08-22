<?php 
// Inclui o arquivo de conexão com o banco de dados
include_once '../bd/conn.php';

// Inclui o arquivo de menu
include_once '../head/menu.html';

// Número de registros por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$quantidade_pg = 5;
$inicio = ($quantidade_pg * $pagina) - $quantidade_pg;

// Calcular o número total de registros
$sql = "SELECT COUNT(*) AS total FROM andar";
$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_tipo = mysqli_fetch_assoc($result)['total'];
$num_pagina = ceil($total_tipo / $quantidade_pg);

// Calcular o índice inicial dos registros
$start_from = ($pagina - 1) * $quantidade_pg;

// Consulta SQL para buscar andares com limite e offset
$sql = "SELECT * FROM andar LIMIT $start_from, $quantidade_pg";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Sala</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="bg-light text-dark">

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center">Sala</h1>
            <div style="overflow-x:auto;">
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
                        <a href="formAndar.php" class="btn btn-primary ms-2">Andar</a>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Sala</button>
                    </div>
                </div>

                <table class='table rounded-table'>
                    <thead>
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Andar</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Consulta SQL para buscar salas junto com o nome do andar
                        $sql = "SELECT s.id_sala, s.nome AS nome_sala, andar.nome AS nome_andar, andar.id_andar FROM salas s JOIN andar ON s.andar_id = andar.id_andar";
                        $resultado = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                        ?>
                        <tr>
                            <td class='text-center'><?= htmlspecialchars($row['id_sala']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['nome_sala']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['nome_andar']); ?></td>
                            <td class='text-center'>
                                <div class='d-flex justify-content-center'>
                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editRoom(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>
                                    <form action='../controls/cadastrarSalas.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='id_sala' value='<?= htmlspecialchars($row['id_sala']); ?>'>
                                        <input type='hidden' name='action' value='delete'>
                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir esta sala?")'><i class='fas fa-times'></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                        ?>
                        <tr><td colspan='4'>Nenhuma sala encontrada</td></tr>
                        <?php 
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Modal para adicionar/editar salas -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Sala</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <div class="modal-body">
                        <form id="roomForm" action="../controls/cadastrarSalas.php" method="POST">
                          <input type="hidden" id="id_sala" name="id_sala">
                          <input type="hidden" id="action" name="action" value="add">

                          <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Sala</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                          </div>

                          <div class="mb-3">
                            <label for="andar_id" class="form-label">Andar</label>
                            <select class="form-control" id="andar_id" name="andar_id" required>
                                <?php
                                // Consulta SQL para buscar todos os andares
                                $sql_andar = "SELECT id_andar, nome FROM andar";
                                $resultado_andar = mysqli_query($conn, $sql_andar);

                                if (mysqli_num_rows($resultado_andar) > 0) {
                                    while ($andar = mysqli_fetch_assoc($resultado_andar)) {
                                        echo "<option value='" . htmlspecialchars($andar['id_andar']) . "'>" . htmlspecialchars($andar['nome']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
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
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
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
</script>

<!-- Navegação de paginação -->
<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">
    <li class="page-item <?= $pagina <= 1 ? 'disabled' : ''; ?>">
      <a class="page-link" href="<?= $pagina > 1 ? 'formSalas.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php for ($i = 1; $i <= $num_pagina; $i++): ?>
    <li class="page-item <?= $pagina == $i ? 'active' : ''; ?>">
      <a class="page-link" href="formSalas.php?pagina=<?= $i; ?>"><?= $i; ?></a>
    </li>
    <?php endfor; ?>
    <li class="page-item <?= $pagina >= $num_pagina ? 'disabled' : ''; ?>">
      <a class="page-link" href="<?= $pagina < $num_pagina ? 'formSalas.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>

</body>
</html>
