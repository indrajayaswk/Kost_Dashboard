<?php

namespace App\Models;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class MidtransPayment extends Model
{
    // No database table used
    protected $table = null;

    // Validation rules for the payment data
    public static function validatePaymentData($data)
    {
        $validator = Validator::make($data, [
            'gross_amount' => 'required|numeric|min:1',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|string',
            'expiry_start_time' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }
}

