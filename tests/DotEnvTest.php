<?php


namespace Santutu\LaravelDotEnv\Tests;


use Illuminate\Support\Facades\Artisan;
use Santutu\LaravelDotEnv\DotEnv;
use Santutu\LaravelDotEnv\Facade;
use Santutu\LaravelDotEnv\ServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;

class DotEnvTest extends \Orchestra\Testbench\TestCase
{

    public function test_dot_env()
    {
        $dotEnvFilePath = app()->environmentFilePath();
        if (file_exists($dotEnvFilePath)) {
            unlink($dotEnvFilePath);
        }
        $dotEnv = new DotEnv($dotEnvFilePath);
        $this->assertTrue(file_exists($dotEnvFilePath));
        $this->assertNull($dotEnv->get('TEST'));
        $this->assertEquals(null, $dotEnv->getNewValue());
        $this->assertEquals(null, $dotEnv->getOldValue());

        //can set
        $dotEnv->set('TEST', 'value');
        $this->assertEquals('value', $dotEnv->getNewValue());
        $this->assertEquals(null, $dotEnv->getOldValue());
        $this->assertEquals($dotEnv->get('TEST'), 'value');
        $this->assertEquals($dotEnv->getKey(), 'TEST');

        $dotEnv->set('TEST', 'value2');
        $this->assertEquals('value2', $dotEnv->getNewValue());
        $this->assertEquals('value', $dotEnv->getOldValue());
        $this->assertEquals($dotEnv->get('TEST'), 'value2');
        $this->assertEquals($dotEnv->getKey(), 'TEST');

        //can set null
        $dotEnv->set('TEST', null);
        $this->assertEquals(null, $dotEnv->getNewValue());
        $this->assertEquals('value2', $dotEnv->getOldValue());
        $this->assertNull($dotEnv->get('TEST'));

        //can set empty
        $dotEnv->set('TEST', '');
        $this->assertEquals('', $dotEnv->getNewValue());
        $this->assertEquals(null, $dotEnv->getOldValue());
        $this->assertNotNull($dotEnv->get('TEST'));
        $this->assertEquals('', $dotEnv->get('TEST'));

        //can delete
        $dotEnv->delete('TEST');
        $this->assertNull($dotEnv->get('TEST'));

        if (file_exists($dotEnvFilePath)) {
            unlink($dotEnvFilePath);
        }

        //can copy
        if (!file_exists($dotEnvFilePath)) {
            $f = fopen('.env.example', 'w');
            fclose($f);
        }
        $dotEnv->copy('.env.example', $dotEnvFilePath);
        $dotEnv->copy('.env.example', $dotEnvFilePath);
        $this->assertTrue(file_exists($dotEnvFilePath));

        //artisan set
        Artisan::call('env:set TEST value');
        Artisan::call('env:set TEST_T value2');
        $this->assertEquals('value', $dotEnv->get('TEST'));
        $this->assertEquals('value2', $dotEnv->get('TEST_T'));

        //artisan get
        Artisan::call('env:get TEST TEST_T', [], new ConsoleOutput());

        //artisan delete
        Artisan::call('env:delete TEST TEST_T');
        $this->assertNull($dotEnv->get('TEST'));
        $this->assertNull($dotEnv->get('TEST_T'));

        //facade
        $this->assertNull(\DotEnv::get('TEST'));
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'DotEnv' => Facade::class
        ];
    }
}