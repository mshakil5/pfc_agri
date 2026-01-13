<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'email',
        'phone',
        'address',
        'current_address',
        'previous_address',
        'bank_name',
        'account_number',
        'sort_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'reference_checked',
        'previous_landlord_reference',
        'personal_reference',
        'credit_score',
        'immigration_status',
        'right_to_rent_status',
        'right_to_rent_check_date',
        'status'
    ];

    protected $casts = [
        'right_to_rent_check_date' => 'date',
        'status' => 'boolean'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}