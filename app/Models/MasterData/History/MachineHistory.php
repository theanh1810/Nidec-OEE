<?php

namespace App\Models\MasterData\History;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MachineHistory extends Model
{
    const CREATED_AT      = 'Time_Created';
    const UPDATED_AT      = 'Time_Updated';

    protected $table      = 'Machine_History';
    protected $primaryKey = 'ID';
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected $fillable   = [
        'Machine_ID', 'Name', 'Symbols', 'MAC', 'Stock_Min', 'Stock_Max', 'Note','Line_ID', 'Status', 'Time_Created', 'User_Created', 'Time_Updated', 'User_Updated', 'IsDelete'
    ];

    public function user_created()
    {
        return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
    }

    public function user_updated()
    {
        return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
    }
}
