<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MUserRoleTabs extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'title',
        'm_company_tabs_id',
        'm_status_tabs_id'
    ];

    public function status(){
        return $this->hasOne(MStatusTabs::class,'id', 'm_status_tabs_id');
    }
}
