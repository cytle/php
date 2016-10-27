<?php

namespace HessianService59\Exceptions;

use Log;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use LibHessian\Exceptions\HessianException;
use HessianService59\Exceptions\ServiceApiResultException;

class Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        if ($e instanceof ServiceApiResultException) {
            Log::info($e);
            $result = $e->getResult();
            // TODO 50010 定义为常量
            if ($result) {
                return new JsonResponse([
                    'code'   => 50010,
                    'status' => $result->status,
                    'msg'    => 'service 调用失败:' . $result->msg,
                    'data'   => $result->data
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);

            } else {

                return new JsonResponse([
                    'code'   => 50010,
                    'status' => 1,
                    'msg'    => 'service 调用失败',
                    'data'   => null
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        }


        if ($e instanceof HessianException) {
            // TODO 50010 定义为常量
            return new JsonResponse([
                'code'   => 50010,
                'status' => 1,
                'msg'    => 'service 调用失败:' . $e->getMessage(),
                'data'   => null

                ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }

        return $e;
    }

}
