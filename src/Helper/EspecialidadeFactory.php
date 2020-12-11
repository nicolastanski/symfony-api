<?php

namespace App\Helper;

use App\Entity\Especialidade;

class EspecialidadeFactory implements EntidadeFactory
{
    public function criarEntidade(string $json): Especialidade
    {
        $dadosJson = json_decode($json);
        if  (!property_exists($dadosJson, 'descricao')) {
            throw new EntityFactoryException('Especialidade precisa de descrição');
        }
        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosJson->descricao);

        return $especialidade;
    }
}