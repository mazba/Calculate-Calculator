<?php

namespace Mazba\Models;

use Exception;
use Mazba\Currency\Currency;

class WithdrawModel
{
    /**
     * @var string
     */
    protected $table = 'withdraws';

    /**
     *
     * Used session to skip the database
     * @param $user_id
     * @param $date
     * @return array|void
     * @throws Exception
     */
    public function getUserWeeklyWithdraw($user_id, $date) : array{
        // using poor loop due to array, should be use database query to get daterange data
        if(isset($_SESSION['withdraws'][$user_id])){
            $all_withdraws = $_SESSION['withdraws'][$user_id];
            $week_start_date = date('Ymd', strtotime("monday this week",strtotime($date)));
            $week_end_date = date('Ymd', strtotime("next sunday",strtotime($date)));
            $total_withdraw = 0;
            $weekly_number_of_withdraws = 0;
            foreach ($all_withdraws as $withdraw){
                if($withdraw['currency']!='EUR'){
                    $currency = new Currency();
                    $withdraw_in_euro = $currency->convertToEuro($withdraw['currency'], $withdraw['amount']);
                }
                else
                    $withdraw_in_euro = $withdraw['amount'];
                $withdraw_date = str_replace('-','',$withdraw['date']);
                if($withdraw_date >= $week_start_date && $withdraw_date <= $week_end_date) {
                    $total_withdraw += $withdraw_in_euro;
                    $weekly_number_of_withdraws++;
                }
            }
            return [
                'total_withdraw_in_euro' =>  $total_withdraw,
                'withdraw_count' =>  $weekly_number_of_withdraws,
            ];
        }
    }

    /**
     *
     * Used session to skip the database
     * @param $date
     * @param $user_id
     * @param $amount
     * @param $currency
     * @param $commission
     */
    public function addWithdrawByUser($date, $user_id, $amount, $currency) : void{
        $_SESSION['withdraws'][$user_id][] = [
            'date'=>$date,
            'amount'=>$amount,
            'currency'=>$currency,
        ];
    }

}