<?php


namespace App\Http\Services;


use App\Http\Utils\TransactionFactory;
use App\Models\Account;
use App\Http\Utils\CurrencyConversion;
use App\Http\Utils\Transaction;

class TransferService
{

    /**
     * @var CurrencyConversion
     */
    private $currencyConversion;

    /**
     * TransferService constructor.
     * @param CurrencyConversion $currencyConversion
     */
    public function __construct(CurrencyConversion $currencyConversion)
    {
        $this->currencyConversion = $currencyConversion;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createTransfer($data)
    {
        $transaction = TransactionFactory::create($data);
        $sender = Account::find($transaction->getSender());
        $receiver = Account::find($transaction->getReceiver());

        if ($receiver->currency !== $transaction->getCurrency()) {
            throw new \RuntimeException('Incorrect currency');
        }

        if (!$balance = $this->checkSenderBalance($transaction, $sender)) {
            throw new \RuntimeException('Insufficient funds. Transfer Canceled');
        }

        $sender->balance = $balance;
        $receiver->balance = $receiver->balance + (float) $transaction->getAmount();

        $transaction = \App\Models\Transaction::create($transaction->jsonSerialize());
        $sender->save();
        $receiver->save();

        return $transaction;
    }

    /**
     * @param Transaction $transaction
     * @param Account $account
     * @return false|float|mixed
     */
    public function checkSenderBalance(Transaction $transaction, Account $account)
    {
        $senderTransferAmount = $transaction->getAmount();
        if ($account->currency !== $transaction->getCurrency()) {
            $senderTransferAmount = $this->currencyConversion->conversion($transaction->getCurrency(), $account->currency, $transaction->getAmount());
        }
        $balance = $account->balance - (float) $senderTransferAmount;
        if ($balance < 0) {
            return false;
        }
        return $balance;
    }
}
