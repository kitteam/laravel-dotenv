<?php

namespace Kitteam\LaravelDotEnv;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return DotEnv::class;
    }
}