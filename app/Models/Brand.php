<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'brand_name',
        'brand_img',
        'meta_title',
        'meta_desc',
        'brand_slug',
        'status'
    ];
}
