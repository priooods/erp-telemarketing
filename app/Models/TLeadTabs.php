<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TLeadTabs extends Model
{
    protected $fillable = [
        'created_by',
        'm_project_tabs_id',
        'm_status_tabs_id',
        'customer_nama',
        'customer_phone',
        'customer_address',
        'lead_in',
        'description',
    ];

    protected $appends = ['last_status'];

    public function getLastStatusAttribute(){
        if($finance = TFinanceTab::where('t_lead_tabs_id', $this->id)->with('status')->first()){
            return $finance->status->title;
        } elseif($leads = TLeadDetailTabs::where('t_lead_tabs_id', $this->id)->with('status')->orderBy('id','desc')->first()){
            return $leads->status->title;
        } else {
            return null;
        }
    }

    public function user(){
        return $this->hasOne(User::class,'id', 'created_by');
    }

    public function project(){
        return $this->hasOne(MProjectTab::class,'id', 'm_project_tabs_id');
    }

    public function status()
    {
        return $this->hasOne(MStatusTabs::class, 'id', 'm_status_tabs_id');
    }

    public function booking(){
        return $this->hasOne(TFinanceTab::class, 't_lead_tabs_id','id');
    }

    public function detail()
    {
        return $this->hasMany(TLeadDetailTabs::class, 't_lead_tabs_id', 'id');
    }
}
