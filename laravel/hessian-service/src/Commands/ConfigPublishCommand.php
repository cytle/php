<?php

namespace HessianService59\Commands;

use Illuminate\Console\Command;

class ConfigPublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'service:config-publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发布hessian-service配置到项目中';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {
        $this->call('vendor:publish', ['--provider' => "HessianService59\Providers\HessianServiceServiceProvider"]);

    }

}
