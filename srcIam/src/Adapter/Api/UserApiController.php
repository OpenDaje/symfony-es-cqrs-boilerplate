<?php declare(strict_types=1);

namespace IdentityAccess\Adapter\Api;

use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use IdentityAccess\Application\Model\Identity\ReadModel\UserList;
use IdentityAccess\Application\Model\Identity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserApiController extends AbstractController
{
    private CommandBus $commandBus;

    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    #[Route("/users/register", methods: ["POST"])]
    public function register(Request $request): Response
    {
        $this->commandBus->sendWithRouting(User::REGISTER_USER, $request->request->all());

        return new RedirectResponse("/");
    }

    #[Route("/users", methods: ["GET"])]
    public function userList(Request $request): Response
    {
        $users = $this->queryBus->sendWithRouting(UserList::GET_USER_LIST);

        return new JsonResponse($users);
    }
}
