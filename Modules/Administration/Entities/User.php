<?php

namespace Modules\Administration\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class User extends Authenticatable
{
    use Notifiable, HasRoles, ThrottlesLogins;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'address',
        'phone',
        'avatar',
        'email',
        'password',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token', 'created_at', 'updated_at'];

    /**
     * Get user full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    /**
     * Get user gender.
     */
    public function getGenderAttribute($value)
    {
        switch ($value) {
            case "0":
                return "Male";
                break;
            case "1":
                return "Female";
                break;
            default:
                return "Other";
        }
    }

    /**
     * Get user status.
     */
    public function getStatusAttribute($value)
    {
        switch ($value) {
            case "0":
                return "Inactive";
                break;

            case "1":
                return "Active";
                break;

            default:
                return "No status available";
        }
    }
}