<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadesController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
         EspecialidadeRepository $repository,
         EspecialidadeFactory $especialidadeFactory,
         ExtratorDadosRequest $extrador
    ) {
        parent::__construct($repository, $entityManager, $especialidadeFactory, $extrador);
    }

     /**
     *
     * @param Especialidade $entidadeExistente
     * @param Especialidade  $entidadeEnviada
     * @return void
     */
    public function atualizarEntidadeExistente(int $id, $entidadeEnviada)
    {
        $entidadeExistente = $this->repository->find($id);
        if (is_null($entidadeExistente)) {
            throw new \InvalidArgumentException();
        }

        $entidadeExistente
            ->setDescricao($entidadeEnviada->getDescricao());
        
        return $entidadeExistente;
    }
}
