<?php

namespace App\Http\Controllers\Admin;

use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function index()
    {
        return view(getThemeView('home.index'));
    }
}
