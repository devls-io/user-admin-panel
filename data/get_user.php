<?php 

function listUserById(PDO $pdo, $id) {
   
    $sql = 'SELECT * FROM usuarios WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}