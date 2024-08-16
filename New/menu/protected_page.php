<?php
include_once "session_checker.php"; // Inclua o verificador de sessão no início de todas as páginas que precisam de autenticação
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Página Protegida</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>!</h1>

    <form action="../controles/logout.php" method="post">
        <button type="submit" name="btnLogout">Sair</button>
    </form>
</body>
</html>
