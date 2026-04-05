<?php 

function listAllUsers(PDO $pdo, int $limit = 10, int $offset = 0, string $search = '') : array{
        // Por segurança passamos :limit e :offset

       // Se tiver busca usamos WHERE + LIKE. Se não, SQL normal
       $sql = "SELECT * FROM usuarios";

       if(!empty($search)){
        $sql .= " WHERE nome LIKE :search";
       }

       $sql .= " LIMIT :limit OFFSET :offset";


        $stmt = $pdo->prepare($sql);

        // Bind se tivermos o search

        if(!empty($search)){
                $stmt->bindValue(':search' , "%$search%", PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit' , $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
};

// Função auxiliar para contar o total de pessoas
// Para saber o total de páginas

function countTotalUsers(PDO $pdo, string $search = '') : int{
        $sql = "SELECT COUNT(*) FROM usuarios";

        if(!empty($search)){
                $sql .= " WHERE nome LIKE :search";
        }

        $stmt = $pdo->prepare($sql);

        if(!empty($search)){
                $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();

};
?>