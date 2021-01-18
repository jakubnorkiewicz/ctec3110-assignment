<?php

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Schema;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
    /**
     * Object used for connecting with database.
     *
     * @var Capsule
     */
    public $capsule;

    /**
     * Function used for interacting with database.
     *
     * @var Schema
     */
    public $schema;

    /**
     * Initialise database migration.
     *
     * @return void
     */
    public function init()
    {
        $this->capsule = new Capsule;
        $this->capsule->addConnection([
            'driver' => $_ENV['DB_DRIVER_15'],
            'host' => $_ENV['DB_HOST_15'],
            'port' => $_ENV['DB_PORT_15'],
            'database' => $_ENV['DB_NAME_15'],
            'username' => $_ENV['DB_USER_15'],
            'password' => $_ENV['DB_PASSWORD_15'],
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}
