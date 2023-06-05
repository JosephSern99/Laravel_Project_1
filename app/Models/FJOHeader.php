<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\DelScope;

class FJOHeader extends Model
{
    use HasFactory;

    protected $table = "ServiceOrder";
    protected $primaryKey = "svod_ServiceOrderID";
    const CREATED_AT = 'svod_CreatedDate';
    const UPDATED_AT = 'svod_UpdatedDate';
	protected $dates = [
        "svod_date",
		"svod_datefrom",
		"svod_dateto"
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DelScope("svod_Deleted"));

        static::creating(function($model)
        {
			if(auth()->check()){
				$user = auth()->user();
				$model->svod_CreatedBy = $user->getKey();
                $model->svod_UpdatedBy = $user->getKey();
			}
        });

        static::updating(function($model)
        {
            if(auth()->check()){
				$user = auth()->user();
                $model->svod_UpdatedBy = $user->getKey();
			}
        });
    }

    /*public function items()
    {
        return $this->hasMany(FJODetail::class, "svit_ServiceOrderItemId");
    }*/
	
	public function territory()
	{
		return $this->belongsTo(Territories::class, "svod_Secterr", "Terr_TerritoryID");
	}
	
	
	public function Person()
	{
		return $this->belongsTo(Person::class, "svod_PersonId");//3rd parameter is primary key
	}
	
	public function Company()
	{
		return $this->belongsTo(Company::class, "svod_CompanyId");//3rd parameter is primary key
	}
	
	public function Contact()
	{
		return $this->belongsTo(Contact::class, "svod_PersonId", "Pers_PersonId");//3rd parameter is primary key
	}
	
	public function Address()
	{
		return $this->belongsTo(Address::class, "svod_svcaddress");//3rd parameter is primary key
	}
	
	public function Asset()
	{
		return $this->belongsTo(Asset::class, "svod_assetid");//3rd parameter is primary key
	}
	
	
		

}
