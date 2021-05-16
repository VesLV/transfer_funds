<?php


namespace App\Http\Utils;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyConversion
{
    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $amount
     * @return float|int
     */
    public function conversion(string $currencyFrom,string $currencyTo, float $amount)
    {
        try {
            $response = Http::get(getenv('CURRENCY_RATES') . 'from=' . $currencyFrom . '&to=' . $currencyTo . '&amount=' . $amount)->json();
        } catch (\RuntimeException $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException('Fetch currency rates failed');
        }

        return $response['result'];
    }
}
