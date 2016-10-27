<?php

namespace HessianService59\Commands;

use Symfony\Component\Console\Input\InputArgument;

class ConfigListCommand extends BaseConfigCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'service:config-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '查看配置列表';


    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {

        $type = $this->getTypeInput() ? : 'service';

        $this->warn('-------------');
        $this->warn($type);
        $this->warn('-------------');
        $this->listConfig($type);
    }

    /**
     * 打印配置
     *
     * @param  string  $name
     * @return void
     */
    protected function listConfig($name)
    {
        $config = $this->getRequire($this->getPath($name));

        if (is_null($config)) {
            $this->error('无该配置');
        } else {
            if ($name === 'service') {
                $this->info("  " . implode($config, "\n  "));
            } else {

                $rows = array_map(function($item, $key) {
                    return [$key, $item];
                }, $config, array_keys($config));

                $this->table(['name', 'value'], $rows);
            }
        }
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getTypeInput()
    {
        return trim($this->argument('type'));
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['type', InputArgument::OPTIONAL, 'service/envName 默认为service'],
        ];
    }
}
