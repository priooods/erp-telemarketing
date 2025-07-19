<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TLeadDetailTabs extends Model
{
    protected $fillable = [
        'created_by',
        't_lead_tabs_id',
        'm_status_tabs_id',
        't_marketing_tabs_id',
        'visit_date',
        'description',
    ];

    public function header()
    {
        return $this->hasOne(TLeadTabs::class, 'id', 't_lead_tabs_id');
    }

    public function marketing(){
        return $this->hasOne(TMarketingTab::class,'id', 't_marketing_tabs_id');
    }

    public function status()
    {
        return $this->hasOne(MStatusTabs::class, 'id', 'm_status_tabs_id');
    }
}
