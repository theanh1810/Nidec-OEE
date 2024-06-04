<?php

namespace App\Models\Oee;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ProductionLog extends Model
{
    // use HasFactory;
    const CREATED_AT = 'Time_Created';
    const UPDATED_AT = 'Time_Updated';

    protected $table = 'Production_Log';
    protected $primaryKey = 'ID';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Command_Production_Detail_ID',
        'Master_Shift_ID',
        'Master_Machine_ID',
        'Total',
        'NG',
        'Runtime',
        'Stoptime',
        'Cavity',
        'Cycletime',
        'Note',
        'User_Created',
        'Time_Created',
        'User_Updated',
        'Time_Updated',
        'IsDelete',
        'Master_Status_ID'
    ];

    public function status()
    {
        return $this->hasOne('App\Models\MasterData\MasterStatus', 'ID', 'Master_Status_ID')->whereIsdelete(0);
    }
}
