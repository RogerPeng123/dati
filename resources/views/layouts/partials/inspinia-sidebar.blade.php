@inject('menuPresenter','App\Repositories\Presenters\Admin\MenuPresenter')
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header text-center">
                <div class="dropdown profile-element">
                    <h1 class="text-info"><strong class="font-bold">{{ config('app.name') }}</strong></h1>
                </div>
                <div class="logo-element">
                    {{ config('app.name') }}
                </div>
            </li>

            {!!$menuPresenter->sidebarMenuList($sidebarMenu)!!}

        </ul>
    </div>
</nav>