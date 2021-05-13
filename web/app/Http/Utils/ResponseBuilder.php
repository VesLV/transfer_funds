<?php


namespace App\Http\Utils;


use App\Models\Account;
use App\Models\Client;

class ResponseBuilder
{
    /**
     * @param Client $client
     * @return array
     */
    public static function clientResponse(Client $client): array
    {
        foreach ($client->accounts as $account) {
            $accounts[] = [
                'id' => $account->id,
                'currency' => $account->currency
            ];
        }

        return [
            'id' => $client->id,
            'info' => [
                'name' => $client->name,
                'surname' => $client->surname,
                'country' => $client->country
            ],
            'accounts' => $accounts
        ];
    }

    /**
     * @param Account $account
     * @return array
     */
    public static function accountResponse(Account $account): array
    {
        return [
            'id' => $account->id,
            'client' => $account->client_id,
            'currency' => $account->currency,
            'balance' => $account->balance
        ];
    }
}
