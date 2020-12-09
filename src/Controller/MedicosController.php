<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\ExtratorDadosRequest;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory,
        MedicoRepository $medicoRepository,
        ExtratorDadosRequest $extrador
    ) {
        parent::__construct($medicoRepository, $entityManager, $medicoFactory, $extrador);
    }

    public function buscaMedico(int $id)
    {
        return $this->repository->find($id);
    }

     /**
     * @Route("/especialidades/{especialidadeId}/medicos", methods={"PUT"})
     *
     * @return Response
     */
    public function buscaPorEspecialidade(int $especialidadeId): Response
    {
        $repositorioMedicos = $this->getDoctrine()
            ->getRepository(Medico::class);
        $medicos = $repositorioMedicos->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos);
    }

    /**
     *
     * @param Medico $entidadeExistente
     * @param Medico  $entidadeEnviada
     * @return void
     */
    public function atualizarEntidadeExistente(int $id, $entidadeEnviada)
    {
        $entidadeExistente = $this->repository->find($id);
        if (is_null($entidadeExistente)) {
            throw new \InvalidArgumentException();
        }

        $entidadeExistente
            ->setCrm($entidadeEnviada->getCrm())
            ->setNome($entidadeEnviada->getNome())
            ->setEspecialidade($entidadeEnviada->getEspecialidade());

        return $entidadeExistente;
    }

}