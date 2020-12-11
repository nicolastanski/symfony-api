<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;
use Exception;

class MedicoFactory implements EntidadeFactory
{
    private $especialidadeRepository;
    
    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function criarEntidade(string $json): Medico
    {
        $dadosEmJson = json_decode($json);

        $this->checkAllProperties($dadosEmJson);

        $especialidadeId = $dadosEmJson->especialidadeId;

        $especialidade = $this->especialidadeRepository->find($especialidadeId);

        $medico = new Medico();
        $medico
            ->setCrm($dadosEmJson->crm)
            ->setNome($dadosEmJson->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }

    private function checkAllProperties(object $dadosJson): void
    {
        if (!property_exists($dadosJson, 'nome')) {
            throw new EntityFactoryException('Médico precisa ter nome');
        }

        if (!property_exists($dadosJson, 'crm')) {
            throw new EntityFactoryException('Médico precisa de CRM');
        }

        if (!property_exists($dadosJson, 'especialidade')) {
            throw new EntityFactoryException('Médico precisa de especialidade');
        }
    }
}