<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    private $host;

    private $accountCollection;

    private $account;

    private $transfers;

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
        $this->accountCollection = Account::factory(1)->make();
        $this->account = Account::factory(1)->create();
        $this->transfers = Transaction::factory(1)->create();


    }

    public function testCreateAccount(): void
    {
        $response = $this->call('POST', $this->host . '/account', [
            'client' => $this->accountCollection[0]->client_id,
            'currency' => $this->accountCollection[0]->currency,
            'balance' => (string) $this->accountCollection[0]->balance
        ]);
        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode(), 'Expected status code 201 received ' . $response->getStatusCode());
    }

    public function testGetAccount(): void
    {
        $response = $this->call('GET', $this->host . '/account/' . $this->account[0]->id);
        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode(), 'Expected status code 200, received: ' . $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $content, 'Response is missing id key');
        self::assertArrayHasKey('client', $content, 'Response is missing client key');
        self::assertArrayHasKey('currency', $content, 'Response is missing currency key');
        self::assertArrayHasKey('balance', $content, 'Response is missing balance key');
    }

    public function testGetAccountHistory(): void
    {
        $response = $this->call('GET', $this->host . '/account/' . $this->transfers[0]->sender . '/history');
        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode(), 'Expected status code 200, received: ' . $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $content, 'Response is missing id key');
        self::assertArrayHasKey('offset', $content, 'Response is missing offset key');
        self::assertArrayHasKey('limit', $content, 'Response is missing limit key');
        self::assertArrayHasKey('total', $content, 'Response is missing total key');
        self::assertArrayHasKey('history', $content, 'Response is missing history key');
    }

    public function testGetAccountHistoryWithLimitAndOffset(): void
    {
        $response = $this->call('GET', $this->host . '/account/' . $this->transfers[0]->sender . '/history?limit=100&offset=5');
        self::assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode(), 'Expected status code 200, received: ' . $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        self::assertEquals(100, $content['limit'], 'Incorrect limit');
        self::assertEquals(5, $content['offset'], 'Incorrect offset');
    }
}
