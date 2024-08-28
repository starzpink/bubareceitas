<?php
include './conn.php';
include_once './adm.php';
session_start();

header('Content-type: application/json');

// valida dados POST
if (empty($_POST['nome_rec']) || empty($_POST['ingredientes']) || empty($_POST['modo_pr']) || empty($_POST['tempo_pr']) || !isset($_POST['grau_dif']) || empty($_POST['sugestao_pr']) || !isset($_POST['id_corte']) || !isset($_POST['id_subcorte']) || !isset($_POST['id_rec'])) {
    echo json_encode(['msg' => 'Dados inválidos ou faltando.']);
    exit;
}

// prepara as variaveis para enviar via post
$nome_rec = $_POST['nome_rec'];
$ingredientes = $_POST['ingredientes'];
$modo_pr = $_POST['modo_pr'];
$tempo_pr = $_POST['tempo_pr'];
$grau_dif = intval($_POST['grau_dif']);
$sugestao_pr = $_POST['sugestao_pr'];
$id_corte = intval($_POST['id_corte']);
$id_subcorte = intval($_POST['id_subcorte']);
$id_rec = intval($_POST['id_rec']);

// Prepare SQL statement
$sql = "UPDATE receita 
        SET nome_rec = ?, 
            ingredientes = ?, 
            modo_pr = ?, 
            tempo_pr = ?, 
            grau_dif = ?, 
            sugestao_pr = ?, 
            id_corte = ?, 
            id_subcorte = ? 
        WHERE id_rec = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['msg' => 'Erro ao preparar a consulta: ' . $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param('ssssisiii', $nome_rec, $ingredientes, $modo_pr, $tempo_pr, $grau_dif, $sugestao_pr, $id_corte, $id_subcorte, $id_rec);

// Execute statement
if ($stmt->execute()) {
    $msg = 'Receita atualizada com sucesso!';
} else {
    $msg = "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode(['msg' => $msg]);
?>