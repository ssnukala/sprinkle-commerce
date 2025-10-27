<?php

/**
 * SN DB Forms (http://www.srinivasnukala.com)
 *
 * @link      https://github.com/ssnukala/ufsprinkle-autoforms/
 * @copyright Copyright (c) 2013-2016 Srinivas Nukala
 */

namespace UserFrosting\Sprinkle\Commerce\Database\Migrations\v600;

use UserFrosting\Sprinkle\Core\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

/**
 * Products table migration
 * Version 4.0.1
 *
 * See https://laravel.com/docs/5.4/migrations#tables
 * @extends Migration
 * @author Srinivas Nukala
 */

class CategoryTable extends Migration
{
    public static $dependencies = [
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\PermissionsTable'
    ];

    /**
     * {@inheritDoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('pr_category')) {
            $this->schema->create('pr_category', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100);
                $table->string('description', 500)->nullable();
                $table->string('slug', 100);
                $table->string('photo', 500)->nullable();
                $table->char('type', 2);
                $table->string('notes', 500)->nullable();
                $table->json('meta')->nullable();
                $table->char('status', 1)->default('A');
                $table->string('created_by', 20)->nullable();
                $table->string('updated_by', 20)->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->engine = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset = 'utf8';
            });
        }
        // Permissions are now managed via CRUD6 schemas
    }

    /**
     * {@inheritDoc}
     */
    public function down()
    {
        $this->schema->drop('pr_category');
    }
}