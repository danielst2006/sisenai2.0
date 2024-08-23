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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro de Sala</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="bg-light text-dark">

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center">Sala</h1>
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
                        <!-- Botão para abrir o modal de adição de nova sala -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearForm()">Adicionar Nova Sala</button>
                    </div>
                </div>

                <!-- Tabela de salas -->
                <table class='table rounded-table'>
                    <thead>
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Andar</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Conexão com o banco de dados
                        include_once '../bd/conn.php';

                        // Consulta SQL para buscar salas junto com o nome do andar
                        $sql = "SELECT s.id_sala, s.nome as nome_sala, andar.nome as nome_andar, andar.id_andar FROM salas s JOIN andar ON andar_id = andar.id_andar";
                        $resultado = mysqli_query($conn, $sql);

                        // Verifica se há salas e exibe cada uma em uma linha da tabela
                        if (mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                        ?>
                        <tr>
                            <td class='text-center'><?= htmlspecialchars($row['id_sala']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['nome_sala']); ?></td>
                            <td class='text-center'><?= htmlspecialchars($row['nome_andar']); ?></td>
                            <td class='text-center'>
                                <div class='d-flex justify-content-center'>
                                    <!-- Botão de edição -->
                                    <button class='btn action-button edit-button me-2' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='editRoom(<?= json_encode($row); ?>)'><i class='fas fa-pencil-alt'></i></button>
                                    
                                    <!-- Formulário de exclusão -->
                                    <form action='../controls/cadastrarSala.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='id_sala' value='<?= htmlspecialchars($row['id_sala']); ?>'>
                                        <input type='hidden' name='action' value='delete'>

                                        <!-- Botão de exclusão com confirmação -->
                                        <button type='submit' class='btn action-button delete-button' onclick='return confirm("Tem certeza que deseja excluir esta sala?")'><i class='fas fa-times'></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                        ?>
                        
                        <!-- Mensagem quando não há salas -->
                        <tr><td colspan='4'>Nenhuma sala encontrada</td></tr>
                        <?php 
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Modal para adicionar/editar salas -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Sala</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <div class="modal-body">
                        <form id="roomForm" action="../controls/cadastrarSala.php" method="POST">
                          <input type="hidden" id="id_sala" name="id_sala">
                          <input type="hidden" id="action" name="action" value="add">

                          <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Sala</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                          </div>

                          <div class="mb-3">
                            <label for="andar_id" class="form-label">Andar</label>
                            <select class="form-control" id="andar_id" name="andar_id" required>
                                <?php
                                // Consulta SQL para buscar todos os andares
                                $sql_andar = "SELECT id_andar, nome FROM andar";
                                $resultado_andar = mysqli_query($conn, $sql_andar);
                                ?>

                                <option value="" disabled selected>Selecione o andar</option>
                                <?php
                                // Preenche o dropdown com os andares disponíveis
                                if (mysqli_num_rows($resultado_andar) > 0) {
                                    while ($andar = mysqli_fetch_assoc($resultado_andar)) {
                                        echo "<option value='" . htmlspecialchars($andar['id_andar']) . "'>" . htmlspecialchars($andar['nome']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
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
// Função para limpar o formulário ao adicionar nova sala
function clearForm() {
    document.getElementById('roomForm').reset();
    document.getElementById('id_sala').value = ''; // ID não é necessário para adição, mas limpamos o campo para edições futuras
    document.getElementById('action').value = 'add';
    document.getElementById('exampleModalLabel').innerText = 'Adicionar Nova Sala';
}

// Função para preencher o formulário ao editar uma sala
function editRoom(room) {
    document.getElementById('id_sala').value = room.id_sala;
    document.getElementById('nome').value = room.nome_sala;
    document.getElementById('andar_id').value = room.id_andar; // Corrigido para usar o id_andar
    document.getElementById('action').value = 'update';
    document.getElementById('exampleModalLabel').innerText = 'Atualizar Sala';
}
</script>

</body>

    <?php mysqli_close($conn)?>

</html>
