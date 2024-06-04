<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use App\Models\Basic;

class MasterAGV extends Model
{
    // use Basic;

    protected $connection = 'sqlsrv2';
    const CREATED_AT      = 'Time_Created';
    const UPDATED_AT      = 'Time_Updated';
    protected $table      = 'AGV';
    protected $primaryKey = 'ID';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable   = [
        'Name', 'IP', 'MAC', 'Image', 'Type', 'Home', 'Active', 'Manager_AGV', 'Part', 'Note', 'Maintenance_Date', 'Maintenance_Time', 'Time_Created', 'User_Created', 'Time_Updated', 'User_Updated', 'IsDelete'
    ];

    public function manager()
    {
        return $this->hasOne('App\Models\User', 'id', 'Manager_AGV')->whereIsdelete(0);
    }

    public function user_created()
    {
        return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
    }

    public function user_updated()
    {
        return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
    }

    //   public function log()
    //   {
    //     return $this->hasMany('App\Models\ControlAGV\AgvLog', 'AGV', 'ID');
    //   }

    // public function warehouseScope()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\MasterData\MasterWarehouse',
    //         'STI_TRANSPORT.dbo.AGV_SCOPE',
    //         'AGVID',
    //         'WarehouseID'
    //     )
    //         ->withPivot('User_Created', 'User_Updated', 'IsDelete')
    //         ->withTimestamps()
    //         ->wherePivot('IsDelete', 0);
    // }
}
