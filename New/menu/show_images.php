<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="240">
    <title>Exibição de Imagens e Lista de Turmas</title>
    <link rel="stylesheet" href="style01.css">
</head>
<body>
<div class="header1">
    <span id="current-date" class="date"></span>
    <span id="current-time" class="time"></span>
    <img src="../imagens/senai.png" alt="Logo" class="logo">
</div>

<div class="main-content">
    <div class="table-size">
        <div class="table-container" style="overflow-x: auto;">
            <table id="turma-table">
                <thead>
                <tr>
                    
                    <th>TEMA</th>
                    <th>ÁREA</th>
                    <th>SALA</th>
                    <th>ANDAR</th>
                    <th>RESPONSAVEL</th>
                    <th>INÍCIO</th>
                    <th>FIM</th>
		    
                </tr>
                </thead>
                <tbody id="turmas-body">
                <!-- Conteúdo da tabela de turmas será inserido aqui via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="image-display-container"> -->
        <?php
        $dir = "imagens/";
        $images = array_diff(scandir($dir), array('.', '..'));

        if (count($images) > 0) {
            $index = (time() / 10) % count($images);
            $image = array_values($images)[$index];
            echo "<img src='$dir$image' alt='Imagem' id='image-preview'>";
        } else {
            echo "Nenhuma imagem encontrada.";
        }
        ?>
   
</div>

<script src="script.js"></script>

</body>
</html>
