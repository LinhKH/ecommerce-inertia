<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected  $primaryKey = 'id';

    protected $fillable = [
        'category_name',
        'category_icon',
        'parent_category',
        'meta_title',
        'meta_desc',
        'category_slug',
        'status'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_category');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_category')->with('categories');
    }
}
