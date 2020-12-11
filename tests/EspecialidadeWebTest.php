<?php

namespace App\Testes;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EspecilidadeWebTest extends WebTestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    public function testGaranteQueRequisicaoFalhaSemAutenticacao()
    {
        $this->client->request('GET', '/especialidades');

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testGaranteQueEspecialidaedsSaoListadas()
    {
        $token = $this->login($this->client);

        $this->client->request('GET', '/especialidades', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token"
        ]);

        $resposta = json_decode($this->client->getResponse()->getContent());

        self::assertTrue($resposta->sucesso);
    }

    public function testInsereEspecialidade()
    { 
        $token = $this->login($this->client);

        $resposta = $this->client->request('POST', '/especialidades', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer $token"
        ], json_encode([
            'descricao' => 'descricao'
        ]));
        
        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    private function login()
    {
        $this->client->request('POST', '/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'usuario' => 'user',
            'senha' => '123456'
        ]));

        return json_decode($this->client->getResponse()->getContent())->access_token;        
    }

    public function testHtmlEspecialidade()
    {
        $this->client->request('GET', '/especialidades_html');

        $this->assertSelectorTextContains('h1', 'Especialidades');
        $this->assertSelectorExists('.especialidade');
    }
}