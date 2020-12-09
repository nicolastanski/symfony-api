<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    private $repository;
    private $encoder;

    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request): Response
    {
        $dadosJson = json_decode($request->getContent());
        if (is_null($dadosJson->usuario || is_null($dadosJson->senha))) {
            return new JsonResponse([
                'erro' => 'Favor enviar usuário e senha'
            ], Response::HTTP_BAD_REQUEST);
        }
        $user = $this->repository->findOneBy(['username' => $dadosJson->usuario]);
        
        if (!$this->encoder->isPasswordValid($user, $dadosJson->senha)) {
            return new JsonResponse([
                'erro' => 'Usuário ou senha inválidos'
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $token = JWT::encode(['username' => $user->getUsername()], 'chave');

        return new JsonResponse(['access_token' => $token]);
    }
}
