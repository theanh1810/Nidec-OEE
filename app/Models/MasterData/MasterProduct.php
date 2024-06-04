<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterProduct extends Model
{
  const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';

	protected $table      = 'Master_Product';
	protected $primaryKey = 'ID';

  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Name'
  	,'Symbols'
  	,'Unit_ID'
    ,'Materials_ID'
    ,'Quantity'
    ,'Cycle_Time'
  	,'Note'
  	,'Time_Created'
  	,'User_Created'
  	,'Time_Updated'
  	,'User_Updated'
  	,'IsDelete'
  ];

  public function user_created()
  {
    return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
  }

  public function user_updated()
  {
    return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
  }

  public function unit()
  {
    return $this->hasOne('App\Models\MasterData\MasterUnit', 'ID', 'Unit_ID')->whereIsdelete(0);
  }

   public function materials()
  {
    return $this->hasOne('App\Models\MasterData\MasterMaterials', 'ID', 'Materials_ID')->whereIsdelete(0);
  }

  public function bom()
  {
    return $this->hasMany('App\Models\MasterData\MasterBOM', 'Product_BOM_ID', 'ID')->whereIsdelete(0);
  }
  public function running()
  {
    return $this->hasOne('App\Models\ProductionPlan\CommandProductionDetail', 'Product_ID', 'ID')->where('Status','=',1);
  }
}
