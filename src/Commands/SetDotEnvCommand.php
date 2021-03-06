<?php


namespace Kitteam\LaravelDotEnv\Commands;


use Illuminate\Console\Command;
use InvalidArgumentException;
use Kitteam\LaravelDotEnv\DotEnv;

class SetDotEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:set {key} {value?} {--env=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set .env key value';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dotEnvPath = $this->option('env') ?? app()->environmentFilePath();
        $dotEnv = new DotEnv($dotEnvPath);

        [$key, $value] = $this->getKeyValue();
        if ($dotEnv->set($key, $value)) {
            $this->info("Environment variable with key '{$dotEnv->getKey()}' has been changed from '{$dotEnv->getOldValue(false)}' to '{$dotEnv->getNewValue(false)}' in '{$dotEnvPath}'");
        } else {
            $this->info("A new environment variable with key '{$dotEnv->getKey()}' has been set to '{$dotEnv->getNewValue(false)}' in '{$dotEnvPath}'");
        }
    }


    /**
     * Determine what the supplied key and value is from the current command.
     *
     * @return array
     */
    protected function getKeyValue(): array
    {
        $key = $this->argument('key');
        $value = $this->argument('value');
        if (!isset($value)) {
            $value = '';
        }

        return [strtoupper($key), $value];
    }


}