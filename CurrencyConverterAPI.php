<?php

namespace Statamic\Addons\CurrencyConverter;

use Statamic\API\Cache;
use Statamic\API\Helper;
use Statamic\Extend\API;
use Log;

class CurrencyConverterAPI extends API
{
    /**
     * Gets the current conversion rate
     *
     * The rate is will be stored for a hour and therefore might be a bit outdated
     *
     * @param string $from currency
     * @param string $to currency
     * @return number
     */
    public function getConversionRate($from, $to)
    {
        $requestUrl = 'https://free.currencyconverterapi.com/api/v6/convert?apiKey=' . $this->getConfig('apikey') . '&q=';
        $key = $from . '_' . $to;

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        try {
            $rateObject = file_get_contents($requestUrl . $key);
        } catch (\Exception $e) {
            Log::error('Currency-Converter: Problem reaching API');
            return false;
        }

        if ($rateObject != false) {
            $rateObject = json_decode($rateObject);

            if ($rateObject == false || !isset($rateObject->results->{$key}) || $rateObject->query->count == 0) {
                Log::info('Currency-Converter: Could not gather the rate of the conversion ' . $key);
                return false;
            }

            $rate = $rateObject->results->{ $key }->val;

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
     * @return array
     */
    public function convert($amount, $from, $to)
    {
        return collect($to = Helper::ensureArray($to))->map(function ($code, $key) use ($amount, $from, $to) {
            return [
                'currency' => $to[$key],
                'converted_amount' => $this->getConversionRate($from, $to[$key]) * $amount,
            ];
        })->values()->all();
    }
}
