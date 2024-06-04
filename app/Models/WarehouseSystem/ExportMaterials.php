<?php

namespace App\Models\WarehouseSystem;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class ExportMaterials extends Model
{
    const CREATED_AT = 'Time_Created';
    const UPDATED_AT = 'Time_Updated';

    protected $table = 'Export_Materials';
    protected $primarykey = 'ID';
    protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}
    protected $fillable = [
        'Plan_ID',
        'Name',
        'Materials_ID',
        'Quantity',
        'Type',
        'Status',
        'To',
        'Product_ID',
        'Quantity_Export',
        'Note',
        'User_Created',
        'Time_Created',
        'User_Updated',
        'Time_Updated',
        'IsDelete'
    ];
    public function user_created()
	{
	    return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
	}

	public function user_updated()
	{
	    return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
	}
    public function materials()
    {
      return $this->hasOne('App\Models\MasterData\MasterMaterials', 'ID', 'Materials_ID')->whereIsdelete(0);
    }
    public function machine()
	{
	    return $this->hasOne('App\Models\MasterData\MasterMachine', 'ID', 'To')->whereIsdelete(0);
	}
    public function product()
    {
      return $this->hasOne('App\Models\MasterData\MasterProduct', 'ID', 'Product_ID')->whereIsdelete(0);
    }
    public function plan()
    {
      return $this->hasOne('App\Models\ProductionPlan\CommandProductionDetail', 'ID', 'Plan_ID')->whereIsdelete(0);
    }
}
