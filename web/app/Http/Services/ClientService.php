<?php

namespace App\Http\Services;

use App\Models\Client;

class ClientService
{
    /**
     * @param $data
     * @return bool
     */
    public function createClient($data): bool
    {
        $client = Client::firstOrNew(['name' => $data['name'], 'surname' => $data['surname']], $data);
        if ($client->exists) {
            throw new \RuntimeException('Client: ' . $data['name'] . ' ' . $data['surname'] . ' already exists!');
        }
        return $client->save();
    }
}
