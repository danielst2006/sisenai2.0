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
$result_curso = "SELECT COUNT(*) AS total FROM unidade_curricular uc 
                 JOIN cursos c ON uc.curso_id = c.curso_id 
                 WHERE c.nome_curso LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_curso);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_curso = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_curso / $quantidade_pg);

// Consulta para buscar as unidades curriculares
$sql = "SELECT uc.idunidade_curricular, uc.nome_unidade, uc.carga_horaria, c.curso_id, c.nome_curso AS curso_nome 
        FROM unidade_curricular uc 
        JOIN cursos c ON uc.curso_id = c.curso_id 
        WHERE uc.idunidade_curricular LIKE CONCAT('%', '$pesquisa', '%') 
           OR uc.nome_unidade LIKE CONCAT('%', '$pesquisa', '%') 
           OR uc.carga_horaria LIKE CONCAT('%', '$pesquisa', '%') 
           OR c.nome_curso LIKE CONCAT('%', '$pesquisa', '%')";

$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Unidade Curricular</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Unidade Curricular</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formUnidadeCurricular.php" method="post" class="mb-4">
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
                                // Exibe uma mensagem de sucesso ou erro com base no parâmetro de status na URL
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
                                <!-- Botão para abrir o modal de adição de novas unidades curriculares -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Unidade Curricular</button>
                            </div>
                        </div>

                        <!-- Tabela de unidades curriculares -->
                        <table class='table rounded-table'>
                            <thead>
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Nome da Unidade</th>
                                    <th class="text-center">Carga Horária</th>
                                    <th class="text-center">Curso</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Verifica se há unidades curriculares e exibe cada uma em uma linha da tabela
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                ?>
                                        <tr>
                                            <td class='text-center'><?= htmlspecialchars($row['idunidade_curricular']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_unidade']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['carga_horaria']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['curso_nome']); ?></td>
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <!-- Botão de edição -->
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editUnidadeCurricular(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarUnidadeCurricular.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='idunidade_curricular' value='<?= htmlspecialchars($row['idunidade_curricular']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>

                                                        <!-- Botão de exclusão com confirmação -->
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir esta unidade curricular?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- Mensagem quando não há unidades curriculares -->
                                    <tr>
                                        <td colspan='5'>Nenhuma unidade curricular encontrada</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formUnidadeCurricular.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formUnidadeCurricular.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formUnidadeCurricular.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Modal para adicionar/editar unidades curriculares -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Unidade Curricular</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="unidadeCurricularForm" action="../controls/cadastrarUnidadeCurricular.php" method="POST">
                                            <input type="hidden" id="idunidade_curricular" name="idunidade_curricular">
                                            <input type="hidden" id="action" name="action" value="add">

                                            <div class="mb-3">
                                                <label for="nome_unidade" class="form-label">Nome da Unidade</label>
                                                <input type="text" class="form-control" id="nome_unidade" name="nome_unidade" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="carga_horaria" class="form-label">Carga Horária</label>
                                                <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="curso" class="form-label">Curso</label>
                                                <select class="form-select" id="curso" name="curso_id" required>
                                                    <?php
                                                    $query = "SELECT curso_id, nome_curso FROM cursos";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['curso_id']}'>{$row['nome_curso']}</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>Nenhum curso disponível</option>";
                                                    }
                                                    ?>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script>
        // Função para preencher o formulário no modal para edição
        function editUnidadeCurricular(data) {
            document.getElementById('idunidade_curricular').value = data.idunidade_curricular;
            document.getElementById('nome_unidade').value = data.nome_unidade;
            document.getElementById('carga_horaria').value = data.carga_horaria;

            let cursoSelect = document.getElementById('curso');

            // Seleciona a opção correta no <select> com base no curso_id
            for (let i = 0; i < cursoSelect.options.length; i++) {
                if (cursoSelect.options[i].value == data.curso_id) {
                    cursoSelect.selectedIndex = i;
                    break;
                }
            }

            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Unidade Curricular';
        }



        // Função para limpar o formulário no modal para adicionar novas unidades curriculares
        function clearForm() {
            document.getElementById('unidadeCurricularForm').reset();
            document.getElementById('idunidade_curricular').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Nova Unidade Curricular';
        }

        // Função para submeter o formulário
        function submitForm() {
            document.getElementById('unidadeCurricularForm').submit();
        }
    </script>
</body>

</html>