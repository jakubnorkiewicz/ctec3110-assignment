<?php

namespace App\Actions;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class LoginSubmitAction
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $email = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];
        $user = User::where('email', $email)
            ->first();

        if (isset($user) && password_verify($password, $user->password)) {
                $this->session->invalidate();
                $this->session->start();
                $this->session->set('user', $user);
        }

        return $response->withStatus(302)->withHeader('Location', '/p17215071');
    }
}
