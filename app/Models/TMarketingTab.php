<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TMarketingTab extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'photo',
        'atasan_id',
        'm_status_tabs_id',
    ];

    public function atasan(){
        return $this->hasOne(User::class,'id', 'atasan_id');
    }

    public function status()
    {
        return $this->hasOne(MStatusTabs::class, 'id', 'm_status_tabs_id');
    }
}
