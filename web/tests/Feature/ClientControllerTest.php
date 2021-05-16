<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    private $host;

    private $clientCollection;

    private $client;

    private $accounts;


    /**
     * ClientControllerTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->host = "http://app/api";
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->clientCollection = Client::factory(1)->make();
        $this->client = Client::factory(1)->create();
        $this->accounts = Account::factory(2)->create();
    }

    public function testCreateClient(): void
    {
        $response = $this->call('POST', $this->host . '/client', [
            'name' => $this->clientCollection[0]->name,
            'surname' => $this->clientCollection[0]->surname,
            'country' => $this->clientCollection[0]->country
        ]);
        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode(), 'Expected status code 201 received ' . $response->getStatusCode());
    }

    public function testGetClient(): void
    {
        $response = $this->call('GET', $this->host . '/client/' . $this->client[0]->id);
        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode(), 'Expected status code 200, received: ' . $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('info', $content, 'Response is missing info key');
        self::assertArrayHasKey('accounts', $content, 'Response is missing accounts key');
        self::assertArrayHasKey('id', $content, 'Response is missing id key');
    }
}
