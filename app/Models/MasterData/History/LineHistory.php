<?php

namespace App\Models\MasterData\History;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class LineHistory extends Model
{
	const CREATED_AT = 'Time_Created';
	const UPDATED_AT = 'Time_Updated';

	protected $connection = 'sqlsrv';
	protected $table = 'Line_History';
	protected $primaryKey = 'ID';
	protected $fillable = [
        'Line_ID',
		'Name',
		'Note',
        'Status',
		'Time_Created',
		'User_Created',
		'Time_Updated',
		'User_Updated',
		'IsDelete'
	];

	protected function serializeDate(DateTimeInterface $date)
	{
		return $date->format('Y-m-d H:i:s');
	}

	public function user_created()
	{
		return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
	}

	public function user_updated()
	{
		return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
	}

    public function lines()
    {
        return $this->hasOne('App\Models\MasterData\MasterLine', 'ID', 'Line_ID')->whereIsdelete(0);
    }
}
