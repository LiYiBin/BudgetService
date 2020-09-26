<?php

namespace App;

class BudgetService
{
    private $budgetRepo;

    public function __construct($budgetRepo)
    {
        $this->budgetRepo = $budgetRepo;
    }

    public function query($start, $end)
    {
        if ($end < $start) {
            return 0;
        }

        $allAmount=$this->budgetRepo->getAll();



        if ($start->format('c') === $end->format('c')) {
            $amount = $allAmount[$start->format('Ym')];

            $thisMonthDays = cal_days_in_month(CAL_GREGORIAN, $start->format('m'), $start->format('Y'));

            return round($amount / $thisMonthDays, 2);
        }

        if ($start->format('d') == $start->modify('first day of this month')->format('d')  &&
            $end->format('d') == $end->modify('last day of this month')->format('d')) {

            $totalMonths = $end->format('m') - $start->format('m') + 1;

            $totalAmount = 0;
            for ($i = 0; $i < $totalMonths ; $i++) {
                $next = $start->modify("+$i month");

                $totalAmount += $allAmount[$next->format('Ym')] ?? 0;
            }

            return round($totalAmount, 2);
        }

        if ($start->format('Ym')===$end->format('Ym')) {
            $amount = $allAmount[$start->format('Ym')];

            $thisMonthDays = cal_days_in_month(CAL_GREGORIAN, $start->format('m'), $start->format('Y'));
            $total_day = $end->format('d') - $start->format('d') + 1;

            return round($amount / $thisMonthDays * $total_day, 2);
        }

        return 0;
    }
}
