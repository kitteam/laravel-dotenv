<?php

namespace Kitteam\LaravelDotEnv;

use Kitteam\LaravelDotEnv\Commands\CopyDotEnvCommand;
use Kitteam\LaravelDotEnv\Commands\DeleteDotEnvCommand;
use Kitteam\LaravelDotEnv\Commands\GetDotEnvCommand;
use Kitteam\LaravelDotEnv\Commands\SetDotEnvCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        app()->loadEnvironmentFrom('.env');
        $this->app->singleton(DotEnv::class, function () {
            return new DotEnv(app()->environmentFilePath());
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetDotEnvCommand::class,
                GetDotEnvCommand::class,
                DeleteDotEnvCommand::class,
                CopyDotEnvCommand::class,
            ]);
        }

    }


}