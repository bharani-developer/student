<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class student extends Model
{
    protected $table ="students";
    protected $fillable =[
        'name',
        'gender',
        'address',
        'department',
        'branch',
        
    ];
}
