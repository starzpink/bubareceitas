<?php
//carregamento das receitas do banco de dados para o site

include './conn.php';

$id_corte = isset($_GET['id_corte']) ? $_GET['id_corte'] : '';
$id_subcorte = isset($_GET['id_subcorte']) ? $_GET['id_subcorte'] : '';

if ($conn) {
    $query = "SELECT id_rec, nome_rec FROM receita WHERE 1=1";

    if (!empty($id_corte)) {
        $query .= " AND id_corte = " . mysqli_real_escape_string($conn, $id_corte);
    }

    if (!empty($id_subcorte)) {
        $query .= " AND id_subcorte = " . mysqli_real_escape_string($conn, $id_subcorte);
    }

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($receita = mysqli_fetch_assoc($result)) {
            $id_receita = $receita['id_rec'];
            $nome_receita = $receita['nome_rec'];
            echo "<div class='receita'>";
            echo "<div class='receita-titulo'><a href='receita.php?id=$id_receita'>$nome_receita</a></div>";
            echo "</div>";
        }
    } else {
        echo "<div class='receita'>Nenhuma receita encontrada.</div>";
    }

    mysqli_close($conn);
} else {
    echo "Erro na conexão com o banco de dados.";
}
?>