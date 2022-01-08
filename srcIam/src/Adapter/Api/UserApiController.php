<?php declare(strict_types=1);

namespace IdentityAccess\Adapter\Api;

use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use IdentityAccess\Application\Model\Identity\Command\RegisterUser;
use IdentityAccess\Application\Model\Identity\ReadModel\UserList;
use IdentityAccess\Application\Model\Identity\User;
use IdentityAccess\Infrastructure\Authentication\SecurityUser;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserApiController extends AbstractController
{
    private CommandBus $commandBus;

    private QueryBus $queryBus;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus, UserPasswordHasherInterface $passwordHasher)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route("/users/register", methods: ["POST"])]
    public function register(Request $request): Response
    {
        $plaintextPassword = $request->request->get('hashedPassword');

        $command = new RegisterUser(
            $request->request->get('email'),
            SecurityUser::encryptPassword($plaintextPassword, $this->passwordHasher),
            Uuid::uuid4()->toString()
        );

        $this->commandBus->sendWithRouting(User::REGISTER_USER, $command);

        return new RedirectResponse("/");
    }

    #[Route("/users", methods: ["GET"])]
    public function userList(Request $request): Response
    {
        $users = $this->queryBus->sendWithRouting(UserList::GET_USER_LIST);

        return new JsonResponse($users);
    }
}
