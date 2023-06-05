<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\DelScope;

class InventoryAdjustment extends Model
{
    use HasFactory;
	
	protected $table = "InventoryAdjustment";
    protected $primaryKey = "inva_invadjustmentid";
    const CREATED_AT = 'inva_CreatedDate';
    const UPDATED_AT = 'inva_UpdatedDate';
	protected $dates = [
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DelScope("inva_Deleted"));

        static::creating(function($model)
        {
			if(auth()->check()){
				$user = auth()->user();
				$model->inva_CreatedBy = $user->getKey();
                $model->inva_UpdatedBy = $user->getKey();
			}
        });

        static::updating(function($model)
        {
            if(auth()->check()){
				$user = auth()->user();
                $model->inva_UpdatedBy = $user->getKey();
			}
        });
    }
	

}
