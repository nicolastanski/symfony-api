<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }
    /**
     * @Route("/especialidades", methods="POST")
     */
    public function nova(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $dadosJson = json_decode(($dadosRequest));
        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosJson->descricao);
        
        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades", methods={"GET"})
     *
     * @return Response
     */
    public function buscarTodas(): Response
    {
        $especialidades = $this->repository->findAll();

        return new JsonResponse($especialidades);
    }

    /**
     * @Route("/especialidades/{id}", methods={"GET"})
     *
     * @return Response
     */
    public function buscarUma(int $id): Response
    {
        return new JsonResponse($this->repository->find($id));
    }

    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     *
     * @return Response
     */
    public function atualiza(int $id, Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $dadosJson = json_decode($dadosRequest);

        $especialidade = $this->repository->find($id);
        $especialidade->setDescricao($dadosJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"DELETE"})
     *
     * @return Response
     */
    public function remove(int $id): Response
    {
        $especialidade = $this->repository->find($id)
        ;
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
