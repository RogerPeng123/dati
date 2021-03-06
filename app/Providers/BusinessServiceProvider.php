<?php


namespace App\Providers;

use App\Services\Wechat\CycleService;
use App\Services\Wechat\Impl\CycleServiceImpl;
use App\Services\Wechat\Impl\LearnServiceImpl;
use App\Services\Wechat\Impl\NewsServiceImpl;
use App\Services\Wechat\LearnService;
use App\Services\Wechat\MemberService;
use App\Services\Wechat\Impl\MemberServiceImpl;
use App\Services\Wechat\NewsService;
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
        $this->app->bind(LearnService::class, LearnServiceImpl::class);
        $this->app->bind(NewsService::class, NewsServiceImpl::class);
    }
}