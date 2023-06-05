<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\DelScope;

class Company extends Model
{
    use HasFactory;
	protected $table = "Company";
    protected $primaryKey = "Comp_CompanyId";
    const CREATED_AT = 'Comp_CreatedDate';
    const UPDATED_AT = 'Comp_UpdatedDate';
	protected $dates = [
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DelScope("Comp_Deleted"));

        static::creating(function($model)
        {
			if(auth()->check()){
				$user = auth()->user();
				$model->Comp_CreatedBy = $user->getKey();
                $model->Comp_UpdatedBy = $user->getKey();
			}
        });

        static::updating(function($model)
        {
            if(auth()->check()){
				$user = auth()->user();
                $model->Comp_UpdatedBy = $user->getKey();
			}
        });
    }
}
