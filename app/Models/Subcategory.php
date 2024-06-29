<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $table = 'subcategories';

    protected $fillable = [
        'subcat_name',
        'parent_category',
        'meta_title',
        'meta_desc',
        'subcat_slug',
        'status'
    ];
}
