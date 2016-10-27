<?php

namespace HessianService59\Commands;

use Symfony\Component\Console\Input\InputArgument;

class ConfigCommand extends BaseConfigCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'service:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '查看配置';


    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {
        $name = $this->getNameInput();
        $envApiName = get_service_api_env_name($name);

        $serviceConfig = $this->getRequire($this->getPath('service'));

        if (is_null($serviceConfig)) {
            $this->error('service的配置文件不存在，请添加一个service');
            $this->info('$ php artisan service:config-append <name>');
            return null;
        }

        if (! in_array($name, $serviceConfig)) {
            $this->error("service的配置中不存在{$name}项，请添加");
            $this->info('$ php artisan service:config-append <name>');
            return null;
        }


        $envInfo = $this->getEnvInfo();

        $rows = array_map(function($info) use ($envApiName){
            $config = $info['config'] ? : [];
            return [
                $info['envName'],
                (isset($config[$envApiName]) ? $config[$envApiName] : '')
            ];
        }, $envInfo);

        $this->warn('-------------');
        $this->warn($name);
        $this->warn('-------------');
        $this->table(['env', 'api url'], $rows);


    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return lcfirst(trim($this->argument('name')));
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'service name'],
        ];
    }
}
