<?php


namespace App\Http\Utils;


class Transaction implements \JsonSerializable
{
    /**
     * @var integer
     */
    private $sender;

    /**
     * @var integer
     */
    private $receiver;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @return int
     */
    public function getSender(): int
    {
        return $this->sender;
    }

    /**
     * @param int $sender
     * @return Transaction
     */
    public function setSender(int $sender): Transaction
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return int
     */
    public function getReceiver(): int
    {
        return $this->receiver;
    }

    /**
     * @param int $receiver
     * @return Transaction
     */
    public function setReceiver(int $receiver): Transaction
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return Transaction
     */
    public function setAmount(string $amount): Transaction
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Transaction
     */
    public function setCurrency(string $currency): Transaction
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
