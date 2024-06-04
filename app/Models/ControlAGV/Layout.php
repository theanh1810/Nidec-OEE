<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic;
use DateTimeInterface;

class Layout extends Model
{
    use Basic;

    const CREATED_AT      = 'Time_Created';
    const UPDATED_AT      = 'Time_Updated';
    
    protected $connection = 'sqlsrv2';

    protected $primaryKey = 'ID';
    
    protected $table      = 'LAYOUT';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Name'
        ,'IMAGE'
        ,'W'
        ,'H'
        ,'X'
        ,'Y'
        ,'scale'
        ,'Time_Created'
        ,'User_Created'
        ,'Time_Updated'
        ,'User_Updated'
        ,'IsDelete'
    ];
}
