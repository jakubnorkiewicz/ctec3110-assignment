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
            'driver' => $_ENV['DB_DRIVER'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
        ]);

        $capsule->bootEloquent();
        $capsule->setAsGlobal();

        $this->http = new GuzzleHttp\Client(['base_uri' => $_ENV['BASE_URL']]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->http = null;
    }
}
