<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OlaMundoController
{
    /**
     * @Route("/ola")
     */
    public function olaMundoAction(Request $request): Response
    {
        $pathInfo = $request->getPathInfo();
        //$request->query->get('parametro');
        dd($request->query->all());
        return new JsonResponse(['mensagem' => 'Olá mundo']);
    }

}