<?php

namespace App\Models\WarehouseSystem;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class InventoryMachine extends Model
{
    const CREATED_AT = 'Time_Created';
    const UPDATED_AT = 'Time_Updated';

    protected $table = 'Inventory_Machine';
    protected $primarykey = 'ID';
    protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}
    protected $fillable = [
        'Machine_ID',
        'Quantity',
        'Note',
        'User_Created',
        'Time_Created',
        'User_Updated',
        'Time_Updated',
        'IsDelete'
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
