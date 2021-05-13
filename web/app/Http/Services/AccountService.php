<?php


namespace App\Http\Services;


use App\Models\Account;
use App\Models\Client;

class AccountService
{
    /**
     * @param $data
     * @return bool
     */
    public function createAccount($data): bool
    {
        $client = Client::find($data['client']);
        if (!$client) {
            throw new \RuntimeException('Client not found');
        }

        $account = Account::firstOrNew(['client_id' => $data['client'], 'currency' => $data['currency']], $data);
        if ($account->exists) {
            throw new \RuntimeException('Account for client: ' . $data['client'] . ' with currency: ' . $data['currency'] . ' already exists!');
        }
        return $account->save();
    }
}
