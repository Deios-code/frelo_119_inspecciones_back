<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddRoute extends Command
{
    protected $signature = 'add:route {name}';

    protected $description = 'Genera ruta';

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
        $stub = base_path("stubs/routeTemplate.stub");

        if (!File::exists($path)) {
            $stub = File::get($stub);
            $content = str_replace(['{{name}}', '{{folder}}'],[$name, $folder], $stub);
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $this->info("✔️ archivo ruta creado: " . $path);
        } else {
            $this->warn("⚠️ archivo ruta ya existe: " . $path);
        }
    }
}
