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
            'driver' => 'mysql',
            'host' => DB_HOST,
            'port' => DB_PORT,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}
