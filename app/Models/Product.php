<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = [];

    public function tags()
    {
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }

    public function suppliers()
    {
        return $this->hasMany(ProductSupplier::class, 'product_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'product_category_relations',
            'product_id',
            'category_id'
        );
    }
}
