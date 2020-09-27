<?php


namespace App;


class Budget
{
    public string $yearMonth;
    public int $amount;

    public function __construct(string $yearMonth, int $amount)
    {
        $this->yearMonth = $yearMonth;
        $this->amount = $amount;
    }
}
