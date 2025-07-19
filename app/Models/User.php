<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'm_company_tabs_id',
        'm_status_tabs_id',
    ];

    public function company()
    {
        return $this->hasOne(MCompanyTab::class,'id', 'm_company_tabs_id');
    }

    public function role_detail()
    {
        return $this->hasOne(TUserRoleDetailTab::class, 'users_id', 'id');
    }
    
    public function status()
    {
        return $this->hasOne(MStatusTabs::class, 'id', 'm_status_tabs_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
