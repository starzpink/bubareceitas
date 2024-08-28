<?php
//função para saída do usuario

session_start();
session_destroy();
header("Location: index.php");
exit;

?>