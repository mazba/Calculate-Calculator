<?php

namespace Mazba\Currency;

use Exception;
use Mazba\Services\HttpService;

class Currency
{
    /**
     * @var array
     */
    private $currency;

    /**
     * Loads currency from API and converts
     */
    public function __construct(){
        $api_response = HttpService::get('https://developers.paysera.com/tasks/api/currency-exchange-rates');
        $this->currency = json_decode($api_response,true);
    }

    /**
     * @throws Exception
     */
    public function convertToEuro($currency, $amount) : float{
        try {
            if(isset($this->currency['rates'][$currency])) {
                return $amount/$this->currency['rates'][$currency];
            }
            else
                throw new Exception('Currency not found');
        }
        catch (Exception $e) {
            throw new Exception('Currency Error');
        }
    }
    /**
     * @throws Exception
     */
    public function convertTo($currency, $amount) : float{
        try {
            if(isset($this->currency['rates'][$currency])) {
                return $amount*$this->currency['rates'][$currency];
            }
            else
                throw new Exception('Currency not found');
        }
        catch (Exception $e) {
            throw new Exception('Currency Error');
        }
    }
}