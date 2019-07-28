<?php

namespace StudioAtual\Tests;

use Phinx\Config\Config;
use Phinx\Migration\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Illuminate\Database\Capsule\Manager as Capsule;

abstract class DatabaseTestCase extends TestCase
{
    private $manager;

    final public function getConnection()
    {

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => env('DB_DRIVER', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'tests'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix' => env('DB_PREFIX', '')
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $configArray['paths'] = [ 'migrations' => __DIR__ . '/../../database/migrations', 'seeds' => __DIR__ . '/../../database/seeds' ];
        $configArray['environments']['default_migration_table'] = 'migrations';
        $configArray['environments']['test'] = [
            'adapter' => env('DB_DRIVER', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' =>  env('DB_PORT', 3306),
            'name' => env('DB_DATABASE', 'tests'),
            'user' => env('DB_USERNAME', 'root'),
            'pass' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
        ];
        $config = new Config($configArray);
        $this->manager = new Manager($config, new StringInput(' '), new NullOutput());
    }

    final public function setUp():void
    {
        if (!$this->manager) {
            $this->getConnection();
        }
        $this->manager->migrate('test');
        $this->manager->seed('test');
    }

    final public function tearDown():void
    {
        $this->manager->rollback('test', 0, true);
    }

    final public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
