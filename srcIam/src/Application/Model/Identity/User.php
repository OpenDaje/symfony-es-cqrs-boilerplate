<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity;

use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;
use IdentityAccess\Application\Model\Identity\Command\RegisterUser;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;

#[EventSourcingAggregate]
class User
{
    public const REGISTER_USER = "user.registerUser";

    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private string $id;

    private string $email;

    private string $hashedPassword;

    #[CommandHandler(self::REGISTER_USER)]
    public static function register(RegisterUser $command): array
    {
        return [new UserWasRegistered($command->getUserId(), $command->getEmail(), $command->getHashedPassword())];
    }

    #[EventSourcingHandler]
    public function applyUserWasRegistered(UserWasRegistered $event): void
    {
        $this->id = $event->getUserId();
        $this->email = $event->getEmail();
        $this->hashedPassword = $event->getHashedPassword();
    }
}
