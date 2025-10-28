<?php

/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce\Database\Migrations\v600;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use UserFrosting\Sprinkle\Account\Database\Models\Role;
use UserFrosting\Sprinkle\Core\Database\Migration;

/**
 * Roles table migration
 * Roles replace "groups" in UF 0.3.x.  Users acquire permissions through roles.
 * Version 4.0.0
 *
 * See https://laravel.com/docs/5.4/migrations#tables
 * @extends Migration
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class ProductRolesTable extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function up(): void
    {
        if ($this->schema->hasTable('roles')) {
            // Add default roles
            //
            $roles = [
                'product-admin' => new Role([
                    'slug' => 'product-admin',
                    'name' => 'Product Admin',
                    'description' => 'Product Admin Role.'
                ])
            ];

            foreach ($roles as $slug => $role) {
                if (!Role::where('slug', $slug)->first()) {
                    $role->save();
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function down(): void
    {
        Role::whereIn('slug', ['product-admin'])->delete();
    }
}
