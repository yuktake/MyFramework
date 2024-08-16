<?php
namespace Model;

use Libs\Model\Base;
use DateTime;

readonly class User extends Base {
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private int $departmentId;
    private int $rank;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        int $id, 
        string $name, 
        string $email, 
        string $password,
        int $departmentId,
        int $rank,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->departmentId = $departmentId;
        $this->rank = $rank;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getDepartmentId(): int {
        return $this->departmentId;
    }

    public function getRank(): int {
        return $this->rank;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }
}