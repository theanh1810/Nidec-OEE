<?php

namespace App\Models\ControlAGV\Views;

use Illuminate\Database\Eloquent\Model;

class ViewMap extends Model
{
    protected $connection = 'sqlsrv2';
    
    protected $table      = 'View_Map';
}
