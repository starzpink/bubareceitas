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
        <div class="row content">
            <div class="col-sm-2 sidenav"></div>
            <div class="col-sm-8 text-left">
                <h4>Índice de Receitas</h4>
                <div class="filtros">
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

                    <div id="subcorte-filter-container" style="display:none;">
                        <label for="subcorte-filter">Subcorte:</label>
                        <select id="subcorte-filter" onchange="filterContent()">
                            <option value="">Todos</option>
                        </select>
                    </div>
                </div>
                <div class="cards">
                    <?php

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

                            $sql_subcortes = "SELECT id_subcorte, nome_subcorte FROM subcorte WHERE id_corte = $id_corte";
                            $result_subcortes = mysqli_query($conn, $sql_subcortes);

                            if (mysqli_num_rows($result_subcortes) > 0) {
                                echo "<div class='subcorte-cards-container'>";

                                while ($subcorte = mysqli_fetch_assoc($result_subcortes)) {
                                    $id_subcorte = $subcorte['id_subcorte'];
                                    $nome_subcorte = $subcorte['nome_subcorte'];

                                    $sql_receitas = "SELECT id_rec, nome_rec FROM receita WHERE id_subcorte = $id_subcorte";
                                    $result_receitas = mysqli_query($conn, $sql_receitas);

                                    echo "<div class='subcorte-card' data-subcorte='$id_subcorte'>";
                                    echo "<div class='subcorte-title'>$nome_subcorte</div>";

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

                                $sql_receitas_sem_subcorte = "SELECT id_rec, nome_rec FROM receita WHERE id_corte = $id_corte AND id_subcorte IS NULL";
                                $result_receitas_sem_subcorte = mysqli_query($conn, $sql_receitas_sem_subcorte);

                                if (mysqli_num_rows($result_receitas_sem_subcorte) > 0) {
                                    while ($receita = mysqli_fetch_assoc($result_receitas_sem_subcorte)) {
                                        $id_receita = $receita['id_rec'];
                                        $nome_receita = $receita['nome_rec'];
                                        echo "<div class='subcorte-card' data-subcorte=''>";
                                        echo "<div class='subcorte-title'>Receitas sem subcorte</div>";
                                        echo "<div class='receita'>";
                                        echo "<div class='receita-titulo'><a href='receita.php?id=$id_receita'>$nome_receita</a></div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }

                                echo "</div>";
                            } else {
                                $sql_receitas = "SELECT id_rec, nome_rec FROM receita WHERE id_corte = $id_corte";
                                $result_receitas = mysqli_query($conn, $sql_receitas);

                                echo "<div class='recipe-cards-container'>";

                                if (mysqli_num_rows($result_receitas) > 0) {
                                    while ($receita = mysqli_fetch_assoc($result_receitas)) {
                                        $id_receita = $receita['id_rec'];
                                        $nome_receita = $receita['nome_rec'];
                                        echo "<div class='receita'>";
                                        echo "<div class='receita-titulo'><a href='receita.php?id=$id_receita'>$nome_receita</a></div>";
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
            const corteId = document.getElementById('corte-filter').value;
            const subcorteFilterContainer = document.getElementById('subcorte-filter-container');
            const subcorteFilter = document.getElementById('subcorte-filter');

            subcorteFilter.innerHTML = '<option value="">Todos</option>';

            if (corteId) {
                fetch(`getSubcorte.php?id_corte=${corteId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            subcorteFilterContainer.style.display = 'block';
                            data.forEach(subcorte => {
                                const option = document.createElement('option');
                                option.value = subcorte.id_subcorte;
                                option.textContent = subcorte.nome_subcorte;
                                subcorteFilter.appendChild(option);
                            });
                        } else {
                            subcorteFilterContainer.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Erro ao buscar subcortes:', error));
            } else {
                subcorteFilterContainer.style.display = 'none';
            }
        }

        function filterContent() {
            const corteId = document.getElementById('corte-filter').value;
            const subcorteId = document.getElementById('subcorte-filter').value;
            const cards = document.querySelectorAll('.card-receitas');

            cards.forEach(card => {
                const cardCorteId = card.getAttribute('data-corte');
                const subcorteCards = card.querySelectorAll('.subcorte-card');

                let showCard = !corteId || cardCorteId === corteId;

                if (showCard) {
                    subcorteCards.forEach(subcorteCard => {
                        const cardSubcorteId = subcorteCard.getAttribute('data-subcorte');
                        subcorteCard.style.display = (!subcorteId || cardSubcorteId === subcorteId) ? 'block' : 'none';
                    });
                }

                card.style.display = showCard ? 'block' : 'none';
            });
        }

        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            const results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const corteId = getUrlParameter('id_corte');
            if (corteId) {
                document.getElementById('corte-filter').value = corteId;
                filterSubcortes();
                filterContent();
            }
        });

        document.getElementById('corte-filter').addEventListener('change', filterSubcortes);
        document.getElementById('subcorte-filter').addEventListener('change', filterContent);
    </script>
</body>

</html>