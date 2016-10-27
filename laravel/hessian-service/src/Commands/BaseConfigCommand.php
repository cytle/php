<?php
namespace HessianService59\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class BaseConfigCommand extends Command {

    protected $rootPath = 'vendor/store59/hessian-service/src/Configs/ServiceApiConfig';
    protected $appendSign = '##APPEND';

    protected $envInfo = null;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;

        $this->rootPath = base_path($this->rootPath);

    }

    /**
     * 获取env 配置
     *
     * @return array
     */
    protected function getEnvInfo($name = null)
    {
        if (is_null($this->envInfo)) {

            $pattern = '/^env\.(.+)\.php$/';

            $paths = $this->files->files($this->rootPath);
            $envInfo = [];

            foreach ($paths as $path) {

                $sPath = str_replace($this->rootPath . '/', '', $path);

                if (preg_match($pattern, $sPath, $matches)) {
                    $envName = $matches[1];
                } else {
                    continue;
                }

                $envInfo[$envName] = [
                    'envName' => $envName,
                    'path' => $path,
                    'config' => $this->getRequire($path)
                ];

            }
            $this->envInfo = $envInfo;
        }
        if (is_null($name)) {
            return $this->envInfo;
        }

        return isset($this->envInfo[$name]) ? $this->envInfo[$name] : null;
    }



    /**
     * 获取地址内容
     *
     * @param  string  $path
     * @return array
     */
    protected function getRequire($path)
    {
        if ($this->files->isFile($path)) {
            return $this->files->getRequire($path);
        } else {
            return null;
        }
    }



    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {

        return $this->rootPath . '/' . $name . '.php';
    }


    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }
}
