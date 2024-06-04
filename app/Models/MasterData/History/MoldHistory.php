<?php

namespace App\Models\MasterData\History;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MoldHistory extends Model
{
  const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Mold_History';
	protected $primaryKey = 'ID';
  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }
	protected $fillable   = [
    'Mold_ID'   
  	,'Name'
  	,'Symbols'
  	,'CAV_Max'
  	,'Note'
    ,'Status'
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
  public function detail()
  {
    return $this->hasMany('App\Models\Maintance\MaintanceDetail', 'Mold_ID', 'ID')->whereIsdelete(0);
  }
  public function group()
  {
      return $this->belongsToMany('App\Models\MasterData\MasterAccessories', 'Master_Mold_Accessories', 'Mold_ID', 'Accessories_ID')->wherePivot('Isdelete', 0);
  }
}
