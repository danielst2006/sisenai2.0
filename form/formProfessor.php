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

$pesquisa = isset($_POST['busca']) ? mysqli_real_escape_string($conn, $_POST['busca']) : '';

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$quantidade_pg = 10;
$inicio = ($quantidade_pg * $pagina) - $quantidade_pg;

// Consulta para contar o total de registros
$result_professor = "SELECT COUNT(*) AS total FROM professores p 
                     JOIN usuarios u ON p.usuario_id = u.idUsuario 
                     WHERE p.nome LIKE '%$pesquisa%' 
                     OR p.email LIKE '%$pesquisa%' 
                     OR p.telefone LIKE '%$pesquisa%' 
                     OR u.nome_usuario LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_professor);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_professor = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_professor / $quantidade_pg);

// Consulta para buscar os professores com paginação
$sql = "SELECT p.idProfessor, p.nome, p.email, p.telefone, u.idUsuario, u.nome_usuario AS usuario, p.area, p.tipo_contrato 
        FROM professores p 
        JOIN usuarios u ON p.usuario_id = u.idUsuario 
        WHERE p.nome LIKE '%$pesquisa%' 
           OR p.email LIKE '%$pesquisa%' 
           OR p.telefone LIKE '%$pesquisa%' 
           OR u.nome_usuario LIKE '%$pesquisa%' 
        LIMIT $inicio, $quantidade_pg";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Professores</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th:nth-child(1), .table td:nth-child(1) { width: 8%; }
        .table th:nth-child(2), .table td:nth-child(2) { width: 20%; }
        .table th:nth-child(3), .table td:nth-child(3) { width: 20%; }
        .table th:nth-child(4), .table td:nth-child(4) { width: 15%; }
        .table th:nth-child(5), .table td:nth-child(5) { width: 12%; }
        .table th:nth-child(6), .table td:nth-child(6) { width: 10%; }
        .table th:nth-child(7), .table td:nth-child(7) { width: 10%; }
        .table th:nth-child(8), .table td:nth-child(8) { width: 15%; }

        .action-button { width: 30px; height: 30px; padding: 5px; text-align: center; }
    </style>
</head>

<body class="bg-light text-dark">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Cadastro de Professores</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Formulário de Pesquisa -->
            <form class="d-flex" action="formProfessor.php" method="POST">
                <input class="form-control me-2" type="search" name="busca" placeholder="Pesquisar por nome, email ou telefone" value="<?= htmlspecialchars($pesquisa); ?>">
                <button class="btn btn-outline-success" type="submit">Pesquisar</button>
            </form>

            <!-- Botão para abrir o modal de adição de novo professor -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Professor</button>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Código</th>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Telefone</th>
                    <th class="text-center">Usuário</th>
                    <th class="text-center">Área</th>
                    <th class="text-center">Tipo de Contrato</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                ?>
                        <tr>
                            <td class='text-center'><?= htmlspecialchars($row['idProfessor']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['nome']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['email']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['telefone']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['usuario']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['area']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['tipo_contrato']); ?></td>
                            <td class='text-center'>
                                <div class='d-flex justify-content-center'>
                                    <!-- Botão de edição -->
                                    <button class="btn btn-warning me-2 action-button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick='editProfessor(<?= json_encode($row); ?>)'>
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <!-- Formulário de exclusão -->
                                    <form action='../controls/cadastrarProfessor.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='idProfessor' value='<?= htmlspecialchars($row['idProfessor']); ?>'>
                                        <input type='hidden' name='action' value='delete'>
                                        <button type='submit' class='btn btn-danger action-button' onclick="return confirm('Tem certeza que deseja excluir este professor?')">
                                            <i class='fas fa-times'></i>
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
                        <td colspan="8" class="text-center">Nenhum professor encontrado</td>
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
                    <a class="page-link" href="<?= ($pagina > 1) ? 'formProfessor.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                    <li class="page-item <?= ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formProfessor.php?pagina=<?= $i; ?>"><?= $i; ?></a></li>
                <?php } ?>
                <li class="page-item <?= ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= ($pagina < $num_pagina) ? 'formProfessor.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal para adicionar/editar professor -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Professor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="professorForm" action="../controls/cadastrarProfessor.php" method="POST">
                            <input type="hidden" id="idProfessor" name="idProfessor">
                            <input type="hidden" id="action" name="action" value="add">

                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" required>
                            </div>
                            <div class="mb-3">
                                <label for="usuario_id" class="form-label">Usuário</label>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <?php
                                    $query = "SELECT idUsuario, nome_usuario FROM usuarios";
                                    $result = mysqli_query($conn, $query);

                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['idUsuario']}'>{$row['nome_usuario']}</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Nenhum usuário disponível</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="area" class="form-label">Área</label>
                                <select class="form-select" id="area" name="area" required>
                                    <option value="">Selecione a Área</option>
                                    <option value="Automotiva">Automotiva</option>
                                    <!-- Adicione outras áreas aqui -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_contrato" class="form-label">Tipo de Contrato</label>
                                <select class="form-select" id="tipo_contrato" name="tipo_contrato" required>
                                    <option value="">Selecione o Tipo de Contrato</option>
                                    <option value="Mensalista">Mensalista</option>
                                    <option value="RPA">RPA</option>
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
        function editProfessor(data) {
            document.getElementById('idProfessor').value = data.idProfessor;
            document.getElementById('nome').value = data.nome;
            document.getElementById('email').value = data.email;
            document.getElementById('telefone').value = data.telefone;
            document.getElementById('area').value = data.area;
            document.getElementById('tipo_contrato').value = data.tipo_contrato;

            let usuarioSelect = document.getElementById('usuario_id');
            for (let i = 0; i < usuarioSelect.options.length; i++) {
                if (usuarioSelect.options[i].value == data.idUsuario) {
                    usuarioSelect.selectedIndex = i;
                    break;
                }
            }

            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Professor';
        }

        function clearForm() {
            document.getElementById('professorForm').reset();
            document.getElementById('idProfessor').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Novo Professor';
        }

        function submitForm() {
            document.getElementById('professorForm').submit();
        }
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>
