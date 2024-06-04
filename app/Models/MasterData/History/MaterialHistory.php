<?php

namespace App\Models\MasterData\History;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MaterialHistory extends Model
{
    const CREATED_AT      = 'Time_Created';
    const UPDATED_AT      = 'Time_Updated';

    protected $table      = 'Material_History';
    protected $primaryKey = 'ID';
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected $fillable   = [
        'Material_ID', 'Name', 'Symbols', 'Unit_ID', 'Packing_ID', 'Supplier_ID', 'Model', 'Standard_Unit', 'Standard_Packing', 'Part_ID', 'Difference', 'Wire_Type', 'Spec', 'Note', 'Norms', 'Time_Created', 'User_Created', 'Time_Updated', 'User_Updated', 'IsDelete', 'Status'
    ];

    public function user_created()
    {
        return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
    }

    public function user_updated()
    {
        return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
    }

    public function unit()
    {
        return $this->hasOne('App\Models\MasterData\MasterUnit', 'ID', 'Unit_ID')->whereIsdelete(0);
    }

    public function materials()
    {
        return $this->hasOne('App\Models\MasterData\MasterMaterials', 'ID', 'Materials_ID')->whereIsdelete(0);
    }

    public function bom()
    {
        return $this->hasMany('App\Models\MasterData\MasterBOM', 'Product_ID', 'ID')->whereIsdelete(0);
    }
}
