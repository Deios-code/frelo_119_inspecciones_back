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
        $name = ucfirst($this->argument('name'));

        $paths = [
            'interface' => app_path("Interfaces/{$name}RepositoryInterface.php"),
            'repository' => app_path("Repositories/{$name}Repository.php"),
            'service' => app_path("Services/{$name}/{$name}Service.php"),
        ];

        $stubs = [
            'interface' => base_path("stubs/interface.stub"),
            'repository' => base_path("stubs/repository.stub"),
            'service' => base_path("stubs/service.stub"),
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
