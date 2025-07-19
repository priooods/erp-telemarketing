<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TFinanceTab extends Model
{
    protected $fillable = [
        'created_by',
        't_marketing_tabs_id',
        't_lead_tabs_id',
        'm_unit_tabs_id',
        'paid',
        'booking_date',
        'm_status_tabs_id',
        'description',
    ];

    public function lead()
    {
        return $this->hasOne(TLeadTabs::class, 'id', 't_lead_tabs_id');
    }

    public function unit()
    {
        return $this->hasOne(MUnitTab::class, 'id', 'm_unit_tabs_id');
    }

    public function marketing()
    {
        return $this->hasOne(TMarketingTab::class, 'id', 't_marketing_tabs_id');
    }

    public function status()
    {
        return $this->hasOne(MStatusTabs::class, 'id', 'm_status_tabs_id');
    }
}
