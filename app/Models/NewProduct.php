<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewProduct extends Model
{
    use HasFactory;
	
    protected $table = "NewProduct";
    protected $primaryKey = "Prod_ProductID";
    const CREATED_AT = null;
    const UPDATED_AT = null;
	public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
    }


    public function ProductFamily()
    {
        return $this->belongsTo(ProductFamily::class, "prod_productfamilyid");
    }

}
