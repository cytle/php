<?php

namespace HessianService59\Commands;

use Symfony\Component\Console\Input\InputArgument;

class ConfigAppendCommand extends BaseConfigCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'service:config-append';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新增一个配置，在service.php和env.*.php中插入配置';


    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {

        $name = $this->getNameInput();

        $envApiName = get_service_api_env_name($name);


        // 写入service
        $path = $this->getPath('service');

        $this->makeDirectory($path);

        $config = $this->getRequire($path);

        if (is_null($config) || ! in_array($name, $config)) {
            $this->files->put($path, $this->buildConfig($path, [
                    $name
                ]));
        }


        $envInfo = $this->getEnvInfo();


        foreach ($envInfo as $envName => $item) {

            $config = $item['config'];
            $path = $item['path'];

            if (! is_null($config) && isset($config[$envApiName])) {
                // TODO 判断是否修改
                continue;

                $envApiUrl = $config[$envApiName];
            } else {
                $envApiUrl = '';
            }

            $envApiUrl = $this->ask("{$envName}-env-{$envApiName}", $envApiUrl);

            $this->files->put($path, $this->buildConfig($path, [
                    $envApiName => trim($envApiUrl)
                ]));
        }



        $this->info('配置新增成功.');
    }

    /**
     * 构建配置字符串
     *
     * @param  string  $path
     * @param  array  $array
     * @param  boolean  $clear
     * @return string
     */
    protected function buildConfig($path, array $array, $clear = false)
    {
        $sign = $this->appendSign;

        if (! $clear && $this->files->isFile($path)) {
            $stub = $this->files->get($path);
        } else {
            $stub = $this->getStub();
        }
        $arratStr = $this->array2str($array);


        return str_replace($sign, "{$arratStr}\n\n{$sign}" , $stub);
    }

    protected function getStub()
    {
        static $stub = null;
        if (is_null($stub)) {
            $stub = $this->files->get(__DIR__ . '/stubs/config.stub');
        }
        return $stub;
    }

    /**
     * 将数组转换为字符串
     *
     * @param  array  $array
     * @return string
     */
    protected function array2str(array $array)
    {
        $str = array_map(function($item, $key) {

            if (is_numeric($key)) {
                return "'{$item}',";
            }

            return "'{$key}' => '{$item}',";

        }, $array, array_keys($array));

        return "\n    " . implode($str, "\n\n    ");
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
            ['name', InputArgument::REQUIRED, 'api 名字，如OrderService 就为 order'],
        ];
    }
}
