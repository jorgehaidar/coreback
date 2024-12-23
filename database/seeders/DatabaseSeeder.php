<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use App\Models\Security\RoleUser;
use App\Models\Security\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $totalStartTime = microtime(true);

        Artisan::call('route:sync');

        $models = [
            User::class,
            Role::class,
            RoleUser::class,
        ];

        foreach ($models as $model){
            $this->runSeeder($model);
        }

        $this->call([PermissionSeeder::class]);

        $totalExecutionTime = round((microtime(true) - $totalStartTime) * 1000, 2);
        $this->command->info("<fg=green>  Total seeding time: {$totalExecutionTime} ms</>");
    }

    private function runSeeder(string $modelName): void
    {
        $model = resolve($modelName);
        $startTime = microtime(true);
        $padding = 140;

        $message = "  Seeding {$model->getTable()} table";
        $dots = str_repeat('.', $padding - strlen($message));
        $this->command->getOutput()->write("{$message}");
        $this->command->getOutput()->write("<fg=gray>{$dots}</>");
        $this->command->getOutput()->write(' <fg=yellow>RUNNING</>');

        $json = File::get("database/data/{$model->getTable()}.json");
        $data = json_decode($json, true);

        foreach ($data as $obj) {
            $model::query()->updateOrCreate(['id' => $obj['id']], $obj);
        }

        $executionTime = round((microtime(true) - $startTime) * 1000);

        $this->command->line('');
        $message = "  Seeding {$model->getTable()} table";

        $dots = str_repeat('.', $padding - strlen($message) - strlen($executionTime));

        $this->command->getOutput()->write("{$message}");
        $this->command->getOutput()->write("<fg=gray>{$dots}</>");
        $this->command->getOutput()->write("<fg=gray>{$executionTime} ms</>");
        $this->command->getOutput()->write('<fg=green> DONE</>');

        $this->command->line('');
        $this->command->line('');
    }
}
