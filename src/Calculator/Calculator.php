<?php
namespace Mazba\Calculator;

use Exception;
use Mazba\Currency\Currency;
use Mazba\Models\ConfigModel;
use Mazba\Models\WithdrawModel;
use Mazba\Services\InputService;
use Mazba\Transaction\Deposit;
use Mazba\Transaction\Withdraw;

class Calculator
{
    /**
     * @var
     */
    private $transaction_type;
    /**
     * @var
     */
    private $date;
    /**
     * @var
     */
    private $user_id;
    /**
     * @var
     */
    private $user_type;
    /**
     * @var
     */
    private $amount;
    /**
     * @var
     */
    private $currency;


    /**
     * Initiate the calculation
     * @param $transaction_type
     * @param $date
     * @param $user_id
     * @param $user_type
     * @param $amount
     * @param $currency
     * @throws Exception
     */
    public function __construct($date,$user_id,$user_type,$transaction_type,$amount,$currency){
        $this->date = $date;
        $this->user_id = $user_id;
        $this->user_type = $user_type;
        $this->transaction_type = $transaction_type;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Calculate the commission for each row
     *
     * @throws Exception
     */
    public function calculate()
    {
        switch ($this->transaction_type){
            case 'withdraw':
                // Creating a withdrawal log
                $withdraw = new Withdraw($this->date,$this->user_id,$this->user_type,$this->amount,$this->currency);
                // Initiate the calculation for withdraw
                return $this->calculateWithdrawCommission($this->date,$this->user_id,$this->user_type,$this->amount,$this->currency);
                break;
            case 'deposit':
                // Creating a deposit log
                $deposit = new Deposit($this->date,$this->user_id,$this->user_type,$this->amount,$this->currency);
                // Initiate the calculation for withdraw
                return $this->calculateDepositCommission($this->user_id, $this->user_type, $this->amount, $this->currency);
                break;
            default:
                break;
        }
    }

    /**
     * Calculate and print with commission
     * @param $date
     * @param $user_id
     * @param $user_type
     * @param $amount
     * @param $currency
     * @throws Exception
     */
    private function calculateWithdrawCommission($date, $user_id, $user_type, $amount, $currency) {
        if($currency!='EUR') {
            $amount_in_euro = (new Currency())->convertToEuro($currency, $amount);
        }
        else
            $amount_in_euro = $amount;
        $config = ConfigModel::getConfig()['withdraw'];
        $free_upto = $config['free_upto'];
        $free_upto_amount = $config['free_upto_amount'];
        $commission_rate = $config[$user_type];
        $withdraw_history = (new WithdrawModel())->getUserWeeklyWithdraw($user_id, $date);
        $prev_withdraw_amount = $withdraw_history['total_withdraw_in_euro'] - $amount_in_euro;
        switch ($user_type){
            case 'private':
                if($withdraw_history['withdraw_count'] <= $free_upto
                    && $withdraw_history['total_withdraw_in_euro'] <= $free_upto_amount)
                        $commission= 0;
                elseif($withdraw_history['withdraw_count'] <= $free_upto
                    &&  $withdraw_history['total_withdraw_in_euro'] > $free_upto_amount
                    &&  $prev_withdraw_amount < $free_upto_amount){
                    $considerable_amount = $withdraw_history['total_withdraw_in_euro'] - $free_upto_amount;
                    $commission = ($commission_rate/100)*$considerable_amount;
                }
                else
                    $commission = ($commission_rate/100)*$amount_in_euro;
                break;
            case 'business':
                $commission = ($commission_rate/100)*$amount_in_euro;
                break;
            default:
                $commission = 0;
                break;
        }
        return $commission;
    }

    /**
     * Calculate and print the deposit commission
     *
     * @param $user_id
     * @param $user_type
     * @param $amount
     * @param $currency
     * @throws Exception
     */
    private function calculateDepositCommission($user_id, $user_type, $amount, $currency){
        $commission_rate = ConfigModel::getConfig()['deposit'][$user_type];
        $commission = ($commission_rate/100)*$amount;
        return $commission;
    }


}