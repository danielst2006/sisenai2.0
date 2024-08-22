<?php
// Inclui o arquivo de menu
include_once '../head/menu.php';
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
$result_curso = "SELECT COUNT(*) AS total FROM cursos 
                 WHERE nome_curso LIKE '%$pesquisa%' 
                    OR area_tecnologica LIKE '%$pesquisa%' 
                    OR ano LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_curso);

if ($consulta === false) {
    die("Erro na consulta: " . mysqli_error($conn));
}

$total_curso = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_curso / $quantidade_pg);

// Consulta para buscar os cursos
$sql = "SELECT curso_id, nome_curso, area_tecnologica, ano 
FROM cursos 
WHERE nome_curso LIKE CONCAT('%', '$pesquisa', '%') 
   OR area_tecnologica LIKE CONCAT('%', '$pesquisa', '%') 
   OR ano LIKE CONCAT('%', '$pesquisa', '%')";
$resultado = mysqli_query($conn, $sql);

// Consulta para buscar todos os cursos para o select
$sql_cursos = "SELECT nome_curso FROM cursos ORDER BY nome_curso ASC";
$result_cursos = mysqli_query($conn, $sql_cursos);

if ($result_cursos === false) {
    die("Erro na consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Cursos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Cursos</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formCursos.php" method="post" class="mb-4">
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
                                        echo '<div id="alertBox" class="alert alert-success mb-0" style="display: inline-block" role="alert">Operação realizada com sucesso!</div>';
                                    } else if ($_GET['status'] == 'error') {
                                        echo '<div id="alertBox" class="alert alert-danger mb-0" style="display: inline-block" role="alert">Erro ao realizar a operação</div>';
                                    }
                                }
                                ?>
                            </div>

                            <script>
                                // Esconde a mensagem de alerta após 5 segundos
                                setTimeout(function() {
                                    var alertBox = document.getElementById('alertBox');
                                    if (alertBox) {
                                        alertBox.style.display = 'none';
                                    }
                                }, 5000); // 5000 ms = 5 segundos
                            </script>

                            <div>
                                <!-- Botão para abrir o modal de adição de novos cursos -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Curso</button>
                            </div>
                        </div>

                        <!-- Tabela de cursos -->
                        <table class='table rounded-table'>
                            <thead>
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Nome do Curso</th>
                                    <th class="text-center">Área Tecnológica</th>
                                    <th class="text-center">Ano</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Verifica se há cursos e exibe cada um em uma linha da tabela
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                ?>
                                        <tr>
                                            <td class='text-center'><?= htmlspecialchars($row['curso_id']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_curso']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['area_tecnologica']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['ano']); ?></td>
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <!-- Botão de edição -->
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editCurso(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarCurso.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='curso_id' value='<?= htmlspecialchars($row['curso_id']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>

                                                        <!-- Botão de exclusão com confirmação -->
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir este curso?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- Mensagem quando não há cursos -->
                                    <tr>
                                        <td colspan='5'>Nenhum curso encontrado</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formCurso.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formCurso.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formCurso.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
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
                                                <div class="mb-3">
                                                <label for="area_tecnologica" class="form-label">Área Tecnológica</label>
                                                <select class="form-select" id="area_tecnologica" name="area_tecnologica" required>
                                                    <option value="">Selecione uma área</option>
                                                    <option value="Automotiva">Automotiva</option>
                                                    <option value="Automação Industrial">Automação Industrial</option>
                                                    <option value="Eletrônica">Eletrônica</option>
                                                    <option value="Eletrotécnica">Eletrotécnica</option>
                                                    <option value="Mecânica">Mecânica</option>
                                                    <option value="Mecatrônica">Mecatrônica</option>
                                                    <option value="TI Software">TI Software</option>
                                                    <option value="TI Hardware">TI Hardware</option>
                                                    <option value="Desenvolvimento de Sistemas">Desenvolvimento de Sistemas</option>
                                                    <option value="Redes de Computadores">Redes de Computadores</option>
                                                    <option value="Manutenção Industrial">Manutenção Industrial</option>
                                                    <option value="Telecomunicações">Telecomunicações</option>
                                                    <option value="Química">Química</option>
                                                    <option value="Metalurgia">Metalurgia</option>
                                                    <option value="Logística">Logística</option>
                                                    <option value="Gestão da Produção">Gestão da Produção</option>
                                                    <option value="Segurança do Trabalho">Segurança do Trabalho</option>
                                                    <option value="Construção Civil">Construção Civil</option>
                                                    <option value="Design de Produto">Design de Produto</option>
                                                    <option value="Gestão Ambiental">Gestão Ambiental</option>
                                                    <option value="Energia Renovável">Energia Renovável</option>
                                                </select>
                                           
                                            </div>

                                         

                                            <div class="mb-3">
                                                <label for="ano" class="form-label">Ano</label>
                                                <input type="number" min="1900" max="2100" class="form-control" id="ano" name="ano" required>
                                            </div>
                                           


                                       

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim do Modal -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para limpar o formulário ao abrir o modal -->
    <script>
        function clearForm() {
            document.getElementById("cursoForm").reset();
            document.getElementById("action").value = "add";
        }

        function editCurso(curso) {
            document.getElementById("curso_id").value = curso.curso_id;
            document.getElementById("nome_curso").value = curso.nome_curso;
            document.getElementById("area_tecnologica").value = curso.area_tecnologica;
            document.getElementById("ano").value = curso.ano;
            document.getElementById("action").value = "update";
            document.getElementById("exampleModalLabel").innerText = "Editar Curso";
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>