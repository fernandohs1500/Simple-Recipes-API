<?php

use PHPUnit\Framework\TestCase;
use \GuzzleHttp\Client;
final class RecipeTest extends TestCase
{

    protected $client;
    protected $token;
    protected $validRecipeId;

    protected function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost'
        ]);

        $this->token = $this->getAuthToken();
        $this->validRecipeId = $this->getValidRecipe();
    }

    private function getAuthToken()
    {
        $response = $this->client->post('/auth', [
            'form_params' => [
                'user' => 'hellofresh',
                'pass' => 'hellofresh'
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['access_token'];
    }

    private function getValidRecipe() {
        $response = $this->client->get('/recipes/page/1/per/1');
        $data = json_decode($response->getBody(), true);
        $first = $data['data']['data'][0];
        return $first['id'];
    }

    public function testBringAllRecipes()
    {
        $response = $this->client->get('/recipes');
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
    }

    public function testBringAllRecipesWithPagination()
    {
        $response = $this->client->get('/recipes/page/1/per/2');
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
    }

    public function testRecipesPaginationShouldBeEmpty()
    {
        $response = $this->client->get('/recipes/page/1000/per/5');
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertSame(array(), $data['data']['data']);
    }


    public function testCreateRecipeWhithoutToken()
    {
        $response = $this->client->post('/recipes',[
            'http_errors' => false,
            'form_params' => [
                    'name' => 'Herby Pan-Seared Chicken',
                    'prep_time' => 15,
                    'difficult' => 1,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(403, $response->getStatusCode());
        $this->expectOutputString(0, $data['success']);
    }

    public function testCreateRecipeWhitToken()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes',[
                'http_errors' => false,
                'headers' => $headers,
                'form_params' => [
                    'name' => "Herby Pan-Seared Chicken",
                    'prep_time' => 15,
                    'difficult' => 2,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
    }

    public function testCreateRecipeWithDifficultOutOfRange()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes',[
                'http_errors' => false,
                'headers' => $headers,
                'form_params' => [
                    'name' => "Herby Pan-Seared Chicken",
                    'prep_time' => 15,
                    'difficult' => -1,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
    }

    public function testCreateRecipeWithEmptyName()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes',[
                'http_errors' => false,
                'headers' => $headers,
                'form_params' => [
                    'name' => "",
                    'prep_time' => 15,
                    'difficult' => 1,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
    }

    public function testCreateRecipeWithVegetarianNotBoolean()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes',[
                'http_errors' => false,
                'headers' => $headers,
                'form_params' => [
                    'name' => "Herby Pan-Seared Chicken",
                    'prep_time' => 15,
                    'difficult' => 2,
                    'bol_vegetarian' => 1
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
    }

    public function testGetRecipt()
    {
        $id = $this->validRecipeId;
        $response = $this->client->get('/recipes/' . $id);
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertEquals($id, $data['data']['id']);
    }

    public function testGetInvalidRecipt()
    {
        $id = 200000;
        $response = $this->client->get('/recipes/' . $id);
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame(array(), $data['data']);
    }

    public function testUpdateRecipeWhitToken()
    {
        $id = $this->validRecipeId;
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->put('/recipes/' . $id, [
                'http_errors' => false,
                'headers' => $headers,
                'form_params' => [
                    'name' => "Herby Pan-Seared Chicken",
                    'prep_time' => 15,
                    'difficult' => 2,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertEquals($id, $data['data']);
    }


    public function testUpdateRecipeWhihInvalidToken()
    {
        $id = $this->validRecipeId;
        $headers = [
            'Authorization' => 'Bearer 999999999990000',
            'Accept'        => 'application/json',
        ];

        $response = $this->client->put('/recipes/' . $id, [
                'http_errors' => false,
                'headers' => $headers,
                'form_params' => [
                    'name' => "Herby Pan-Seared Chicken",
                    'prep_time' => 15,
                    'difficult' => 2,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
    }

    public function testDeleteRecipe()
    {

        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes',[
                'http_errors' => false,
                'headers' => $headers,
                'form_params' => [
                    'name' => "Herby Pan-Seared Chicken",
                    'prep_time' => 15,
                    'difficult' => 2,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);
        $id = $data['data'];

        $response = $this->client->delete('/recipes/' . $id, [
                'http_errors' => false,
                'headers' => $headers
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertEquals('DELETED!', trim(strtoupper($data['data'])));
    }

    public function testSearchRecipe()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes/search', [
                'http_errors' => false,
                'headers' => $headers,
                'json' => [
                    'name' => "Herby",
                    'prep_time' => 15,
                    'difficult' => 2,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
    }

    public function testSearchPrepTimeInvalid()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes/search', [
                'http_errors' => false,
                'headers' => $headers,
                'json' => [
                    'name' => "Herby",
                    'prep_time' => 15000,
                    'difficult' => 12,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertSame(array(), $data['data']);
    }

    public function testSearchEmptyBody()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes/search', [
                'http_errors' => false,
                'headers' => $headers,
                'json' => [
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(0, $data['success']);
    }

    public function testCreatRecipeAndFind()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ];

        $response = $this->client->post('/recipes',[
                'http_errors' => false,
                'headers' => $headers,
                'form_params' => [
                    'name' => "Herby Pan-Seared Chicken",
                    'prep_time' => 15,
                    'difficult' => 2,
                    'bol_vegetarian' => 'true'
                ]
            ]
        );
        $data = json_decode($response->getBody(), true);
        $id = $data['data'];
        $response = $this->client->get('/recipes/' . $id);
        $data = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $data['success']);
        $this->assertEquals($id, $data['data']['id']);
    }


}
