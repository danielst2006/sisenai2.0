<?php
// Inclui o arquivo de menu
include_once '../head/menu.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Andar</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Andar</h1>
                <div style="overflow-x:auto;">
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
                            <!-- Botão para abrir o modal de adição de novo andar -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Novo Andar</button>
                        </div>
                    </div>

                    <!-- Tabela de andares -->
                    <table class='table rounded-table'>
                        <thead>
                            <tr>
                                <th class="text-center">Código</th>
                                <th class="text-center">Nome</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Conexão com o banco de dados
                            include_once '../bd/conn.php';

                            // Definindo quantos registros por página
                            $registros_por_pagina = 5;

                            // Descobrir qual página estamos
                            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                            $inicio = ($pagina - 1) * $registros_por_pagina;

                            // Consulta SQL para buscar andares com paginação
                            $sql = "SELECT * FROM andar LIMIT $inicio, $registros_por_pagina";
                            $resultado = mysqli_query($conn, $sql);

                            // Verifica se há andares e exibe cada um em uma linha da tabela
                            if (mysqli_num_rows($resultado) > 0) {
                                while ($row = mysqli_fetch_assoc($resultado)) {
                            ?>
                                    <tr>
                                        <td class='text-center'><?= htmlspecialchars($row['id_andar']); ?></td>
                                        <td class='text-center'><?= htmlspecialchars($row['nome']); ?></td>
                                        <td class='text-center'>
                                            <div class='d-flex justify-content-center'>
                                                <!-- Botão de edição -->
                                                <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editFloor(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                <!-- Formulário de exclusão -->
                                                <form action='../controls/cadastrarAndar.php' method='POST' style='display:inline-block;'>
                                                    <input type='hidden' name='id_andar' value='<?= htmlspecialchars($row['id_andar']); ?>'>
                                                    <input type='hidden' name='action' value='delete'>

                                                    <!-- Botão de exclusão com confirmação -->
                                                    <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir este andar?")'><i class='fas fa-times'></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>

                                <!-- Mensagem quando não há andares -->
                                <tr>
                                    <td colspan='3'>Nenhum andar encontrado</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <?php
                    // Consulta para contar o número total de andares
                    $sqlTotal = "SELECT COUNT(*) as total FROM andar";
                    $resultadoTotal = mysqli_query($conn, $sqlTotal);
                    $totalRegistros = mysqli_fetch_assoc($resultadoTotal)['total'];

                    // Calcula o número total de páginas
                    $num_pagina = ceil($totalRegistros / $registros_por_pagina);
                    ?>

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($pagina > 1) ? 'formAndar.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formAndar.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                            <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formAndar.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Modal para adicionar/editar andares -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Novo Andar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <form id="floorForm" action="../controls/cadastrarAndar.php" method="POST">
                                        <input type="hidden" id="id_andar" name="id_andar">
                                        <input type="hidden" id="action" name="action" value="add">

                                        <div class="mb-3">
                                            <label for="nome" class="form-label">Nome do Andar</label>
                                            <input type="text" class="form-control" id="nome" name="nome" required>
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
        // Função para limpar o formulário ao adicionar novo andar
        function clearForm() {
            document.getElementById('floorForm').reset();
            document.getElementById('action').value = 'add';
            document.getElementById('exampleModalLabel').innerText = 'Adicionar Novo Andar';
        }

        // Função para preencher o formulário ao editar um andar
        function editFloor(floor) {
            document.getElementById('id_andar').value = floor.id_andar;
            document.getElementById('nome').value = floor.nome;
            document.getElementById('action').value = 'update';
            document.getElementById('exampleModalLabel').innerText = 'Atualizar Andar';
        }
    </script>

</body>

</html>
