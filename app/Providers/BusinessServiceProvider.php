<?php


namespace App\Providers;

use App\Services\Wechat\CycleService;
use App\Services\Wechat\Impl\CycleServiceImpl;
use App\Services\Wechat\MemberService;
use App\Services\Wechat\Impl\MemberServiceImpl;
use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(MemberService::class, MemberServiceImpl::class);
        $this->app->bind(CycleService::class, CycleServiceImpl::class);
    }
}