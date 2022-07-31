<?php

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * Basic EUR withdraw test
     *
     * @return void
     */
    public function testEurWithdrawCommission()
    {
        $date = date('Y-m-d');
        $user_id = rand(1,9999);
        $user_type = 'private';
        $amount = 1200.00;
        $currency = 'EUR';
        $transaction_type = 'withdraw';
        $calculator = new \Mazba\Calculator\Calculator($date, $user_id, $user_type, $transaction_type, $amount, $currency);
        $commission = $calculator->calculate();
        $this->assertEquals(0.60,$commission);
    }
    /**
     * Basic deposit test
     *
     * @return void
     */
    public function testDepositCommission()
    {
        $date = date('Y-m-d');
        $user_id = rand(1,9999);
        $user_type = 'private';
        $amount = 1000.00;
        $currency = 'USD';
        $transaction_type = 'deposit';
        $calculator = new \Mazba\Calculator\Calculator($date, $user_id, $user_type, $transaction_type, $amount, $currency);
        $commission = $calculator->calculate();
        $this->assertEquals(0.3,$commission);
    }
}
