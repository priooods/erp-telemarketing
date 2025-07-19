<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MUnitTab extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'title',
        'm_company_tabs_id'
    ];

    public function booking(){
        return $this->hasMany(TFinanceTab::class, 'm_unit_tabs_id','id');
    }

}
