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
$result_usuario = "SELECT COUNT(*) AS total FROM usuarios u 
                 JOIN tipo_usuario tu ON u.tipo_usuario_id = tu.idTipo_usuario 
                 WHERE u.nome_usuario LIKE '%$pesquisa%' OR u.email LIKE '%$pesquisa%' OR u.telefone LIKE '%$pesquisa%' OR tu.tipo LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_usuario);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_usuario = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_usuario / $quantidade_pg);

// Consulta para buscar os usuários
$sql = "SELECT u.idUsuario, u.nome_usuario, u.email, u.telefone, u.senha, u.tipo_contrato, u.tipo_usuario_id, u.codigo_acesso, tu.tipo 
FROM usuarios u 
JOIN tipo_usuario tu ON u.tipo_usuario_id = tu.idTipo_usuario 
WHERE u.nome_usuario LIKE CONCAT('%', '$pesquisa', '%') 
   OR u.email LIKE CONCAT('%', '$pesquisa', '%') 
   OR u.telefone LIKE CONCAT('%', '$pesquisa', '%') 
   OR tu.tipo LIKE CONCAT('%', '$pesquisa', '%')";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Usuários</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Usuários</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formUsuarios.php" method="post" class="mb-4">
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
                                <!-- Botão para abrir o modal de adição de novos usuários -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Usuário</button>
                            </div>
                        </div>

                        <!-- Tabela de usuários -->
                        <table class='table rounded-table'>
                            <thead>
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Telefone</th>
                                    <th class="text-center">Tipo de Usuário</th>
                                    <th class="text-center">Tipo de Contrato</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Verifica se há usuários e exibe cada um em uma linha da tabela
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                ?>
                                        <tr>
                                            <td class='text-center'><?= htmlspecialchars($row['idUsuario']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['nome_usuario']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['email']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['telefone']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['tipo']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['tipo_contrato']); ?></td>
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <!-- Botão de edição -->
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editUsuario(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarUsuarios.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='idUsuario' value='<?= htmlspecialchars($row['idUsuario']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>

                                                        <!-- Botão de exclusão com confirmação -->
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir este usuário?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- Mensagem quando não há usuários -->
                                    <tr>
                                        <td colspan='6'>Nenhum usuário encontrado</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formUsuarios.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formUsuarios.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formUsuarios.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Modal para adicionar/editar usuários -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Usuário</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="usuarioForm" action="../controls/cadastrarUsuarios.php" method="POST">
                                            <input type="hidden" name="idUsuario" id="idUsuario">
                                            <input type="hidden" name="action" id="formAction" value="insert">

                                            <div class="mb-3">
                                                <label for="nome_usuario" class="form-label">Nome</label>
                                                <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" required>
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
                                                <label for="senha" class="form-label">Senha</label>
                                                <input type="password" class="form-control" id="senha" name="senha" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tipo_contrato" class="form-label">Tipo de Contrato</label>
                                                <select class="form-control" id="tipo_contrato" name="tipo_contrato" required>
                                                    <option value="RPA">RPA</option>
                                                    <option value="MENSALISTA">MENSALISTA</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tipo_usuario_id" class="form-label">Tipo de Usuário</label>
                                                <select class="form-control" id="tipo_usuario_id" name="tipo_usuario_id" required>
                                                    <?php
                                                    // Consultar os tipos de usuário disponíveis
                                                    $query = "SELECT * FROM tipo_usuario";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo '<option value="' . htmlspecialchars($row['idTipo_usuario']) . '">' . htmlspecialchars($row['tipo']) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="codigo_acesso" class="form-label">Código de Acesso</label>
                                                <input type="text" class="form-control" id="codigo_acesso" name="codigo_acesso" required>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Salvar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Função para limpar o formulário e definir ação para 'insert'
                            function clearForm() {
                                document.getElementById('usuarioForm').reset();
                                document.getElementById('formAction').value = 'insert';
                                document.getElementById('exampleModalLabel').innerText = 'Adicionar Novo Usuário';
                            }

                            // Função para preencher o formulário com dados do usuário e definir ação para 'update'
                            function editUsuario(usuario) {
                                document.getElementById('idUsuario').value = usuario.idUsuario;
                                document.getElementById('nome_usuario').value = usuario.nome_usuario;
                                document.getElementById('email').value = usuario.email;
                                document.getElementById('telefone').value = usuario.telefone;
                                document.getElementById('senha').value = usuario.senha;
                                document.getElementById('tipo_contrato').value = usuario.tipo_contrato;
                                document.getElementById('tipo_usuario_id').value = usuario.tipo_usuario_id;
                                document.getElementById('codigo_acesso').value = usuario.codigo_acesso;

                                document.getElementById('formAction').value = 'update';
                                document.getElementById('exampleModalLabel').innerText = 'Editar Usuário';
                            }
                        </script>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
                        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
