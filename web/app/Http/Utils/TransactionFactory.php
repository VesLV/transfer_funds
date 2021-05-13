<?php


namespace App\Http\Utils;

class TransactionFactory
{
    /**
     * @param $data
     * @return Transaction
     */
    public static function create($data): Transaction
    {
        return (new Transaction())
            ->setReceiver($data['receiver'] ?? null)
            ->setSender($data['sender'] ?? null)
            ->setAmount($data['amount'] ?? null)
            ->setCurrency(strtoupper($data['currency']) ?? null);
    }
}
