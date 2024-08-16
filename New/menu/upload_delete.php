<?php
$target_dir = "imagens/";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Upload de imagem
    if (isset($_FILES["fileToUpload"])) {
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se é uma imagem
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "O arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        // Verifica se o arquivo já existe
        if (file_exists($target_file)) {
            echo "Desculpe, o arquivo já existe.";
            $uploadOk = 0;
        }

        // Verifica o tamanho do arquivo
       

        // Permite certos formatos de arquivo
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Desculpe, apenas arquivos JPG, JPEG, PNG & GIF são permitidos.";
            $uploadOk = 0;
        }

        // Verifica se $uploadOk está definido como 0 por algum erro
        if ($uploadOk == 0) {
            echo "Desculpe, seu arquivo não foi enviado.";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "O arquivo " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " foi enviado.";
            } else {
                echo "Desculpe, houve um erro ao enviar seu arquivo.";
            }
        }
    }

    // Delete de imagem
    if (isset($_POST["deleteFile"])) {
        $fileToDelete = $target_dir . $_POST["deleteFile"];
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
            echo "O arquivo " . htmlspecialchars($_POST["deleteFile"]) . " foi deletado.";
        } else {
            echo "Desculpe, o arquivo não existe.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Upload e Delete de Imagens</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Upload de Imagem</h2>
    <form action="upload_delete.php" method="post" enctype="multipart/form-data">
        Selecione uma imagem para enviar:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>

    <h2>Deletar Imagem</h2>
    <form action="upload_delete.php" method="post">
        <label for="deleteFile">Selecione o arquivo para deletar:</label>
        <select name="deleteFile" id="deleteFile">
            <?php
            $files = array_diff(scandir($target_dir), array('.', '..'));
            foreach ($files as $file) {
                echo "<option value=\"$file\">$file</option>";
            }
            ?>
        </select>
        <input type="submit" value="Delete Image" name="delete">
    </form>
</body>
</html>
