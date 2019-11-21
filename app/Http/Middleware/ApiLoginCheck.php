<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiResponseExceptions;
use App\Models\Members;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;

class ApiLoginCheck
{
    private $memberModel;

    public function __construct(Members $memberModel)
    {
        $this->memberModel = $memberModel;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('api_token', '[\u5c0f\u6cfd\u739b\u5229\u4e9a]');
        if (!Cache::has('API_TOKEN_MEMBER_' . $token)) {
            $this->memberModel = $this->memberModel->where(['api_token', $token])->first();
            throw_unless($this->memberModel, AuthenticationException::class, '当前用户未登录');

            Cache::put('API_TOKEN_MEMBER_' . $this->memberModel->api_token, $this->memberModel);
        }

        return $next($request);
    }
}
