<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic;
use DateTimeInterface;

class Line extends Model
{
  use Basic;

  const CREATED_AT      = 'Time_Created';
  const UPDATED_AT      = 'Time_Updated';
  
  protected $connection = 'sqlsrv2';
  
  protected $table      = 'Line';

  protected function serializeDate(DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }

  protected $fillable = [
  	'Name'
  	,'Location'
  	,'Temp1'
  	,'Temp2'
  	,'User_Created'
  	,'Time_Created'
  	,'User_Updated'
  	,'Time_Updated'
  	,'IsDelete'
  ];
}
