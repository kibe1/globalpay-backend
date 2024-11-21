<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_mode',
        'customer_payment_mode',
        'amount',
        'time_payment_made',
        'time_payment_processed',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Generate 6-digit numeric ID
            $transaction->id = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

            // Generate a random 5-character alphanumeric transaction code
            $transaction->transaction_code = strtoupper(Str::random(5));
        });
    }
}
