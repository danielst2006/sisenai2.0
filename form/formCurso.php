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
include_once "../bd/conn.php";

// Verifica se foi realizada uma pesquisa
$pesquisa = isset($_POST['busca']) ? $_POST['busca'] : '';

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$quantidade_pg = 10;
$inicio = ($quantidade_pg * $pagina) - $quantidade_pg;

// Consulta para contar o total de registros
$result_curso = "SELECT COUNT(*) AS total FROM cursos WHERE nome_curso LIKE ? OR area_tecnologica LIKE ? OR ano LIKE ?";
$stmt = $conn->prepare($result_curso);
$pesquisa_like = "%$pesquisa%";
$stmt->bind_param('sss', $pesquisa_like, $pesquisa_like, $pesquisa_like);
$stmt->execute();
$consulta = $stmt->get_result();
$total_curso = $consulta->fetch_assoc()['total'];
$num_pagina = ceil($total_curso / $quantidade_pg);

// Consulta para buscar os cursos
$sql = "SELECT curso_id, nome_curso, area_tecnologica, ano FROM cursos WHERE nome_curso LIKE ? OR area_tecnologica LIKE ? OR ano LIKE ? LIMIT $inicio, $quantidade_pg";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $pesquisa_like, $pesquisa_like, $pesquisa_like);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th:nth-child(1), .table td:nth-child(1) { width: 10%; }
        .table th:nth-child(2), .table td:nth-child(2) { width: 50%; }
        .table th:nth-child(3), .table td:nth-child(3) { width: 20%; }
        .table th:nth-child(4), .table td:nth-child(4) { width: 10%; }
        .action-button { width: 30px; height: 30px; padding: 5px; text-align: center; }
    </style>
</head>

<body class="bg-light text-dark">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Cadastro de Cursos</h1>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex" action="formCurso.php" method="POST">
                <input class="form-control me-2" type="search" name="busca" placeholder="Pesquisar por nome, área ou ano" value="<?= htmlspecialchars($pesquisa); ?>">
                <button class="btn btn-outline-success" type="submit">Pesquisar</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Curso</button>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Código</th>
                    <th class="text-center">Nome do Curso</th>
                    <th class="text-center">Área Tecnológica</th>
                    <th class="text-center">Ano</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($row['curso_id']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['nome_curso']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['area_tecnologica']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['ano']); ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-warning me-2 action-button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick='editCurso(<?= json_encode($row); ?>)'>
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <form action="../controls/cadastrarCurso.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="curso_id" value="<?= htmlspecialchars($row['curso_id']); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger action-button" onclick="return confirm('Tem certeza que deseja excluir este curso?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhum curso encontrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina > 1) ? 'formCurso.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $num_pagina; $i++): ?>
                    <li class="page-item <?= ($pagina == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="formCurso.php?pagina=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina < $num_pagina) ? 'formCurso.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal para adicionar/editar cursos -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="cursoForm" action="../controls/cadastrarCurso.php" method="POST">
                            <input type="hidden" id="curso_id" name="curso_id">
                            <input type="hidden" id="action" name="action" value="add">
                            <div class="mb-3">
                                <label for="nome_curso" class="form-label">Nome do Curso</label>
                                <input type="text" class="form-control" id="nome_curso" name="nome_curso" required>
                            </div>
                            <div class="mb-3">
                                <label for="area_tecnologica" class="form-label">Área Tecnológica</label>
                                <select class="form-select" id="area_tecnologica" name="area_tecnologica" required>
                                    <option value="AUTOMAÇÃO">AUTOMAÇÃO</option>
                                    <option value="ELETROELETRÔNICA">ELETROELETRÔNICA</option>
                                    <option value="ALIMENTOS E BEBIDAS">ALIMENTOS E BEBIDAS</option>
                                    <option value="BIOCOMBUSTÍVEIS">BIOCOMBUSTÍVEIS</option>
                                    <option value="CONSTRUÇÃO CIVIL">CONSTRUÇÃO CIVIL</option>
                                    <option value="ENERGIA">ENERGIA</option>
                                    <!-- Outras opções aqui -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="ano" class="form-label">Ano</label>
                                <input type="number" class="form-control" id="ano" name="ano" min="1900" max="2100" required>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        function clearForm() {
            document.getElementById('cursoForm').reset();
            document.getElementById('action').value = 'add';
            document.getElementById('exampleModalLabel').innerText = 'Adicionar Novo Curso';
        }

        function editCurso(curso) {
            document.getElementById('curso_id').value = curso.curso_id;
            document.getElementById('nome_curso').value = curso.nome_curso;
            document.getElementById('area_tecnologica').value = curso.area_tecnologica;
            document.getElementById('ano').value = curso.ano;
            document.getElementById('action').value = 'update';
            document.getElementById('exampleModalLabel').innerText = 'Atualizar Curso';
        }
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>
