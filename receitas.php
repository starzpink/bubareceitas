<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include './conn.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receitas por Corte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include 'navbar.php' ?>
    <div class="container-fluid text-center">
        <div class="row content receitacor">
            <div class="col-sm-2 sidenav"></div>
            <div class="col-sm-8 text-left">
                <h4>Índice de Receitas</h4>

                <div class="filtros">
                    <!-- filtros de corte de carne -->
                    <label for="corte-filter">Corte:</label>
                    <select id="corte-filter" onchange="filterContent()">
                        <option value="">Todos</option>
                        <?php
                        $sql_cortes = "SELECT id_corte, nome_corte FROM corte";
                        $result_cortes = mysqli_query($conn, $sql_cortes);

                        if (mysqli_num_rows($result_cortes) > 0) {
                            while ($corte = mysqli_fetch_assoc($result_cortes)) {
                                $id_corte = $corte['id_corte'];
                                $nome_corte = $corte['nome_corte'];
                                echo "<option value='$id_corte'>$nome_corte</option>";
                            }
                        }
                        ?>
                    </select>

                    <!-- filtros de subcorte de carne -->
                    <div id="subcorte-filter-container" style="display:none;">
                        <label for="subcorte-filter">Subcorte:</label>
                        <select id="subcorte-filter" onchange="filterContent()">
                            <option value="">Todos</option>
                        </select>
                    </div>
                </div>
                <div class="cards">
                    <?php
                    //consulta do banco de dados para recuperar os cortes
                    if (!$conn) {
                        die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
                    }

                    $sql_cortes = "SELECT id_corte, nome_corte FROM corte";
                    $result_cortes = mysqli_query($conn, $sql_cortes);

                    if (mysqli_num_rows($result_cortes) > 0) {
                        while ($corte = mysqli_fetch_assoc($result_cortes)) {
                            $id_corte = $corte['id_corte'];
                            $nome_corte = $corte['nome_corte'];


                            echo "<div class='card-receitas' data-corte='$id_corte'>";
                            echo "<div class='card-title'>$nome_corte</div>";

                            //recupera do banco de dados e exibe os cards de subcortes
                            $sql_subcortes = "SELECT id_subcorte, nome_subcorte FROM subcorte WHERE id_corte = $id_corte";
                            $result_subcortes = mysqli_query($conn, $sql_subcortes);

                            if (mysqli_num_rows($result_subcortes) > 0) {
                                echo "<div class='subcorte-cards-container'>";


                                while ($subcorte = mysqli_fetch_assoc($result_subcortes)) {
                                    $id_subcorte = $subcorte['id_subcorte'];
                                    $nome_subcorte = $subcorte['nome_subcorte'];

                                    //recupera o id e titulo das receitas do banco de dados a partir do subcorte
                                    $sql_receitas = "SELECT id_rec, nome_rec FROM receita WHERE id_subcorte = $id_subcorte";
                                    $result_receitas = mysqli_query($conn, $sql_receitas);

                                    echo "<div class='subcorte-card' data-subcorte='$id_subcorte'>";
                                    echo "<div class='subcorte-title'>$nome_subcorte</div>";

                                    //carrega os titulos das receitas e as exibe nos cards
                                    if (mysqli_num_rows($result_receitas) > 0) {
                                        while ($receita = mysqli_fetch_assoc($result_receitas)) {
                                            $id_receita = $receita['id_rec'];
                                            $nome_receita = $receita['nome_rec'];
                                            echo "<div class='receita'>";
                                            echo "<div class='receita-titulo'><a href='receita.php?id=$id_receita'>$nome_receita</a></div>";
                                            echo "</div>";
                                        }
                                    } else {
                                        echo "<div class='receita'>Nenhuma receita encontrada para este subcorte.</div>";
                                    }

                                    echo "</div>";
                                }

                                // para as receitas não pertencentes a um subcorte, são separadas em outro card
                                $sql_receitas_sem_subcorte = "SELECT id_rec, nome_rec FROM receita WHERE id_corte = $id_corte AND id_subcorte = 0 || id_subcorte IS NULL";
                                $result_receitas_sem_subcorte = mysqli_query($conn, $sql_receitas_sem_subcorte);

                                if (mysqli_num_rows($result_receitas_sem_subcorte) > 0) {

                                    echo "<div class='subcorte-card' data-subcorte=0>";
                                    echo "<div class='subcorte-title'>Outras</div>";

                                    while ($receita = mysqli_fetch_assoc($result_receitas_sem_subcorte)) {
                                        $id_receita = $receita['id_rec'];
                                        $nome_receita = $receita['nome_rec'];

                                        echo "<div class='receita'>";
                                        echo "<div class='receita-titulo'><a href='receita.php?id=$id_receita'>$nome_receita</a></div>";
                                        echo "</div>";
                                    }
                                }
                                echo "</div>";
                            } else {
                                //recupera do banco de dados as receitas de cortes sem subcortes
                                $sql_receitas = "SELECT id_rec, nome_rec FROM receita WHERE id_corte = $id_corte";
                                $result_receitas = mysqli_query($conn, $sql_receitas);

                                echo "<div class='recipe-cards-container'>";

                                if (mysqli_num_rows($result_receitas) > 0) {
                                    while ($receita = mysqli_fetch_assoc($result_receitas)) {
                                        $id_receita = $receita['id_rec'];
                                        $nome_receita = $receita['nome_rec'];
                                        echo "<div class='subcorte-card' data-subcorte=''>";
                                        echo "<div class='receita'>";
                                        echo "<div class='receita-titulo'><a href='receita.php?id=$id_receita'>$nome_receita</a></div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<div class='receita'>Nenhuma receita encontrada para este corte.</div>";
                                }

                                echo "</div>";
                            }

                            echo "</div>";
                        }
                    } else {
                        echo "Nenhum corte de carne encontrado.";
                    }

                    mysqli_close($conn);

                    ?>

                </div>
            </div>
            <div class="col-sm-2 sidenav"></div>
        </div>
    </div>
    <script>
        function filterSubcortes() {
            // Obtém o valor do corte selecionado no filtro.
            const corteId = document.getElementById('corte-filter').value;

            // Obtém os elementos do DOM para o container do subcorte e o seletor de subcorte.
            const subcorteFilterContainer = document.getElementById('subcorte-filter-container');
            const subcorteFilter = document.getElementById('subcorte-filter');

            // Reseta o conteúdo do filtro de subcorte para mostrar a opção padrão "Todos".
            subcorteFilter.innerHTML = '<option value="">Todos</option>';

            // Se um corte foi selecionado (corteId não é vazio)
            if (corteId) {
                // Faz uma requisição para o servidor para obter os subcortes relacionados ao corte selecionado.
                fetch(`getSubcorte.php?id_corte=${corteId}`)
                    .then(response => response.json())  // Converte a resposta em JSON
                    .then(data => {
                        // Se a resposta contém subcortes
                        if (data.length > 0) {
                            // Mostra o container do filtro de subcorte.
                            subcorteFilterContainer.style.display = 'block';

                            // Adiciona cada subcorte como uma opção no seletor de subcorte.
                            data.forEach(subcorte => {
                                const option = document.createElement('option');
                                option.value = subcorte.id_subcorte;
                                option.textContent = subcorte.nome_subcorte;
                                subcorteFilter.appendChild(option);
                            });
                        } else {
                            // Se não houver subcortes, esconde o container do filtro de subcorte.
                            subcorteFilterContainer.style.display = 'none';
                        }
                    })
                    // Captura e exibe qualquer erro que ocorra na requisição.
                    .catch(error => console.error('Erro ao buscar subcortes:', error));
            } else {
                // Se nenhum corte foi selecionado, esconde o container do filtro de subcorte.
                subcorteFilterContainer.style.display = 'none';
            }
        }

        function filterContent() {
            // Obtém os valores dos filtros de corte e subcorte.
            const corteId = document.getElementById('corte-filter').value;
            const subcorteId = document.getElementById('subcorte-filter').value;

            // Seleciona todos os elementos com a classe 'card-receitas'.
            const cards = document.querySelectorAll('.card-receitas');

            cards.forEach(card => {
                // Obtém o valor do atributo data-corte do card.
                const cardCorteId = card.getAttribute('data-corte');

                // Seleciona todos os elementos dentro do card que possuem a classe 'subcorte-card'.
                const subcorteCards = card.querySelectorAll('.subcorte-card');

                // Determina se o card deve ser exibido, verificando se o corte do card corresponde ao corte selecionado ou se nenhum corte foi selecionado.
                let showCard = !corteId || cardCorteId === corteId;

                // Se o card deve ser exibido, verifica se os subcortes dentro dele devem ser exibidos, com base no subcorte selecionado.
                if (showCard) {
                    subcorteCards.forEach(subcorteCard => {
                        const cardSubcorteId = subcorteCard.getAttribute('data-subcorte');
                        subcorteCard.style.display = (!subcorteId || cardSubcorteId === subcorteId) ? 'block' : 'none';
                    });
                }

                // Exibe ou esconde o card com base no resultado das verificações acima.
                card.style.display = showCard ? 'block' : 'none';
            });
        }

        function getUrlParameter(name) {
            // Converte o nome do parâmetro para escapar caracteres especiais.
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');

            // Cria uma expressão regular para capturar o valor do parâmetro na URL.
            const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            const results = regex.exec(location.search);

            // Retorna o valor decodificado do parâmetro ou uma string vazia se o parâmetro não existir.
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }


        document.addEventListener('DOMContentLoaded', () => {
            // Obtém o valor do parâmetro 'id_corte' da URL.
            const corteId = getUrlParameter('id_corte');

            // Se o parâmetro 'id_corte' existir, ele preenche o filtro de corte com esse valor, carrega os subcortes associados e filtra o conteúdo da página.
            if (corteId) {
                document.getElementById('corte-filter').value = corteId;
                filterSubcortes();
                filterContent();
            }
        });

        // Adiciona eventos de 'change' aos filtros de corte e subcorte para acionar as funções de filtragem quando os valores forem alterados.
        document.getElementById('corte-filter').addEventListener('change', filterSubcortes);
        document.getElementById('subcorte-filter').addEventListener('change', filterContent);

    </script>
</body>

</html>