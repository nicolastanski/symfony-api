<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Helper\ResponseFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    protected $repository;
    protected $entityManager;
    protected $factory;

    public function __construct(
        ObjectRepository $repository, 
        EntityManager $entityManager, 
        EntidadeFactory $factory,
        ExtratorDadosRequest $extrator
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extrator = $extrator;
    }

    public function buscarTodos(Request $request): Response
    {
        $ordenacao = $this->extrator->buscaDadosOrdenacao($request);
        $filtro = $this->extrator->buscaDadosFiltro($request);
        [$paginaAtual, $itensPorPagina] = $this->extrator->buscaDadosPaginacao($request);

        $entityList = $this->repository->findBy(
            $filtro, 
            $ordenacao, 
            $itensPorPagina, 
            ($paginaAtual - 1) * $itensPorPagina
        );
        $factoryResposta = new ResponseFactory(
            true,
         $entityList, 
         Response::HTTP_OK, 
            $paginaAtual,
            $itensPorPagina
        );

        return $factoryResposta->getResponse();
    }

    public function buscarUm(int $id): Response
    {
        $entidade = $this->repository->find($id);
        $statusResposta = is_null($entidade) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $fabricaResposta = new ResponseFactory(true, $entidade, $statusResposta);
        return $fabricaResposta->getResponse();
    }

    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entidadeEnviada = $this->factory->criarEntidade($corpoRequisicao);

        try {
            
            $entidadeExistente = $this->atualizarEntidadeExistente($id, $entidadeEnviada);
            $this->entityManager->flush();

            $fabrica = new ResponseFactory(
                true, 
                $entidadeExistente, 
                Response::HTTP_OK
            );
            
            return $fabrica->getResponse();
    
        } catch (\InvalidArgumentException $exception) {
            $fabrica = new ResponseFactory(false, 'Recurso não encontrado', Response::HTTP_NOT_FOUND);
            return $fabrica->getResponse();
        }
        return new JsonResponse($entidadeExistente);
    }

    public function remove(int $id): Response
    {
        $entidade = $this->repository->find($id);
        $this->entityManager->remove($entidade);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    public function novo(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $entidade = $this->factory->criarEntidade($dadosRequest);
    
        $this->entityManager->persist($entidade);
        $this->entityManager->flush();

        return new JsonResponse($entidade);
    }

    abstract public function atualizarEntidadeExistente(int $Id, $entidadeEnviada);
}