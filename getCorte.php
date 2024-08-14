<?php
include './conn.php';

$ini = isset($_GET['page']) ? ($_GET['page'] - 1) * 10 : 0;

$totalQuery = $conn->query('SELECT COUNT(*) AS total FROM corte');
if (!$totalQuery) {

    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(['error' => 'Erro ao recuperar o total de registros']));
}

$total = mysqli_fetch_array($totalQuery);

$sql = 'SELECT * FROM corte LIMIT ' . $ini . ', 20';
$result = $conn->query($sql);
if (!$result) {
    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(['error' => 'Erro ao recuperar os dados do corte']));
}

$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

$conn->close();

header('Content-type: application/json');

echo json_encode(['data' => $rows, 'total' => $total[0]]);
?>
