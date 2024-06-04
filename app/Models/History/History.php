<?php

namespace App\Models\History;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic;
use DateTimeInterface;

class History extends Model
{
    use Basic;

    const CREATED_AT      = 'Time_Created';
    const UPDATED_AT      = 'Time_Updated';

    // protected $connection = 'sqlsrv2';

    protected $table      = 'Histories';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Table_Name', 'ID_Main', 'Action_Name', 'Data_Table'
    ];
}
