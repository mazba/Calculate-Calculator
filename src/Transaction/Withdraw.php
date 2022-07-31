<?php

namespace Mazba\Transaction;

use Mazba\Models\WithdrawModel;

class Withdraw
{

    /**
     * @var string|null
     */
    public $date;
    /**
     * @var integer|null
     */
    public $user_id;
    /**
     * @var string|null
     */
    public $user_type;
    /**
     * @var double|null
     */
    public $amount;
    /**
     * @var string|null
     */
    public $currency;

    /**
     * @param $date
     * @param $user_id
     * @param $user_type
     * @param $amount
     * @param $currency
     */
    public function __construct($date, $user_id, $user_type, $amount, $currency){
        $this->date = $date;
        $this->user_id = $user_id;
        $this->user_type = $user_type;
        $this->amount = $amount;
        $this->currency = $currency;
        return $this->makeWithdraw();
    }

    /**
     * Make new withdraw
     * @return Withdraw
     */
    private function makeWithdraw(){
        $withdrawModel = new WithdrawModel();
        $withdrawModel->addWithdrawByUser($this->date, $this->user_id, $this->amount, $this->currency);
        return $this;
    }
}