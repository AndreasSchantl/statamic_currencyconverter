<?php

namespace Statamic\Addons\CurrencyConverter;

use Statamic\Extend\Tags;

class CurrencyConverterTags extends Tags
{

    /**
     * The {{ currency_converter }} tag
     *
     * @return string|array
     */
    public function index()
    {
        $from = $this->getParam('from');
        $to = $this->getParam('to');
        $amount = $this->getParamInt('amount');

        if($from == null || $from == '') return;
        if($to == null || empty($to)) return;
        if($amount == null) return;

        $converted = $this->api()->convert($amount, $from, $to);

        return $converted;
    }
}
