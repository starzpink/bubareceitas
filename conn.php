<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bubareceitas";

$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn) {
    die("Falha na conexão:" . $conn->connect_error);
}
?>

