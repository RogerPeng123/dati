<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiAuthenticationException;
use App\Exceptions\ApiResponseExceptions;
use App\Models\Members;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;

class ApiLoginCheck
{
    /**
     * @var Members
     */
    private $memberModel;

    private $logger;

    public function __construct(Members $memberModel, LoggerInterface $logger)
    {
        $this->memberModel = $memberModel;
        $this->logger = $logger;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws AuthenticationException
     * @throws \Throwable
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('x-api-key', null);
        if (!$token) {
            throw new ApiAuthenticationException();
        }

        if (!Cache::has('API_TOKEN_MEMBER_' . $token)) {
            $this->memberModel = $this->memberModel->where('api_token', $token)->first();
            if (!$this->memberModel) {
                throw_unless($this->memberModel,
                    ApiAuthenticationException::class);
            }

            Cache::put('API_TOKEN_MEMBER_' . $this->memberModel->api_token, $this->memberModel, 60 * 24 * 30);
        }

        return $next($request);
    }
}
