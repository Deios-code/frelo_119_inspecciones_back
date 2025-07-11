<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddInterface extends Command
{
    protected $signature = 'add:interface {name}';

    protected $description = 'Genera archivo Interface';

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
        $path = app_path("Interfaces/{$folder}/{$name}RepositoryInterface.php");
        $stub = base_path("stubs/interfaceTemplate.stub");

        if (!File::exists($path)) {
            $stub = File::get($stub);
            $content = str_replace(['{{name}}', '{{folder}}'],[$name, $folder], $stub);
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $this->info("✔️ archivo interface creado: " . $path);
        } else {
            $this->warn("⚠️ archivo interface ya existe: " . $path);
        }
    }
}
