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
        $accounts = [];
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

    /**
     * @param $transactions
     * @param int $count
     * @param int $accountId
     * @param array $constraints
     * @return array
     */
    public static function transferHistoryResponse($transactions, int $count, int $accountId, array $constraints): array
    {
        $history = [];
        foreach ($transactions as $transaction) {
            $history[] = [
                'id' => $transaction->id,
                'datetime' => $transaction->created_at,
                'sender' => $transaction->sender,
                'receiver' => $transaction->receiver,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency
            ];
        }
        return [
            'id' => $accountId,
            'offset' => !empty($constraints['offset']) ? $constraints['offset'] : 0,
            'limit' => !empty($constraints['limit']) ?  $constraints['limit'] : 0,
            'total' => $count,
            'history' => $history
        ];
    }
}
