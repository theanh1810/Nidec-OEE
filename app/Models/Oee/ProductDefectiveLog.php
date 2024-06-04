<?php

namespace App\Models\Oee;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ProductDefectiveLog extends Model
{
    // use HasFactory;
    const CREATED_AT = 'Time_Created';
	const UPDATED_AT = 'Time_Updated';

	protected $table = 'Product_Defective_Log';
	protected $primaryKey = 'ID';

	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}

	protected $fillable = [
	  	'Production_Log_ID',
	  	'Master_Shift_ID',
        'Master_Machine_ID',
        'Quantity',
	  	'Time_Created',
	  	'Time_Updated'
	];

    public function production_log()
    {
        return $this->hasOne('App\Models\Oee\ProductionLog', 'ID', 'Production_Log_ID')->whereIsdelete(0);
    }
}
