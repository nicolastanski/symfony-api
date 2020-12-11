<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
         EspecialidadeRepository $repository,
         EspecialidadeFactory $especialidadeFactory,
         ExtratorDadosRequest $extrador,
         CacheItemPoolInterface $cache,
         LoggerInterface $logger
    ) {
        parent::__construct(
            $repository,
            $entityManager,
            $especialidadeFactory,
            $extrador,
            $cache,
            $logger
        );
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
    
    public function cachePrefix(): string
    {
        return 'especialidade_';   
    }

    /**
     * @Route("/especialidades_html")
     *
     * @return void
     */
    public function especialidadesEmHtml()
    {
        $especialidades = $this->repository->findAll();

        return $this->render('especialidades.html.twig', [
            'especialidades' => $especialidades
        ]);
    }
}
