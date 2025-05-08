<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use Illuminate\Database\Seeder;
use Mbox\BackCore\Models\Security\Route;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::all();

        foreach ($routes as $route){
            Permission::updateOrCreate([
                'id' => $route->id
            ],
                [
                    'roles_id' => 1,
                    'routes_id' => $route->id
                ]);
        }
    }
}
