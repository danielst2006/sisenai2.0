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
$result_usuario = "SELECT COUNT(*) AS total FROM tipo_usuario WHERE tipo LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_usuario);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_usuario = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_usuario / $quantidade_pg);

// Consulta para buscar os tipos de usuários
$sql = "SELECT idTipo_usuario, tipo 
FROM tipo_usuario 
WHERE idTipo_usuario LIKE CONCAT('%', '$pesquisa', '%') 
   OR tipo LIKE CONCAT('%', '$pesquisa', '%')";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Tipos de Usuário</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .text-gray {
            color: #6c757d; /* Cor cinza padrão do Bootstrap */
        }
        .btn-custom-gray {
            background-color: #6c757d; /* Cinza padrão do Bootstrap */
            border-color: #6c757d;
            color: white;
        }
        .btn-custom-gray:hover {
            background-color: #5a6268; /* Cinza um pouco mais escuro para o efeito de hover */
            border-color: #545b62;
        }
    </style>
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Tipos de Usuário</h1> 
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formTipoUsuario.php" method="post" class="mb-4">
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
                                <!-- Botão para abrir o modal de adição de novo tipo de usuário -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Tipo de Usuário</button>
                                <!-- Botão para abrir o modal de adição de novos usuários -->
                                <a href="formUsuarios.php" class="btn btn-custom-gray">Usuário</a>
                            </div>
                        </div>

                        <!-- Tabela de tipos de usuário -->
                        <table class='table rounded-table'>
                            <thead>
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Verifica se há tipos de usuários e exibe cada um em uma linha da tabela
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                ?>
                                        <tr>
                                            <td class='text-center'><?= htmlspecialchars($row['idTipo_usuario']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['tipo']); ?></td>
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <!-- Botão de edição -->
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editTipoUsuario(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarTipoUsuario.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='idTipo_usuario' value='<?= htmlspecialchars($row['idTipo_usuario']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>

                                                        <!-- Botão de exclusão com confirmação -->
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir este tipo de usuário?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- Mensagem quando não há tipos de usuários -->
                                    <tr>
                                        <td colspan='3'>Nenhum tipo de usuário encontrado</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formTipoUsuario.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formTipoUsuario.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formTipoUsuario.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Modal para adicionar/editar tipos de usuário -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Tipo de Usuário</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="tipoUsuarioForm" action="../controls/cadastrarTipoUsuario.php" method="POST">
                                            <input type="hidden" id="idTipo_usuario" name="idTipo_usuario">
                                            <input type="hidden" id="action" name="action" value="add">

                                            <div class="mb-3">
                                                <label for="tipo" class="form-label">Tipo</label>
                                                <input type="text" class="form-control" id="tipo" name="tipo" required>
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
        function editTipoUsuario(data) {
            document.getElementById('idTipo_usuario').value = data.idTipo_usuario;
            document.getElementById('tipo').value = data.tipo;
            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Tipo de Usuário';
        }

        // Função para limpar o formulário no modal para adicionar novos tipos de usuário
        function clearForm() {
            document.getElementById('tipoUsuarioForm').reset();
            document.getElementById('idTipo_usuario').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Novo Tipo de Usuário';
        }

        // Função para submeter o formulário
        function submitForm() {
            document.getElementById('tipoUsuarioForm').submit();
        }
    </script>
</body>

</html>
