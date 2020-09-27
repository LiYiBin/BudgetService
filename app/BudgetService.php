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
        if ($start > $end) {
            return 0;
        }

        $budgetList = $this->budgetRepo->getAll();

        $allAmount = 0;
        foreach ($budgetList as $yearMonth => $budget) {
            if ($start->format('Ym') == $end->format('Ym')) {
                $diffDays = $start->diff($end)->days + 1;
                $daysInMonth = $start->format('t');
                $dailyAmount = $budget / $daysInMonth;

                return $dailyAmount * $diffDays;
            }

            if ($yearMonth < $start->format('Ym') || $yearMonth > $end->format('Ym')) {
                continue;
            }

            $allAmount += $budget;
        }

        return $allAmount;
    }
}
