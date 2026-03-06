<?php 

$temaSalvo = $_COOKIE['theme'] ?? 'light';
$classeBody = ($temaSalvo === 'dark') ? 'dark' : '';
$iconeBotao = ($temaSalvo === 'dark') ? '☀️' : '🌙';
?>

<button id="theme-toggle" class="theme-btn">
    Alterar Tema <?= $iconeBotao ?>
</button>