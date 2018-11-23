<?php

use PHPUnit\Framework\TestCase;
use \GuzzleHttp\Client;
final class RateTest extends TestCase
{

    protected $client;
    protected $recipeValidId;

    protected function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost'
        ]);

        $this->recipeValidId = $this->getValidRecipe();
    }

    private function getValidRecipe() {
        $response = $this->client->get('/recipes/page/1/per/1');
        $data = json_decode($response->getBody(), true);
        $first = $data['data']['data'][0];
        return $first['id'];
    }

    public function testBringAllRates()
    {
        $response = $this->client->get('/rates');
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
    }

    public function testBringAllRatesWithPagination()
    {
        $response = $this->client->get('/rates/page/1/per/2');
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
    }

    public function testRatePaginationShouldBeOnlyOnePage()
    {
        $response = $this->client->get("/rates/page/1/per/100000");
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertEquals(1, $data['data']['pagination']['first']);
        $this->assertEquals(1, $data['data']['pagination']['last']);
    }

    public function testRatePaginationShouldBeEmpty()
    {
        $response = $this->client->get('/rates/page/1000/per/5');
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertEquals(array(), $data['data']['data']);
    }

    public function testRateRecipe()
    {
        $id = $this->recipeValidId;

        $response = $this->client->post("/rate/{$id}/3");
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
    }

    public function testRateRecipeNumberBiggerThanFive()
    {
        $id = $this->recipeValidId;
        $response = $this->client->post("/rate/{$id}/7");
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
    }

    public function testRateInvalidReciper()
    {
        $response = $this->client->post('/rate/19999999/3');
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
    }

}
