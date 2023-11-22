<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonateBlood extends Model
{
    protected $fillable = [
        'Quantity',
        'DonatedOn',
        'UserId',
        'BloodId',
        'StatusId'
    ];

    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }


    public function bloodBank()
    {
        return $this->belongsTo(BloodBank::class, 'BloodId');
    }


    public function statusSetting()
    {
        return $this->belongsTo(StatusSetting::class, 'StatusId');
    }

}
