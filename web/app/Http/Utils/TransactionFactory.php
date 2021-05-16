<?php


namespace App\Http\Utils;

class TransactionFactory
{
    /**
     * @param array $data
     * @return Transaction
     */
    public static function create(array $data): Transaction
    {
        return (new Transaction())
            ->setReceiver($data['receiver'] ?? null)
            ->setSender($data['sender'] ?? null)
            ->setAmount($data['amount'] ?? null)
            ->setCurrency(strtoupper($data['currency']) ?? null);
    }
}
