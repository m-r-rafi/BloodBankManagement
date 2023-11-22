<?php

namespace App\Providers;

use App\Repositories\BloodBank\BloodBankRepository;
use App\Repositories\BloodBank\IBloodBankRepository;
use App\Repositories\DonateBlood\DonateBloodRepository;
use App\Repositories\DonateBlood\IDonateBloodRepository;
use App\Repositories\ReceiveBlood\IReceiveBloodRepository;
use App\Repositories\ReceiveBlood\ReceiveBloodRepository;
use App\Repositories\StatusSetting\IStatusSettingRepository;
use App\Repositories\StatusSetting\StatusSettingRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IReceiveBloodRepository::class, ReceiveBloodRepository::class);
        $this->app->bind(IDonateBloodRepository::class, DonateBloodRepository::class);
        $this->app->bind(IBloodBankRepository::class, BloodBankRepository::class);
        $this->app->bind(IStatusSettingRepository::class, StatusSettingRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
