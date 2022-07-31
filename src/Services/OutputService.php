<?php

namespace Mazba\Services;

use Exception;
use Mazba\Currency\Currency;

class OutputService
{
    /**
     * Print formatted commission fees are rounded up to currency's decimal places.
     * For example, 0.023 EUR should be rounded up to 0.03 EUR
     *
     * @param $commission
     * @param string $currency
     * @throws Exception
     */
    public function printCommission($commission, string $currency = 'EUR') : void{
        if($currency != 'EUR')
            $commission = (new Currency())->convertTo($currency,$commission);
        // Japanese currency decimal skipping
        if($currency == 'JPY'){
            $pow = pow(10, abs(0));
            $formatted_commission = ceil($commission * $pow) / $pow;
            echo $formatted_commission."\n";
        }
        else{
            $pow = pow(10, abs(2));
            $formatted_commission = ceil($commission * $pow) / $pow;
            echo sprintf("%.2f", $formatted_commission)."\n";
        }
    }
}