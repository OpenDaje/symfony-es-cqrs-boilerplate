<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Command;

class ChangeUserPassword
{
    private string $userId;

    private string $password;

    public function __construct(string $userId, string $password)
    {
        $this->userId = $userId;
        $this->password = $password;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
