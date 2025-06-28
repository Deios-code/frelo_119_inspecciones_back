<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddService extends Command
{
    protected $signature = 'add:service {ruta}';
    protected $description = 'Crear un servicio dentro de la carpeta Services';

    public function handle()
    {
        $ruta = $this->argument('ruta');
        $partesRuta = explode('/', $ruta);
        $name = end($partesRuta);
        array_pop($partesRuta);
        $folder = implode(',', $partesRuta);
        $path = app_path("Services/{$folder}/{$name}.php");
        $stub = base_path("stubs/serviceTemplate.stub");

        if (!File::exists($path)) {
            $stub = File::get($stub);
            $content = str_replace(['{{name}}', '{{folder}}'],[$name, $folder], $stub);
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $this->info("✔️ archivo service creado: " . $path);
        } else {
            $this->warn("⚠️ archivo service ya existe: " . $path);
        }
    }
}
