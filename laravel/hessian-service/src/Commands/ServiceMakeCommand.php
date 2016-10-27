<?php

namespace HessianService59\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class ServiceMakeCommand extends GeneratorCommand
{

    protected $rootNamespace = 'HessianService59';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'service:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新增一个HessianService';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = '服务';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {
        $name = $this->parseName($this->getNameInput());
        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' 已经存在!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));
        $this->info($this->type.' 创建成功.');
        $this->info('path:' . $path);

        $this->info('现在进行添加配置');

        $this->call('service:config-append', ['name' => $this->getApiNameInput()]);

    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function parseName($name)
    {
        $rootNamespace = $this->rootNamespace;

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }
        $name = ucfirst($name);


        return $this->parseName($this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name);
    }

        /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $rootNamespace = $this->rootNamespace;
        $rootPath = base_path('vendor/store59/hessian-service/src');

        $name = str_replace($rootNamespace, '', $name);

        return $rootPath . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services\\'.ucfirst($this->getApiNameInput()).'Service';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $name = ucfirst($name);
        echo $name . PHP_EOL;
        echo $name . PHP_EOL;
        $class = str_replace($this->getNamespace($name).'\\', '', $name);


        $dummy = [
            'DummyApiName',
            'DummyApiPath',
            'DummyClass',
        ];

        $reality = [
            lcfirst($this->getApiNameInput()),
            lcfirst($class),
            $class,
        ];


        return str_replace($dummy, $reality, $stub);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('apiPath'));
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getApiNameInput()
    {
        return trim($this->argument('apiName'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['apiName', InputArgument::REQUIRED, 'api 名字，如OrderService 就为 order'],
            ['apiPath', InputArgument::REQUIRED, 'api path'],
        ];
    }
}
