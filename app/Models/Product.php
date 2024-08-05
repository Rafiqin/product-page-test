<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug', 'price', 'active'];
    
    public function discount()
    {
        return $this->hasOne(ProductDiscount::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class);
    }

}
