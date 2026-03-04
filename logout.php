<?php 
session_start(); // Precisa abrir para poder fechar
session_unset(); // Limpa as variáveis (tira o ID e o Nome)
session_destroy();  // Destrói a sessão por completo

header("Location: login.php");
exit;
?>