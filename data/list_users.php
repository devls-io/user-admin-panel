<?php 

function listAllUsers(PDO $pdo, int $limit = 10, int $offset = 0) : array{
        // Por segurança passamos :limit e :offset

        $sql = "SELECT * FROM usuarios LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':limit' , $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
};

// Função auxiliar para contar o total de pessoas
// Para saber o total de páginas

function countTotalUsers(PDO $pdo) : int{
        $sql = "SELECT COUNT(*) FROM usuarios";
        return (int) $pdo->query($sql)->fetchColumn();
};

?>