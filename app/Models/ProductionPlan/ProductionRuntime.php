<?php

namespace App\Models\ProductionPlan;

use Illuminate\Database\Eloquent\Model;

class ProductionRuntime extends Model
{
    const CREATED_AT = 'Time_Created';
    const UPDATED_AT = 'Time_Updated';

    protected $table = 'Production_Runtime';
    protected $primarykey = 'ID';
    protected $fillable = [
        'Name',
        'Command_Production_Detail_ID',
        'Master_Product_ID',
        'Master_Machine_ID',
        'Plan_Quantity',
        'Actual_Quantity',
        'Actual_Good',
        'Actual_NG',
        'Plan_Start_Time',
        'Plan_End_Time',
        'Actual_Start_Time',
        'Actual_End_Time',
        'Status',
        'IsDelete'
    ];

    public function production_log() {
        return $this->hasMany('App\Models\Oee\ProductionLog', 'Production_Runtime_ID', 'ID');
    }

    public function master_product() {
        return $this->hasOne('App\Models\MasterData\MasterProduct', 'ID', 'Master_Product_ID')->whereIsdelete(0);
    }
}
