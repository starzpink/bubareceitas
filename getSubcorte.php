<?php
//carregamento dos subcortes depois de selecionado o corte

include './conn.php';

header('Content-Type: application/json');

if (isset($_GET['id_corte'])) {
    $id_corte = $_GET['id_corte'];

    if ($conn) {
        $sql_subcortes = "SELECT id_subcorte, nome_subcorte FROM subcorte WHERE id_corte = $id_corte";
        $result_subcortes = mysqli_query($conn, $sql_subcortes);

        $subcortes = array();
        if (mysqli_num_rows($result_subcortes) > 0) {
            while ($subcorte = mysqli_fetch_assoc($result_subcortes)) {
                $subcortes[] = $subcorte;
            }
        }
        echo json_encode($subcortes);
        mysqli_close($conn);
    } else {
        echo json_encode(array("error" => "Database connection failed"));
    }
} else {
    echo json_encode(array("error" => "No id_corte provided"));
}
?>
