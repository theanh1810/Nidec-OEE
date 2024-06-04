<?php

namespace App\Models\Oee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class OeeDay extends Model
{
    // use HasFactory;
    const CREATED_AT = 'Time_Created';
	const UPDATED_AT = 'Time_Updated';
	
	protected $table = 'Oee_Day';
	protected $primaryKey = 'ID';

	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}

	protected $fillable = [
	  	'Master_Machine_ID',
	  	'Oee',
        'A',
        'P',
	  	'Q',
	  	'A_Loss',
	  	'P_Loss',
	  	'Q_Loss',
	  	'Time_Created',
	  	'Time_Updated'
	];

    public function master_machine() {
        return $this->hasOne('App\Models\MasterData\MasterMachine', 'ID', 'Master_Machine_ID');
    }
}
