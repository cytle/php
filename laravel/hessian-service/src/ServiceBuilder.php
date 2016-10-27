<?php

namespace HessianService59;

use Exception;
use LibHessian\Exceptions\HessianException;
use HessianService59\Exceptions\ServiceApiResultException;
use HessianService59\Exceptions\Handler;

use LibHessian\HessianHelpers;
use HessianService59\Configs\ParseFilters;

/**
 * 请求Service的方法封装
 */
class ServiceBuilder {

    const SUCCESS_STATUS = 0;

    protected $apiConfig = [];
    protected $hessianOptions = [];

    /**
     * 设置api路径配置
     */
    public function setApiConfig(array $apiConfig)
    {
        $this->apiConfig = $apiConfig;

        return $this;
    }

    /**
     * 设置hessian配置
     */
    public function setHessianOptions(array $options)
    {
        if (! isset($options['parseFilters'])) {
            $options['parseFilters'] = ParseFilters::getFilters();
        }

        $this->hessianOptions = $options;

        return $this;
    }


    /**
     * 设置配置，config中配置将分到setApiConfig、setHessianOptions中
     * $config = [
     *   'api' => [], // api配置
     *   'hessian' => [], hessian 配置
     * ]
     */
    public function setConfig(array $config)
    {
        if (! isset($config['api'])) {
            $config['api'] = [];
        }
        if (! isset($config['hessian'])) {
            $config['hessian'] = [];
        }
        $this->setApiConfig($config['api']);
        $this->setHessianOptions($config['hessian']);

        return $this;
    }


    /**
     * 获取单个api配置，如果$name为null，返回所有api配置
     */
    public function getApiConfig($name = null)
    {
        if (is_null($name)) {
            return $this->apiConfig;
        }

        if (! isset($this->apiConfig[$name])) {
            $hessianException = new HessianException('Missing services api ' . $name );
            throw $hessianException;
        }

        return $this->apiConfig[$name];
    }


    /**
     * 获取Service api 地址
     * $name 名称
     * @author xsp
     *
     */
    public function getApiUrl($name)
    {
        $apiConfig = $this->getApiConfig($name);

        if (! isset($apiConfig['url'])) {
            $hessianException = new HessianException('Missing services api ' . $name . ' url');
            throw $hessianException;
        }

        return $apiConfig['url'];
    }

    /**
     * 获取完整的Service api 地址
     *  $hessianUrl = getFullApiUrl('order', '/querySellerOrder');
     * @author xsp
     *
     */
    public function getFullApiUrl($name, $path) {
        return $this->getApiUrl($name) . $path;
    }

    /**
     * 获取hessian options
     */
    public function getHessianOptions()
    {
        return $this->hessianOptions;
    }


    /**
     * Service查询
     *  query($url, $method, array $arguments = []);
     * @author xsp
     *
     * TODO 并非所有service都是使用Hessian，这里需要根据情况选择 (现在只有Hessian
     *
     */
    public function query($url, $method, array $arguments = [], array $options = [])
    {
        $gOptions = $this->getHessianOptions();
        $options = array_merge($gOptions, $options);

        return HessianHelpers::query(
            $url,
            $method,
            $arguments,
            $options);
    }

    /**
     * Service查询，如果返回状态不为0，抛出异常
     *  querySuccessOrFail($url, $method, array $arguments = []);
     * @author xsp
     *
     */
    public function querySuccessOrFail($url, $method, array $arguments = [], array $options = []) {
        // 查询
        $result = $this->query(
            $url,
            $method,
            $arguments,
            $options);

        $result->status = intval($result->status);

        // 状态不对抛出ServiceApiResultException
        if ($result->status !== static::SUCCESS_STATUS) {
            $serviceApiResultException = new ServiceApiResultException($result->msg ?: 'Service失败');

            $serviceApiResultException->setRequest([
                'url' => $url,
                'method' => $method,
                'arguments' => $arguments,
            ]);

            $serviceApiResultException->setResult($result);

            throw $serviceApiResultException;
        }

        return $result;
    }

    /**
     * Service查询默认返回其中data，如果状态错误，抛出异常
     *
     * @return object|array
     */
    public function queryDataSuccessOrFail() {
        $result = call_user_func_array([$this, 'querySuccessOrFail'], func_get_args());
        return $result->data;
    }

    public function renderException($request, Exception $e)
    {
        $handler = new Handler();
        return $handler->render($request, $e);
    }

    /**
     * 一个ServiceBuilder实例，这个方法返回的实例都为同一个
     */
    public static function getServiceBuilder()
    {
        static $builder = null;
        if (is_null($builder)) {
            $builder = new static();
        }
        return $builder;
    }
}
