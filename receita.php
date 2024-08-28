<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include './conn.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buba Receitas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!--  Insere icones do boxicons -->
</head>

<body>
    <?php include 'navbar.php' ?>
    <div class="container-fluid text-center">
        <div class="row content receitacor">
            <div class="col-sm-2 sidenav"></div>
            <div class="col-sm-8 text-left">

                <?php
                // Conexão com o banco de dados
                if (!$conn) {
                    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
                }

                // Obtém o ID da receita da URL
                $id_rec = isset($_GET['id']) ? intval($_GET['id']) : 0;

                // Consulta SQL para recuperar os detalhes da receita
                $sql_receita = "SELECT nome_rec, ingredientes, modo_pr, tempo_pr, grau_dif, sugestao_pr FROM receita WHERE id_rec = $id_rec";
                $result_receita = mysqli_query($conn, $sql_receita);

                // Verifica se encontrou a receita
                if (mysqli_num_rows($result_receita) > 0) {
                    $receita = mysqli_fetch_assoc($result_receita);

                    // Exibe os detalhes da receita
                    echo "<h1>" . htmlspecialchars($receita['nome_rec']) . "</h1>";
                    echo "<div class='1'>";
                    echo "<div class='2'>";
                    echo "<p class='texto-receita'> <i class='bx bx-timer bx-md'></i> Tempo de Preparo: " . nl2br(htmlspecialchars($receita['tempo_pr'])) . "</p>";
                    echo "</div>";
                    echo "<div class='5'>";
                    echo "<p class='texto-receita'> <i class='bx bx-bar-chart bx-md'></i> Grau de dificuldade: ";

                    //exibe o rotulo com base no id
                    if ($receita['grau_dif']==1){
                        echo "Fácil</p>";
                    } else if($receita['grau_dif']==2){
                        echo "Médio</p>";
                    } else if($receita['grau_dif']==3){
                        echo "Difícil</p>";
                    }
                    echo "<h4 class='subt-receita'>Ingredientes</h4>";
                    echo "<div class='3'>";
                    echo "<p class='texto-receita'>" . nl2br(htmlspecialchars($receita['ingredientes'])) . "</p>";
                    echo "</div>";

                    echo "<h4 class='subt-receita'>Modo de Preparo</h4>";
                    echo "<div class='4'>";
                    echo "<p class='texto-receita'>" . nl2br(htmlspecialchars($receita['modo_pr'])) . "</p>";
                    echo "</div>";

                } else {
                    echo "<p>Receita não encontrada.</p>";
                }

                // Fecha a conexão com o banco de dados
                mysqli_close($conn);
                ?>
            </div>
            <div class="col-sm-2 sidenav"></div>
        </div>
    </div>

</body>

</html>