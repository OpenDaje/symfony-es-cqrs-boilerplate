<?php declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Authentication;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $email;

    private array $roles = [];

    private string $password;

    public function __construct(string $password, string $email, array $roles = [])
    {
        $this->email = $email;
        $this->roles = $roles;
        $this->password = $password;
    }

    public static function createForRegistration(): self
    {
        return new self('', '', []);
    }

    public static function createFromReadModel(string $email, string $password): self
    {
        return new self($password, $email, []);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername()
    {
        return $this->email;
    }
}
