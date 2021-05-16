<?php


namespace App\Http\Services;


use App\Http\Utils\ResponseBuilder;
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
     * @param array $data
     * @return mixed
     */
    public function createTransfer(array $data)
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
        $receiver->balance = $receiver->balance + $transaction->getAmount();

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
        $balance = $account->balance - $senderTransferAmount;
        if ($balance < 0) {
            return false;
        }
        return $balance;
    }

    /**
     * @param int $accountId
     * @param array $constrains
     * @return array
     */
    public function getTransferHistory(int $accountId, array $constrains): array
    {
        $transactions = $this->getTransfers($accountId);
        $transactionCount = $this->getTransfers($accountId)->count();

        if (!empty($constrains['offset'])) {
            $transactions->offset($constrains['offset']);
        }

        if (!empty($constrains['limit'])) {
            $transactions->limit($constrains['limit']);
        }

        return ResponseBuilder::transferHistoryResponse($transactions->get(), $transactionCount, $accountId, $constrains);
    }

    public function getTransfers($accountId)
    {
        return \App\Models\Transaction::where('sender', $accountId)->orWhere('receiver', $accountId)->orderByDesc('created_at');
    }
}
