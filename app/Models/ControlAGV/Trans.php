<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic;
use DateTimeInterface;

class Trans extends Model
{
    use Basic;

    const CREATED_AT      = 'Time_Created';
    const UPDATED_AT      = 'Time_Updated';

    protected $connection = 'sqlsrv2';

    protected $table      = 'Trans';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'AGV', 'Line_ID', 'Return_Point', 'From_Point', 'StatusID', 'ProcessID', 'Task', 'Type', 'Confirm', 'Materials_ID', 'Estimate', 'Quantity', 'Export_Material_ID', 'Time_Created', 'User_Created', 'Time_Updated', 'User_Updated', 'IsDelete'
    ];

    public function agv()
    {
        return $this->hasOne('App\Models\MasterData\MasterAGV', 'ID', 'AGV')->whereIsdelete(0);
    }

    public function lines()
    {
        return $this->hasOne('App\Models\MasterData\MasterMachine', 'ID', 'Line_ID')->whereIsdelete(0);
    }

    public function fromPoint()
    {
        return $this->hasOne('App\Models\MasterData\MasterMachine', 'ID', 'From_Point')->whereIsdelete(0);
    }

    public function materials()
    {
        return $this->hasOne('App\Models\MasterData\MasterMaterials', 'ID', 'Materials_ID')->whereIsdelete(0);
    }

    public function returnPoint()
    {
        return $this->hasOne('App\Models\MasterData\MasterWarehouseDetail', 'ID', 'Return_Point')->whereIsdelete(0);
    }
}
