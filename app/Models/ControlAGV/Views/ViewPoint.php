<?php

namespace App\Models\ControlAGV\Views;

use Illuminate\Database\Eloquent\Model;

class ViewPoint extends Model
{
    protected $connection = 'sqlsrv2';
    
    protected $table      = 'View_Point';
}
