<?php

namespace HessianService59\Providers;

use Log;
use Illuminate\Support\ServiceProvider;

use HessianService59\ServiceBuilder;
use HessianService59\Commands\ServiceMakeCommand;
use HessianService59\Commands\ConfigPublishCommand;
use HessianService59\Commands\ConfigAppendCommand;
use HessianService59\Commands\ConfigListCommand;
use HessianService59\Commands\ConfigCommand;

class HessianServiceServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // the default configuration file
        $this->publishes(array(
            __DIR__ . '/../Configs/config.php' => config_path('hessian_service.php'),
        ), 'config');
    }

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'ServiceMake' => 'command.service.make',
        'ServiceConfigPublish' => 'command.service.config-public',
        'ServiceConfigAppend' => 'command.service.config-append',
        'ServiceConfigList' => 'command.service.config-list',
        'ServiceConfig' => 'command.service.config',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;


        $app->singleton('hessianService', function ($app) {

            return ServiceBuilder::getServiceBuilder();
        });


        $this->registerCommand();


        // hessian_service::config is Laravel 4.x
        $config = $app['config']['hessian_service'] ?: $app['config']['hessian_service::config'];

        // Make sure we don't crash when we did not publish the config file
        if (is_null($config)) {
            $config = [];
        }

        $builder = ServiceBuilder::getServiceBuilder();
        $builder->setConfig($config);

    }

    public function registerCommand($value='')
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerServiceMakeCommand()
    {
        $this->app->singleton('command.service.make', function ($app) {
            return new ServiceMakeCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerServiceConfigPublishCommand()
    {
        $this->app->singleton('command.service.config-public', function ($app) {
            return new ConfigPublishCommand();
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerServiceConfigAppendCommand()
    {
        $this->app->singleton('command.service.config-append', function ($app) {
            return new ConfigAppendCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerServiceConfigListCommand()
    {
        $this->app->singleton('command.service.config-list', function ($app) {
            return new ConfigListCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerServiceConfigCommand()
    {
        $this->app->singleton('command.service.config', function ($app) {
            return new ConfigCommand($app['files']);
        });
    }




    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('hessianService');
    }
}
