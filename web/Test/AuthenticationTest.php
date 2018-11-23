<?php

use PHPUnit\Framework\TestCase;
use \GuzzleHttp\Client;
final class AuthenticationTest extends TestCase
{

    protected $client;

    protected function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost'
        ]);
    }

    public function testCanAuthWithCorrectUser()
    {
        $response = $this->client->post('/auth', [
            'form_params' => [
                'user' => 'hellofresh',
                'pass' => 'hellofresh'
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertEquals('OK', $data['msg']);

    }

    public function testCanAuthWithWrongUser()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost'
        ]);

        $response = $this->client->post('/auth', [
            'form_params' => [
                'user' => 'hellofresh10',
                'pass' => 'hellofresh'
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
        $this->assertEquals('Invalid credentials', trim($data['msg']));

    }

    public function testCanAuthWithWrongPass()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost'
        ]);

        $response = $this->client->post('/auth', [
            'form_params' => [
                'user' => 'hellofresh',
                'pass' => 'hellofresh10'
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
        $this->assertEquals('Invalid credentials', trim($data['msg']));

    }

}
