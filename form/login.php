<?php
// Inclui o arquivo de menuIndex.php
include_once '../head/menuIndex.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body class="bg-light text-dark">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h1 class="text-center mt-5">Login</h1>

                <?php
                // Exibe uma mensagem de erro com base no parâmetro de status na URL
                if (isset($_GET['status']) && $_GET['status'] == 'error') {
                    echo '<div id="alertBox" class="alert alert-danger text-center" role="alert">Login ou senha inválidos!</div>';
                }
                ?>

                <script>
                    // Esconde a mensagem de alerta após 5 segundos
                    setTimeout(function() {
                        var alertBox = document.getElementById('alertBox');
                        if (alertBox) {
                            alertBox.style.display = 'none';
                        }
                    }, 5000); 
                </script>

                <!-- Formulário de Login -->
                <form action="../controls/validarLogin.php" method="POST" class="p-4 border rounded bg-white shadow-sm">
                    <div class="mb-3">
                        <label for="login" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

</body>

</html>
