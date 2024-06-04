<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    const CREATED_AT      = 'Time_Created';
    const UPDATED_AT      = 'Time_Updated';
  
    protected $connection = 'sqlsrv2';
  
    protected $table      = 'Map';

    protected $primaryKey = 'ID';

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }

    protected $fillable = [
        'Name'
        ,'CODE'
        ,'CODE_X'
        ,'N0'
        ,'N1'
        ,'N2'
        ,'N3'
        ,'N4'
        ,'N5'
        ,'S0'
        ,'S1'
        ,'S2'
        ,'S3'
        ,'S4'
        ,'S5'
        ,'Layout'
        ,'Gate'
        ,'X'
        ,'Y'
        ,'W'
        ,'Time_Created'
        ,'User_Created'
        ,'Time_Updated'
        ,'User_Updated'
        ,'IsDelete'
    ];
}
