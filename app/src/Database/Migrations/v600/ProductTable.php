<?php

/**
 * SN DB Forms (http://www.srinivasnukala.com)
 *
 * @link      https://github.com/ssnukala/ufsprinkle-autoforms/
 * @copyright Copyright (c) 2013-2016 Srinivas Nukala
 */

namespace UserFrosting\Sprinkle\Commerce\Database\Migrations\v600;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

/**
 * Products table migration
 * Version 4.0.1
 *
 * See https://laravel.com/docs/5.4/migrations#tables
 * @extends Migration
 * @author Srinivas Nukala
 */
class ProductTable extends Migration
{
    public static $dependencies = [
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\PermissionsTable',
        '\UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\CategoryTable',
    ];

    /**
     * {@inheritDoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('pr_product')) {
            $this->schema->create('pr_product', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->string('name', 100)->nullable();
                $table->text('description')->nullable();
                $table->string('slug', 100);
                $table->string('photo', 500)->nullable();
                $table->char('type', 2)->nullable();
                $table->dateTime('active_date')->nullable();
                $table->decimal('unit_price', 10, 2)->default(0.00);
                $table->decimal('tax', 10, 2)->default(0.00);
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
                $table->index('category_id');
            });
        }
        // Permissions are now managed via CRUD6 schemas
    }

    /**
     * {@inheritDoc}
     */
    public function down()
    {
        $this->schema->drop('pr_product');
    }
}