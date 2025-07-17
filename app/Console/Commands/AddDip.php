<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddDip extends Command
{
    protected $signature = 'add:dip {name}';

    protected $description = 'Genera Service, Repository, Interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ruta = $this->argument('name');
        $partesRuta = explode('/', $ruta);
        $name = end($partesRuta);
        array_pop($partesRuta);
        $folder = implode(',', $partesRuta);
        $path = base_path("Routes/api/{$name}.php");
        if(!empty($folder)) {
            $path = base_path("Routes/api/{$folder}/{$name}.php");
        }

        $paths = [
            'interface' => app_path("Interfaces/{$folder}/{$name}RepositoryInterface.php"),
            'repository' => app_path("Repositories/{$folder}/{$name}Repository.php"),
            'service' => app_path("Services/{$folder}/{$name}Service.php"),
            'route' => app_path("Routes/{$folder}Routes.php"),
            'controller' => app_path("Http/Controllers/{$name}Controller.php"),
        ];

        $stubs = [
            'interface' => base_path("stubs/interface.stub"),
            'repository' => base_path("stubs/repository.stub"),
            'service' => base_path("stubs/service.stub"),
            'route' => base_path("stubs/routeTemplate.stub"),
            'controller' => base_path("stubs/controllerTemplate.stub"),
        ];

        foreach ($paths as $type => $path) {
            if (!File::exists($path)) {
                $stub = File::get($stubs[$type]);
                $content = str_replace('{{name}}', $name, $stub);
                File::ensureDirectoryExists(dirname($path));
                File::put($path, $content);
                $this->info("✔️ {$type} creado: " . $path);
            } else {
                $this->warn("⚠️ {$type} ya existe: " . $path);
            }
        }
    }
}
