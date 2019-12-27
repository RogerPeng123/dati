<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiAuthenticationException;
use App\Models\IntrgralLog;
use App\Models\Members;
use App\Toolkit\TimeToolkit;
use Closure;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;

class ApiLoginCheck
{
    /**
     * @var Members
     */
    private $memberModel;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var IntrgralLog
     */
    private $intrgralLog;

    public function __construct(Members $memberModel, LoggerInterface $logger, IntrgralLog $intrgralLog)
    {
        $this->memberModel = $memberModel;
        $this->logger = $logger;
        $this->intrgralLog = $intrgralLog;
    }

    /**
     * Handle an incoming request.
     * Author: roger peng
     * Time: 2019/12/27 15:08
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws ApiAuthenticationException
     * @throws \Throwable
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('x-api-key', null);
        Cache::forget('API_TOKEN_MEMBER_' . $token);
        if (!$token) {
            throw new ApiAuthenticationException();
        }

        if (!Cache::has('API_TOKEN_MEMBER_' . $token)) {
            $this->memberModel = $this->memberModel->where('api_token', $token)->first();
            if (!$this->memberModel) {
                throw_unless($this->memberModel,
                    ApiAuthenticationException::class);
            }

            unset($this->memberModel->password);
            unset($this->memberModel->deleted_at);
            unset($this->memberModel->created_at);

            $this->memberModel->cover = env('APP_URL') . $this->memberModel->cover;

            Cache::put('API_TOKEN_MEMBER_' . $this->memberModel->api_token, $this->memberModel, 60 * 24 * 30);
        }

        $members = Cache::get('API_TOKEN_MEMBER_' . $token);

        //检查当前用户是否已经领取登录积分
        $checkState = $this->intrgralLog->where([
            'm_id' => $members->id, 'type' => $this->intrgralLog::TYPE_LOGIN
        ])->whereBetween('created_at', TimeToolkit::getDayStarAndEnd())->exists();

        if (!$checkState) {
            //添加积分记录
            $this->intrgralLog->insert([
                'm_id' => $members->id,
                'type' => $this->intrgralLog::TYPE_LOGIN,
                'num' => config('integral.login.today_count_num'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);

            $this->memberModel->where('id', $members->id)->increment('integral', config('integral.login.today_count_num'));
        }

        return $next($request);
    }
}
