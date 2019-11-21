<?php

namespace App\Http\Controllers\Admin;

use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission');
    }
}
