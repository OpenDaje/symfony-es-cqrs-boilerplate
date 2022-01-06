<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Command;

class RegisterUser
{
    private string $email;

    private string $hashedPassword;

    private string $userId;

    public function __construct(string $email, string $hashedPassword, string $userId)
    {
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
        $this->userId = $userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
