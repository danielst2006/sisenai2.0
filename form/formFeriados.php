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
$result_curso = "SELECT COUNT(*) AS total FROM feriados WHERE nome LIKE '%$pesquisa%' OR dia_feriado LIKE '%$pesquisa%' OR tipo LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_curso);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_curso = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_curso / $quantidade_pg);

// Consulta para buscar os feriados
$sql = "SELECT idFeriados, nome, dia_feriado, tipo FROM feriados WHERE nome LIKE CONCAT('%', '$pesquisa', '%') OR dia_feriado LIKE CONCAT('%', '$pesquisa', '%') OR tipo LIKE CONCAT('%', '$pesquisa', '%')";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Feriados</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Feriados</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formFeriados.php" method="post" class="mb-4">
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
                                <!-- Botão para abrir o modal de adição de novos feriados -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Feriado</button>
                            </div>
                        </div>

                        <!-- Tabela de feriados -->
                        <table class='table rounded-table'>
                            <thead>
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">Dia do Feriado</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Verifica se há feriados e exibe cada um em uma linha da tabela
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                        // Formata a data no formato brasileiro
                                        $dia_feriado = date('d/m/Y', strtotime($row['dia_feriado']));
                                ?>
                                        <tr>
                                            <td class='text-center'><?= htmlspecialchars($row['idFeriados']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($dia_feriado); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['tipo']); ?></td>
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <!-- Botão de edição -->
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editFeriado(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarFeriados.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='idFeriados' value='<?= htmlspecialchars($row['idFeriados']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir este feriado?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan='5'>Nenhum feriado encontrado</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formFeriados.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formFeriados.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formFeriados.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
<!-- Modal para adicionar/editar feriados -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Feriado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="feriadosForm" action="../controls/cadastrarFeriados.php" method="POST">
                    <input type="hidden" id="idFeriados" name="idFeriados">
                    <input type="hidden" id="action" name="action" value="add">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Feriado</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="dia_feriado" class="form-label">Dia do Feriado</label>
                        <input type="date" class="form-control" id="dia_feriado" name="dia_feriado" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Feriado</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="" disabled selected>Escolha o tipo de feriado</option>
                            <option value="Municipal">Municipal</option>
                            <option value="Estadual">Estadual</option>
                            <option value="Nacional">Nacional</option>
                            <option value="Ponto Facultativo">Ponto Facultativo</option>
                            <option value="Interno">Interno</option>
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
        function editFeriado(data) {
            document.getElementById('idFeriados').value = data.idFeriados;
            document.getElementById('nome').value = data.nome;

            // Converte a data no formato DD/MM/YYYY para o formato YYYY-MM-DD
            var dataFormatada = data.dia_feriado.split('/').reverse().join('-');
            document.getElementById('dia_feriado').value = dataFormatada;

            document.getElementById('tipo').value = data.tipo;
            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Feriado';
        }

        // Função para limpar o formulário no modal para adicionar novos feriados
        function clearForm() {
            document.getElementById('feriadosForm').reset();
            document.getElementById('idFeriados').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Novo Feriado';
        }

        // Função para submeter o formulário
        function submitForm() {
            document.getElementById('feriadosForm').submit();
        }
    </script>
</body>

</html>
