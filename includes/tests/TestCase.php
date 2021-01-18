<?php


namespace App\Tests;

use GuzzleHttp;
use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $http;
    protected $connection;

    public function setUp(): void
    {
        $dotenv = Dotenv::createImmutable('../../../');
        $dotenv->load();

        parent::setUp();
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => $_ENV['DB_DRIVER_15'],
            'host' => $_ENV['DB_HOST_15'],
            'port' => $_ENV['DB_PORT_15'],
            'database' => $_ENV['DB_NAME_15'],
            'username' => $_ENV['DB_USER_15'],
            'password' => $_ENV['DB_PASSWORD_15'],
        ]);

        $capsule->bootEloquent();
        $capsule->setAsGlobal();

        $this->http = new GuzzleHttp\Client(['base_uri' => $_ENV['BASE_URL_15']]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->http = null;
    }
}
