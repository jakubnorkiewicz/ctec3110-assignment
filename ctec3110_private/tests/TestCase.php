<?php


namespace App\Tests;

use GuzzleHttp;
use Illuminate\Database\Capsule\Manager as Capsule;

require './../../config.php';


class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $http;
    protected $connection;

    public function setUp(): void
    {
        parent::setUp();
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => DB_DRIVER,
            'host' => DB_HOST,
            'port' => DB_PORT,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
        ]);

        $capsule->bootEloquent();
        $capsule->setAsGlobal();

        $this->http = new GuzzleHttp\Client(['base_uri' => BASE_URL]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->http = null;
    }
}
