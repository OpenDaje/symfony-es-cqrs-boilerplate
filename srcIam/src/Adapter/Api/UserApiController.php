<?php declare(strict_types=1);

namespace IdentityAccess\Adapter\Api;

use Ecotone\Modelling\CommandBus;
use IdentityAccess\Application\Model\Identity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserApiController extends AbstractController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    #[Route("/users/register", methods: ["POST"])]
    public function register(Request $request): Response
    {
        $this->commandBus->sendWithRouting(User::REGISTER_USER, $request->request->all());

        return new RedirectResponse("/");
    }
}
