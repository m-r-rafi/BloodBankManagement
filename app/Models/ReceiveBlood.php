<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiveBlood extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'Quantity',
        'ReceivedOn',
        'UserId',
        'BloodId',
        'StatusId'
    ];

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
