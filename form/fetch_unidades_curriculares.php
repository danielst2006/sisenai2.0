<?php
include_once "../bd/conn.php";

if (isset($_POST['turma_id'])) {
    $turma_id = $_POST['turma_id'];

    // Supondo que você tem uma relação entre turmas e cursos
    $query = "
        SELECT uc.idunidade_curricular, uc.nome_unidade FROM unidade_curricular uc JOIN 
        cursos c ON uc.curso_id = c.curso_id JOIN turmas t ON t.curso_id = c.curso_id
        WHERE t.turma_id = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $turma_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['idunidade_curricular']}'>{$row['nome_unidade']}</option>";
            }
        } else {
            echo "<option value=''>Nenhuma unidade curricular encontrada</option>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<option value=''>Erro na consulta</option>";
    }

    mysqli_close($conn);
}
?>
