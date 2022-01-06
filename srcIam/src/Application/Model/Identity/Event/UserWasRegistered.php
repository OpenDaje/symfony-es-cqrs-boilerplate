<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\Event;

class UserWasRegistered
{
    private string $userId;

    private string $email;

    private string $hashedPassword;

    public function __construct(string $userId, string $email, string $hashedPassword)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }
}
