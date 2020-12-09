<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    private $sucesso;
    private $conteudoResposta;
    private $statusResposta;
    private $paginaAtual;
    private $itensPorPagina;

    public function __construct(
        bool $sucesso, 
        $conteudoResposta, 
        int $statusResposta = Response::HTTP_OK, 
        int $paginaAtual = null, 
        int $itensPorPagina = null
    ) {
        $this->sucesso = $sucesso;
        $this->conteudoResposta = $conteudoResposta;
        $this->statusResposta = $statusResposta;
        $this->paginaAtual = $paginaAtual;
        $this->itensPorPagina = $itensPorPagina;
        
    }

    public function getResponse(): JsonResponse
    {
        $conteudo = [
            'sucesso' => $this->sucesso,
            'pagina_atual' => $this->paginaAtual,
            'itens_por_pagina' => $this->itensPorPagina,
            'conteudo_resposta' => $this->conteudoResposta
        ];

        if (is_null($this->paginaAtual)) {
            unset($conteudo['pagina_atual']);
            unset($conteudo['itens_por_pagina']);
        }

        return new JsonResponse($conteudo, $this->statusResposta);
    }
}