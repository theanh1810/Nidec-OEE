<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterMoldAccessories extends Model
{
  protected $connection = 'sqlsrv3';
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Master_Mold_Accessories';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
  	'Mold_ID'
  	,'Accessories_ID'
    ,'Quantity'
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

  public function mold()
  {
    return $this->hasOne('App\Models\MasterData\MasterMold', 'ID','Mold_ID')->whereIsdelete(0);
  }

  public function accessories()
  {
    return $this->hasOne('App\Models\MasterData\MasterAccessories', 'ID','Accessories_ID')->whereIsdelete(0);
  }

}
