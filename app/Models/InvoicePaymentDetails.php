<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePaymentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_invoice_id',
        'financial_year_id',
        'payment_id',
        'payment_date',
        'company_id',
        'received_amount',
        'total_amount',
        'flag',
    ];

    /**
     * Get the payment associated with the InvoicePaymentDetails
     * Note: Do not use this with('payment') with get().
     * @example $invoicePaymentDetail->payment
     *
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id')->where('payments.financial_year_id', $this->financial_year_id);
    }
}
