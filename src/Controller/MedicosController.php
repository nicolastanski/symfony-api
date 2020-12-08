<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{
    private $entityManager;
    private $medicoFactory;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $medicoFactory)
    {
        $this->entityManager = $entityManager;    
        $this->medicoFactory = $medicoFactory;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

        $this->entityManager->persist($medico);
        $this->entityManager->flush();
        
        return new JsonResponse($medico);
    }

     /**
     * @Route("/medicos", methods={"GET"})
     *
     * @return Response
     */
    public function buscarTodos(): Response
    {
        $repositorioMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medicos = $repositorioMedicos->findAll();

        return new JsonResponse($medicos);
    }

     /**
     * @Route("/medicos/{id}", methods={"GET"})
     *
     * @return Response
     */
    public function buscarUm($id): Response
    {
        $medico = $this->buscaMedico($id);

        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoRetorno);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     *
     * @return Response
     */
    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

        $medicoExistente = $this->buscaMedico($id);
        if (is_null($medicoExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medicoExistente
            ->setCrm($medico->getCrm())
            ->setNome($medico->getNome());

        $this->entityManager->flush();

        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoRetorno);
    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     *
     * @return Response
     */
    public function remove(int $id): Response
    {
        $medicoExistente = $this->buscaMedico($id);
        if (is_null($medicoExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($medicoExistente);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function buscaMedico(int $id)
    {
        $repositorioMedicos = $this->getDoctrine()->getRepository(Medico::class);
        return $repositorioMedicos->find($id);
    }
}