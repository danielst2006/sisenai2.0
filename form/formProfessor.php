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

// Consulta para buscar os professores
$sql = "SELECT p.idProfessor, p.nome, p.email, p.telefone, u.nome_usuario AS usuario, p.area, p.tipo_contrato 
        FROM professores p 
        JOIN usuarios u ON p.usuario_id = u.idUsuario 
        WHERE p.nome LIKE CONCAT('%', '$pesquisa', '%') 
           OR p.email LIKE CONCAT('%', '$pesquisa', '%') 
           OR p.telefone LIKE CONCAT('%', '$pesquisa', '%') 
           OR u.nome_usuario LIKE CONCAT('%', '$pesquisa', '%')";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Professores</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Professores</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formProfessores.php" method="post" class="mb-4">
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
                                <!-- Botão para abrir o modal de adição de novos professores -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Professor</button>
                            </div>
                        </div>

                        <!-- Tabela de professores -->
                        <table class='table rounded-table'>
                            <thead>
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
                                // Verifica se há professores e exibe cada um em uma linha da tabela
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
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editProfessor(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarProfessor.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='idProfessor' value='<?= htmlspecialchars($row['idProfessor']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>

                                                        <!-- Botão de exclusão com confirmação -->
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir este professor?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- Mensagem quando não há professores -->
                                    <tr>
                                        <td colspan='8'>Nenhum professor encontrado</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formProfessor.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formProfessor.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formProfessor.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
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
                                                    // Consulta para listar usuários
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
        function editProfessor(data) {
            document.getElementById('idProfessor').value = data.idProfessor;
            document.getElementById('nome').value = data.nome;
            document.getElementById('email').value = data.email;
            document.getElementById('telefone').value = data.telefone;
            document.getElementById('usuario_id').value = data.usuario_id;
            document.getElementById('nome_usuario').value = data.nome_usuario;
            document.getElementById('area').value = data.area;
            document.getElementById('tipo_contrato').value = data.tipo_contrato;
        
       let usuarioSelect = document.getElementById('usuario_id');

// Percorre as opções do select e define a que corresponde ao nome do usuário
     for (let i = 0; i < usuarioSelect.options.length; i++) {
       if (usuarioSelect.options[i].text === data.nome_usuario) {
          usuarioSelect.selectedIndex = i;
           break;
      }
  }

            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Professor';
        }

        // Função para limpar o formulário no modal para adicionar novos professores
        function clearForm() {
            document.getElementById('professorForm').reset();
            document.getElementById('idProfessor').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Novo Professor';
        }

        // Função para submeter o formulário
        function submitForm() {
            document.getElementById('professorForm').submit();
        }
    </script>
</body>

</html>
