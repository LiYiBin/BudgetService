<?php

namespace App;

use DateTimeImmutable;

class BudgetService
{
    private IBudgetRepo $budgetRepo;

    public function __construct($budgetRepo)
    {
        $this->budgetRepo = $budgetRepo;
    }

    public function query($start, $end)
    {
        if ($end < $start) {
            return 0;
        }

        $allBudget = $this->budgetRepo->getAll();

        return collect($allBudget)->reduce(function ($totalAmount, $budget) use ($start, $end) {
            $budgetYearMonth = DateTimeImmutable::createFromFormat('Ym', $budget->yearMonth);

            $budgetFistDate = $budgetYearMonth->modify('first day of this month');
            $budgetLastDate = $budgetYearMonth->modify('last day of this month');

            $startDate = $budgetFistDate > $start ? $budgetFistDate : $start;
            $endDate = $budgetLastDate < $end ? $budgetLastDate : $end;

            $thisMonthDays = cal_days_in_month(CAL_GREGORIAN, $budgetYearMonth->format('m'), $budgetYearMonth->format('Y'));
            $totalDays = $endDate->format('d') - $startDate->format('d') + 1;

            return $totalAmount + round($budget->amount / $thisMonthDays * $totalDays, 2);
        }, 0.0);
    }
}
