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
        $amount = (double)$this->getParam('amount');

        // Validate tags here to prevent an error to be thrown
        if($from == null || $from == '') return;
        if($to == null || empty($to)) return;
        if($amount == null) return;

        return $this->parseLoop($this->api()->convert($amount, $from, $to));
    }
}
