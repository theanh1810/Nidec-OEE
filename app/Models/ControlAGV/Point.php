<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic;
use DateTimeInterface;

class Point extends Model
{
  use Basic;
  
  const CREATED_AT      = 'Time_Created';
  const UPDATED_AT      = 'Time_Updated';
  
  protected $connection = 'sqlsrv2';
  
  protected $table      = 'Point';

  protected function serializeDate(DateTimeInterface $date)
	{
	  return $date->format('Y-m-d H:i:s');
	}

  protected $fillable = [
  	'Name'
  	,'Layout'
  	,'MapName'
  	,'Direction'
  	,'Offset'
  	,'StatusID'
  	,'X'
  	,'Y'
  	,'Code'
  	,'Time_Created'
  	,'User_Created'
  	,'Time_Updated'
  	,'User_Updated'
  	,'IsDelete'
  ];
}
