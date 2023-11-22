<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    protected $fillable = [
        'FirstName',
        'LastName',
        'Email',
        'Password',
        'BloodGroup',
        'LastDonatedOn',
        'UserTypeId'
    ];
    public $timestamps = false;

}
