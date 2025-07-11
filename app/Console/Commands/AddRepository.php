<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddRepository extends Command
{
    protected $signature = 'add:repository {name}';

    protected $description = 'Genera archivo repository';

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
        $path = app_path("Repositories/{$folder}/{$name}Repository.php");
        $stub = base_path("stubs/repositoryTemplate.stub");

        if (!File::exists($path)) {
            $stub = File::get($stub);
            $content = str_replace(['{{name}}', '{{folder}}'],[$name, $folder], $stub);
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $this->info("✔️ archivo repository creado: " . $path);
        } else {
            $this->warn("⚠️ archivo repository ya existe: " . $path);
        }
    }
}
