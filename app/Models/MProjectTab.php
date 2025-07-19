<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MProjectTab extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'title',
        'm_company_tabs_id'
    ];
}
