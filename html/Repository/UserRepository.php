<?php

namespace Repository;

use Model\User;
use Repository\Interface\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {

    public function findAll($pdo): array{
        $users = [];
        $sql = " SELECT * FROM Users AS User";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $createdAtDateTime = new \DateTime($row['created_at']);
            $updatedAtDateTime = new \DateTime($row['updated_at']);

            $user = new User(
                $row['id'],
                $row['name'],
                $row['email'],
                $row['password'],
                $row['department_id'],
                $row['rank'],
                $createdAtDateTime,
                $updatedAtDateTime
            );

            $users[] = $user;
        }
        
        return $users;
    }

    public function findByUid($pdo, String $uid): ?User {
        $stmt = $pdo->prepare('SELECT * FROM Users WHERE id = ?');
        $stmt->execute([$uid]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $createdAtDateTime = new \DateTime($row['created_at']);
        $updatedAtDateTime = new \DateTime($row['updated_at']);

        $user = new User(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['password'],
            $row['department_id'],
            $row['rank'],
            $createdAtDateTime,
            $updatedAtDateTime
        );

        return $user;
    }
}

?>