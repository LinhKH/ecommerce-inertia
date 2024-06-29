<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute_value extends Model
{
    use HasFactory;

    protected $table = 'attributes_values';

    protected $fillable = [
        // 'attribute_name',
        'attribute_id',
        // 'attribute_cat',
        'attrvalues',
        'product_id'
    ];
}
