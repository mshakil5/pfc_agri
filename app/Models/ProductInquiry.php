<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInquiry extends Model
{
    protected $fillable = ['product_id', 'name', 'email', 'phone', 'message'];
}
