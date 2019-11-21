<?php


namespace App\Toolkit;

use Illuminate\Http\JsonResponse;

final class ResponseApi
{
    const API_SUCCESS = 200; //正常返回
    const API_ERROR_NORAML = 201; //一般错误返回

    const PARAMETER_ERROR = 203; //请求参数错误
    const API_ROUTER_NOTHINGNESS = 404; //请求路由不存在
    const API_METHOD_ERROR = 405; //请求路由方式不正确
    const API_SUPER_AUTH_LACK = 406; //缺少超管权限
    const API_SUPER_OR_MANAGER_LACK = 407; //缺少超管或者门店创始人权限

    const NOT_LOGIN_ERROR = 207; //未登陆认证
    const JSON_NOT_ERROR = 208; // json解析失败

    /**
     * Api正确回传
     * Author: roger peng
     * Time: 2019-08-08 09:25
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public static function ApiSuccess(string $message, $data = null, int $code = self::API_SUCCESS): JsonResponse
    {
        return self::returnApi($message, $data, $code);
    }

    /**
     * Api错误回传
     * Author: roger peng
     * Time: 2019-08-08 09:25
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public static function ApiError(string $message, $data = null, int $code = self::API_ERROR_NORAML): JsonResponse
    {
        return self::returnApi($message, $data, $code);
    }

    /**
     *
     * Author: roger peng
     * Time: 2019/11/20 21:34
     * @param string $message
     * @param null $data
     * @param int $code
     * @return JsonResponse
     */
    private static function returnApi(string $message, $data = null, int $code): JsonResponse
    {
        $json = (object)[
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($json);
    }

}