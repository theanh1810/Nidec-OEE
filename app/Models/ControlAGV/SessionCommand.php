<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic;
use DateTimeInterface;

class SessionCommand extends Model
{
    use Basic;
  
    const CREATED_AT      = 'Time_Created';
    const UPDATED_AT      = 'Time_Updated';
  
    protected $connection = 'sqlsrv';
  
    protected $table      = 'Session_Command';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'PID'
        ,'From'
        ,'Code'
        ,'To'
        ,'Ratio'
        ,'Command'
        ,'Time_Created'
        ,'User_Created'
        ,'Time_Updated'
        ,'User_Updated'
        ,'IsDelete'
    ];
}
