<?php

namespace App\Console\Commands;

use App\Models\Security\Route;
use Illuminate\Console\Command;
use \Illuminate\Support\Facades\Route as SystemRoutes;

class SyncRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza las rutas registradas con la tabla routes en la base de datos.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $routes = collect(SystemRoutes::getRoutes())->map(function ($route) {
            return [
                'menu_module' => $this->getMenuModule($route->action['controller'] ?? null),
                'module' => $this->getModule($route->action['controller'] ?? null),
                'action' => $this->getAction($route->action['controller'] ?? null),
                'route' => $route->uri,
            ];
        });

        foreach ($routes as $routeData) {
            if (isset($routeData['menu_module'])){
                Route::updateOrCreate(['route' => $routeData['route']], $routeData);
            }
        }

        $this->info("Rutas sincronizadas exitosamente.");
    }

    private function getMenuModule($controller): ?string
    {
        return $controller ? explode('\\', $controller)[3] ?? null : null;
    }

    private function getModule($controller): ?string
    {
        $fullName = $controller ? explode('@', explode('\\', $controller)[4])[0] ?? null : null;
        return $fullName ? str_replace('Controller', '', $fullName) : null;
    }

    private function getAction($controller): ?string
    {
        return $controller ? explode('@', $controller)[1] ?? null : null;
    }
}
