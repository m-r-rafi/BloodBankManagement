<?php

namespace App\Repositories\User;

use App\Http\DTOs\EligibleUpdateModelDTO;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function list(): array
    {
        $list = User::all();
        return $list->toArray();
    }
    public function isEligible(int $id): bool
    {
        $user = $this->find($id);

        if (!$user) {
            return false;
        }

        $now = Carbon::now();
        $lastDonatedDate = Carbon::parse($user->LastDonatedOn);

        $monthsSinceLastDonation = $now->diffInDays($lastDonatedDate) / 30;

        if ($monthsSinceLastDonation > 4) {
            return true;
        }

        return false;
    }

    public function isEligibleUpdate($obj): bool
    {
        $user = $this->find($obj->userId);

        if (!$user) {
            return false;
        }

        if (($obj->date) < ($user->LastDonatedOn)) {
            return false;
        }

        $date = Carbon::parse($obj->date);
        $lastDonatedDate = Carbon::parse($user->LastDonatedOn);
        $monthsSinceLastDonation = $date->diffInDays($lastDonatedDate) / 30;

        if ($monthsSinceLastDonation < 4) {
            return false;
        }
        $user->LastDonatedOn = $obj->date;
        $user->save();
        return true;
    }

    public function authenticate($email, $password)
    {
        return User::where(function ($query) use ($email) {
            $query->Where('Email', $email);
        })->where('Password', $password)->first();
    }
}

