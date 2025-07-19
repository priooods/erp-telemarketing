<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TUserRoleDetailTab extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'm_user_role_tabs_id',
        'users_id',
    ];

    public function role(){
        return $this->hasOne(MUserRoleTabs::class,'id', 'm_user_role_tabs_id');
    }
}
