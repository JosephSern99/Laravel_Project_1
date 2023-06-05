<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFamily extends Model
{
    use HasFactory;

    protected $table = "ProductFamily";
    protected $primaryKey = "";
    const CREATED_AT = null;
    const UPDATED_AT = null;
	public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
    }
}
