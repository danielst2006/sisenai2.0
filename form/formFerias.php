<?php

session_start();
if(isset($_SESSION['login'])){
    if($_SESSION['tipo_usuario'] == "COPED" || $_SESSION['tipo_usuario'] == "ADM"){

    } else {
        header('Location: ../form/menu.php');
    }
} else {
    header('Location: ../form/login.php');
}

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
$result_curso = "SELECT COUNT(*) AS total FROM ferias f 
                 JOIN tipo_ferias tf ON f.tipoFerias_id = tf.id_tipoferias 
                 WHERE tf.tipo LIKE '%$pesquisa%'";
$consulta = mysqli_query($conn, $result_curso);

if ($consulta === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$total_curso = mysqli_fetch_assoc($consulta)['total'];
$num_pagina = ceil($total_curso / $quantidade_pg);

// Consulta para buscar as férias
$sql = "SELECT f.idFerias, f.data_inicio, f.data_final, f.tipoFerias_id, tf.tipo 
FROM ferias f 
JOIN tipo_ferias tf ON f.tipoFerias_id = tf.id_tipoferias 
WHERE f.idFerias LIKE CONCAT('%', '$pesquisa', '%') 
   OR f.data_inicio LIKE CONCAT('%', '$pesquisa', '%') 
   OR f.data_final LIKE CONCAT('%', '$pesquisa', '%') 
   OR tf.tipo LIKE CONCAT('%', '$pesquisa', '%') 
LIMIT $inicio, $quantidade_pg"; // Adicionando LIMIT para a paginação
$resultado = mysqli_query($conn, $sql);

// Função para formatar a data para o formato brasileiro
function formatarData($data) {
    if ($data) {
        $dataObj = new DateTime($data);
        return $dataObj->format('d/m/Y');
    }
    return '';
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Férias</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Férias</h1>
                <div style="overflow-x:auto;">
                    <div class="pesquisa">
                        <form action="formFerias.php" method="post" class="mb-4">
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
                                <!-- Botão para abrir o modal de adição de novas férias -->
                                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Férias</button>
                                
                                <!-- Botão Tipo de Férias -->
                                <a href="formTipoFerias.php" class="btn btn-secondary">Tipo de Férias</a>
                            </div>
                        </div>

                        <!-- Tabela de férias -->
                        <table class='table rounded-table'>
                            <thead>
                                <tr>
                                    <th class="text-center">Código</th>
                                    <th class="text-center">Data Início</th>
                                    <th class="text-center">Data Fim</th>
                                    <th class="text-center">Tipo de Férias</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Verifica se há férias e exibe cada uma em uma linha da tabela
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                ?>
                                        <tr>
                                            <td class='text-center'><?= htmlspecialchars($row['idFerias']); ?></td>
                                            <td class='text-center'><?= formatarData($row['data_inicio']); ?></td>
                                            <td class='text-center'><?= formatarData($row['data_final']); ?></td>
                                            <td class='text-center'><?= htmlspecialchars($row['tipo']); ?></td>
                                            <td class='text-center'>
                                                <div class='d-flex justify-content-center'>
                                                    <!-- Botão de edição -->
                                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editFerias(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>

                                                    <!-- Formulário de exclusão -->
                                                    <form action='../controls/cadastrarFerias.php' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='idFerias' value='<?= htmlspecialchars($row['idFerias']); ?>'>
                                                        <input type='hidden' name='action' value='delete'>

                                                        <!-- Botão de exclusão com confirmação -->
                                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir estas férias?")'><i class='fas fa-times'></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <!-- Mensagem quando não há férias -->
                                    <tr>
                                        <td colspan='5'>Nenhuma férias encontrada</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($pagina <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina > 1) ? 'formFerias.php?pagina=' . ($pagina - 1) : '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                                    <li class="page-item <?php echo ($pagina == $i) ? 'active' : ''; ?>"><a class="page-link" href="formFerias.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php } ?>
                                <li class="page-item <?php echo ($pagina >= $num_pagina) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo ($pagina < $num_pagina) ? 'formFerias.php?pagina=' . ($pagina + 1) : '#'; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Modal para adicionar/editar férias -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Férias</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="feriasForm" action="../controls/cadastrarFerias.php" method="POST">
                                            <input type="hidden" id="idFerias" name="idFerias">
                                            <input type="hidden" id="action" name="action" value="add">

                                            <div class="mb-3">
                                                <label for="data_inicio" class="form-label">Data Início</label>
                                                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="data_final" class="form-label">Data Fim</label>
                                                <input type="date" class="form-control" id="data_final" name="data_final" required>
                                            </div>

                                            <script>
                                                document.getElementById('data_final').addEventListener('change', function() {
                                                    var dataInicio = document.getElementById('data_inicio').value;
                                                    var dataFinal = this.value;

                                                    if (dataInicio && dataFinal) {
                                                        if (dataInicio > dataFinal) {
                                                            alert('A data de início não pode ser posterior à data de fim.');
                                                            this.value = ''; // Limpa a data de fim se a condição for inválida
                                                        }
                                                    }
                                                });
                                            </script>
                                            <div class="mb-3">
                                                <label for="tipoFerias" class="form-label">Tipo de Férias</label>
                                                <select class="form-select" id="tipoFerias" name="tipoFerias_id" required>
                                                    <?php
                                                    // Consulta para listar tipos de férias
                                                    $query = "SELECT id_tipoferias, tipo FROM tipo_ferias";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['id_tipoferias']}'>{$row['tipo']}</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>Nenhum tipo disponível</option>";
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
        function editFerias(data) {
            document.getElementById('idFerias').value = data.idFerias;
            document.getElementById('data_inicio').value = data.data_inicio;
            document.getElementById('data_final').value = data.data_final;
            document.getElementById('tipoFerias').value = data.tipoFerias_id;
            document.getElementById('action').value = 'update';
            document.querySelector('.modal-title').textContent = 'Editar Férias';
        }

        // Função para limpar o formulário no modal para adicionar novas férias
        function clearForm() {
            document.getElementById('feriasForm').reset();
            document.getElementById('idFerias').value = '';
            document.getElementById('action').value = 'add';
            document.querySelector('.modal-title').textContent = 'Adicionar Nova Férias';
        }

        // Função para submeter o formulário
        function submitForm() {
            document.getElementById('feriasForm').submit();
        }
    </script>
</body>

        <?php mysqli_close($conn)?>

</html>
