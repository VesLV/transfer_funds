<?php


namespace App\Http\Services;


use App\Models\Account;
use App\Models\Client;

class AccountService
{
    /**
     * @param array $data
     * @return bool
     */
    public function createAccount(array $data): bool
    {
        $client = Client::find($data['client']);
        if (!$client) {
            throw new \RuntimeException('Client not found');
        }
        $data['currency'] = strtoupper($data['currency']);
        $account = Account::firstOrNew(['client_id' => $data['client'], 'currency' => $data['currency']], $data);
        if ($account->exists) {
            throw new \RuntimeException('Account for client: ' . $account->client->name . ' ' . $account->client->surname . ' with currency: ' . $data['currency'] . ' already exists!');
        }
        return $account->save();
    }
}
