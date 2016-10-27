<?php
namespace HessianService59\Configs;


/**
* hessian-Service api 配置获取
*/
class ServiceApiConfigMake
{
    // 当前环境
    protected $appEnv;

    // 存在的url配置
    protected $ServiceEnvConfig;

    // 存在的Service
    protected $ServiceExists;

    function __construct()
    {
        $this->appEnv = env('APP_ENV', 'product');
        if ($this->appEnv === 'testing') {
            $this->appEnv = 'dev';
        }
        $this->ServiceEnvConfig = $this->getServiceApiConfigFromFile('env.' . $this->appEnv);
        $this->ServiceExists = $this->getServiceApiConfigFromFile('service');
    }

    public function getConfig()
    {
        $serviceNames = $this->ServiceExists;
        $config = [];

        foreach ($serviceNames as $name) {
            $config[$name] = [
                'url' => $this->getServiceApiUrl($name)
            ];
        }

        return $config;
    }

    public function getServiceApiUrl($name)
    {
        $name = get_service_api_env_name($name);
        return env($name, $this->env($name));
    }


    protected function env($name)
    {
        if (isset($this->ServiceEnvConfig[$name])) {
            return $this->ServiceEnvConfig[$name];
        }
        return null;
    }


    protected function getServiceApiConfigFromFile($filename)
    {
        $filename = __DIR__ . "/ServiceApiConfig/" . $filename . '.php';

        if (file_exists($filename)) {
            $c = require $filename;
            return $c;
        }

        return [];
    }


}

