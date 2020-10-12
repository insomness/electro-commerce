<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{

    use SoftDeletes;


    protected $fillable = [
        'order_id',
        'number',
        'transaction_id',
        'amount',
        'method',
        'status',
        'token',
        'payloads',
        'payment_type',
        'va_number',
        'vendor_name',
        'biller_code',
        'bill_key',
    ];

    public const PAYMENT_CHANNELS = [
        'credit_card', 'mandiri_clickpay', 'cimb_clicks',
        'bca_klikbca', 'bca_klikpay', 'bri_epay', 'echannel', 'permata_va',
        'bca_va', 'bni_va', 'other_va', 'gopay', 'indomaret',
        'danamon_online', 'akulaku'
    ];

    public const EXPIRY_DURATION = 7;
    public const EXPIRY_UNIT = 'days';


    public const CHALLENGE = 'challenge';
    public const SUCCESS = 'success';
    public const SETTLEMENT = 'settlement';
    public const PENDING = 'pending';
    public const DENY = 'deny';
    public const EXPIRE = 'expire';
    public const CANCEL = 'cancel';


    public const PAYMENTCODE = 'PAY';

    /**
     * Generate order code
     *
     * @return string
     */
    public static function generateCode()
    {
        $dateCode = self::PAYMENTCODE . '/' . date('Ymd') . '/' . numberToRomanRepresentation((date('m'))) . '/' . numberToRomanRepresentation(date('d')) . '/';

        $lastOrder = self::select([\DB::raw('MAX(payments.number) AS last_code')])
            ->where('number', 'like', $dateCode . '%')
            ->first();

        $lastOrderCode = !empty($lastOrder) ? $lastOrder['last_code'] : null;

        $orderCode = $dateCode . '00001';
        if ($lastOrderCode) {
            $lastOrderNumber = str_replace($dateCode, '', $lastOrderCode);
            $nextOrderNumber = sprintf('%05d', (int)$lastOrderNumber + 1);

            $orderCode = $dateCode . $nextOrderNumber;
        }

        if (self::_isOrderCodeExists($orderCode)) {
            return generateCode();
        }

        return $orderCode;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if the generated order code is exists
     *
     * @param string $orderCode order code
     *
     * @return void
     */
    private static function _isOrderCodeExists($orderCode)
    {
        return self::where('number', '=', $orderCode)->exists();
    }
}
