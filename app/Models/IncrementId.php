<?php

namespace App\Models;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class IncrementId extends Model
{
    use HasFactory, GeneratesAutoId;

    protected $fillable = [
        'id',
        'financial_year_id',
        'iuid',
        'ouid',
        'reference_id',
        'sale_bill_id',
        'payment_id',
        'commission_id',
        'goods_return_id',
    ];

    /**
     * Atomically increment the iuid counter for a financial year
     * Uses DB::raw() to prevent race conditions in concurrent requests
     *
     * @param int $financialYearId
     * @return int The new iuid value
     */
    public static function increaseIuid($financialYearId)
    {
        return self::incrementField($financialYearId, 'iuid');
    }

    /**
     * Atomically increment the ouid counter for a financial year
     *
     * @param int $financialYearId
     * @return int The new ouid value
     */
    public static function increaseOuid($financialYearId)
    {
        return self::incrementField($financialYearId, 'ouid');
    }

    /**
     * Atomically increment the reference_id counter for a financial year
     *
     * @param int $financialYearId
     * @return int The new reference_id value
     */
    public static function increaseReferenceId($financialYearId)
    {
        return self::incrementField($financialYearId, 'reference_id');
    }

    /**
     * Atomically increment the sale_bill_id counter for a financial year
     *
     * @param int $financialYearId
     * @return int The new sale_bill_id value
     */
    public static function increaseSaleBillId($financialYearId)
    {
        return self::incrementField($financialYearId, 'sale_bill_id');
    }

    /**
     * Atomically increment the payment_id counter for a financial year
     *
     * @param int $financialYearId
     * @return int The new payment_id value
     */
    public static function increasePaymentId($financialYearId)
    {
        return self::incrementField($financialYearId, 'payment_id');
    }

    /**
     * Atomically increment the commission_id counter for a financial year
     *
     * @param int $financialYearId
     * @return int The new commission_id value
     */
    public static function increaseCommissionId($financialYearId)
    {
        return self::incrementField($financialYearId, 'commission_id');
    }

    /**
     * Atomically increment the goods_return_id counter for a financial year
     *
     * @param int $financialYearId
     * @return int The new goods_return_id value
     */
    public static function increaseGoodsReturnId($financialYearId)
    {
        return self::incrementField($financialYearId, 'goods_return_id');
    }

    /**
     * Private method to atomically increment any field using DB::raw()
     * Ensures thread-safe increments without race conditions
     *
     * @param int $financialYearId
     * @param string $fieldName The counter field to increment (iuid, ouid, etc)
     * @return int The new incremented value
     */
    private static function incrementField($financialYearId, $fieldName)
    {
        // Use DB::raw() to increment atomically at database level
        DB::table('increment_ids')
            ->where('financial_year_id', $financialYearId)
            ->update([
                $fieldName => DB::raw($fieldName . ' + 1'),
                'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
            ]);

        // Fetch and return the new value
        $record = DB::table('increment_ids')
            ->where('financial_year_id', $financialYearId)
            ->first();

        return $record ? $record->$fieldName : 0;
    }
}
