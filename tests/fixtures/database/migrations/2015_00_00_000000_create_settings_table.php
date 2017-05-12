<?php

use Arcanedev\Support\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;

/**
 * Class     CreateSettingsTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateSettingsTable extends Migration
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * CreateSettingsTable constructor.
     */
    public function __construct()
    {
        $configs          = config('settings.drivers.database.options');
        $this->connection = Arr::get($configs, 'connection', null);
        $this->table      = Arr::get($configs, 'table', 'settings');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createSchema(function(Blueprint $table) {
            $table->unsignedInteger('user_id')->default(0);
            $table->string('key');
            $table->text('value');
            $table->timestamps();

            $table->unique(['user_id', 'key']);
        });
    }
}
