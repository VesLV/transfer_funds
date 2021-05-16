<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class TransferControllerTest extends TestCase
{
    use RefreshDatabase;

    private $host;

    private $transfer;

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
        $this->transfer = Client::factory(1)->make();
    }

    public function testCreateTransfer(): void
    {
        $response = $this->call('POST', $this->host . '/transfer', [
            'sender' => $this->transfer[0]->sender,
            'receiver' => $this->transfer[0]->receiver,
            'amount' => $this->transfer[0]->amount,
            'currency' => $this->transfer[0]->currency
        ]);
        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode(), 'Expected status code 201 received ' . $response->getStatusCode());
    }
}
