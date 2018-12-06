<?php

namespace Statamic\Addons\CurrencyConverter;

use Statamic\API\Cache;
use Statamic\Extend\API;

class CurrencyConverterAPI extends API
{
    /**
     * Gets the current conversion rate
     * 
     * The rate is will be stored for an hour and therefore might be a bit outdated
     *
     * @param string $from currency
     * @param string $to currency
     * @return array
     */
    public function getConversionRate($from, $to)
    {
        $requestUrl = 'https://free.currencyconverterapi.com/api/v6/convert?q=';
        $key = $from . '_' . $to;

        $rate = Cache::get($key);
        if($rate === null) 
            $rate = json_decode(file_get_contents($requestUrl . $key));
        else 
            return $rate;

        if($rate != false) {
            Cache::put($key, $rate, 60);
            return $rate;
        } else {
            return false;
        }
    }

    /**
     * Converts an amount to the desired currency or currencies
     *
     * @param number $amount
     * @param string $from string of the currency the amount is given in
     * @param string|array $to string of one currency or array of currencies
     * @return void
     */
    public function convert($amount, $from, $to)
    {
        $converted = array();

        for ($i = 0; $i < count($to); $i++) {
            $conversionRate = $this->getConversionRate($from, $to[$i]);
            $key = strtoupper($from . '_' . $to[$i]); // API uppercases them as well. This prevents an error to be thrown

            $a = round($amount * $conversionRate->results->{ $key }->val, 2);
            array_push($converted, array('currency' => $to[$i], 'converted_amount' => $a));
        }

        return array('converted' => $converted);
    }
}
