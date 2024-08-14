<?php

include 'conn.php';
include_once './adm.php';
session_start();

header('Content-type: application/json');
$sql = "insert into receita(nome_rec, ingredientes, modo_pr, tempo_pr, grau_dif, sugestao_pr, id_corte, id_subcorte) values('"
    . $_POST['nome_rec'] . "',
     '" . $_POST['ingredientes'] . "',
     '" . $_POST['modo_pr'] . "',
     '" . $_POST['tempo_pr'] . "',
     '" . $_POST['grau_dif'] . "',
     '" . $_POST['sugestao_pr'] . "',
     " . $_POST['id_corte'] . " ,
     " . $_POST['id_subcorte'] . ")";
if ($conn->query($sql) === TRUE) {
    $msg = 'Receita criada com sucesso!';
} else {
    $msg = 'Error' . $sql . '<br>' . $conn->error;
}

$conn->close();

echo json_encode(['msg' => $msg]);

