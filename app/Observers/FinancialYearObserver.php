<?php

namespace App\Observers;

use App\Models\FinancialYear;
use App\Models\IncrementId;

class FinancialYearObserver
{
    /**
     * Handle the FinancialYear "created" event.
     * Auto-create increment_ids entry when a new financial year is created.
     *
     * @param  \App\Models\FinancialYear  $financialYear
     * @return void
     */
    public function created(FinancialYear $financialYear)
    {
        // Get the last ID from increment_ids table
        $lastIncrementId = IncrementId::orderBy('id', 'DESC')->first();
        $nextId = !empty($lastIncrementId) ? $lastIncrementId->id + 1 : 1;

        // Create a new increment_ids entry with all counters initialized to 0
        IncrementId::create([
            'id' => $nextId,
            'financial_year_id' => $financialYear->id,
            'iuid' => 0,
            'ouid' => 0,
            'reference_id' => 0,
            'sale_bill_id' => 0,
            'payment_id' => 0,
            'commission_id' => 0,
            'goods_return_id' => 0,
        ]);
    }

    /**
     * Handle the FinancialYear "updated" event.
     *
     * @param  \App\Models\FinancialYear  $financialYear
     * @return void
     */
    public function updated(FinancialYear $financialYear)
    {
        //
    }

    /**
     * Handle the FinancialYear "deleted" event.
     *
     * @param  \App\Models\FinancialYear  $financialYear
     * @return void
     */
    public function deleted(FinancialYear $financialYear)
    {
        //
    }

    /**
     * Handle the FinancialYear "restored" event.
     *
     * @param  \App\Models\FinancialYear  $financialYear
     * @return void
     */
    public function restored(FinancialYear $financialYear)
    {
        //
    }

    /**
     * Handle the FinancialYear "force deleted" event.
     *
     * @param  \App\Models\FinancialYear  $financialYear
     * @return void
     */
    public function forceDeleted(FinancialYear $financialYear)
    {
        //
    }
}
