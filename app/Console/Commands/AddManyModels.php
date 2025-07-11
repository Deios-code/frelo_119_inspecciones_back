<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddManyModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:manymodels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera los modelos para las tablas especificadas y agrega la línea $table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelos = [
            'gpp_pa_identificacion' => 'GppPaIdentificacion',
            'gpp_pa_identificacion_entidades' => 'GppPaIdentificacionEntidad',
            'gpp_pa_alineaciones_planes' => 'GppPaAlineacionPlan',
            'gpp_lineas_tecnologicas' => 'GppLineaTecnologica',
            'gpp_pa_lineas_tecnologicas' => 'GppPaLineaTecnologica',
            'gpp_pa_cadena_valor' => 'GppPaCadenaValor',
            'gpp_pa_cadena_valor_objetivos_especificos' => 'GppPaCadenaValorObjetivoEspecifico',
            'gpp_pa_cadena_valor_productos' => 'GppPaCadenaValorProducto',
            'gpp_pa_cadena_valor_medidos' => 'GppPaCadenaValorMedido',
            'gpp_pa_cadena_valor_meta' => 'GppPaCadenaValorMeta',
            'gpp_pa_cadena_valor_actividades' => 'GppPaCadenaValorActividad',
            'gpp_pa_cronograma_ejecucion' => 'GppPaCronogramaEjecucion',
            'gpp_pa_cronograma_ejecucion_actividades' => 'GppPaCronogramaEjecucionActividad',
            'gpp_pa_lineas_inversion' => 'GppPaLineaInversion',
            'gpp_pa_lineas_inversion_actividades' => 'GppPaLineaInversionActividad',
            'gpp_pa_analisis_riesgos' => 'GppPaAnalisisRiesgo',
            'gpp_pa_analisis_riesgos_identificacion' => 'GppPaAnalisisRiesgoIdentificacion',
            'gpp_documentos_anexos' => 'GppDocumentoAnexo',
            'gpp_pa_documentos_anexos_identificacion' => 'GppPaDocumentoAnexoIdentificacion',
        ];

        foreach ($modelos as $tabla => $modelo) {
            // Obtener columnas
            $columnas = DB::getSchemaBuilder()->getColumnListing($tabla);
            $fillable = array_filter($columnas, function ($columna) {
                return $columna !== 'id' &&
                    !str_ends_with($columna, '_id') &&
                    !in_array($columna, ['created_at', 'updated_at']);
            });

            // Contenido del archivo del modelo
            $contenido = <<<EOD
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$modelo} extends Model
{
    use HasFactory;

    protected \$table = '{$tabla}';

    protected \$fillable = [
EOD;

            foreach ($fillable as $campo) {
                $contenido .= "\n       '{$campo}',";
            }

            $contenido .= "\n    ];\n\n}";

            // Guardar archivo en Models o en app/ si no existe Models
            $directorio = app_path('Models');
            if (!File::exists($directorio)) {
                File::makeDirectory($directorio, 0755, true);
            }

            $ruta = "{$directorio}/{$modelo}.php";
            File::put($ruta, $contenido);

            $this->info("✅ Modelo {$modelo}.php generado correctamente con \$fillable y HasFactory.");
        }

        $this->info("🚀 ¡Todos los modelos generados con estructura limpia y ordenada!");
    }
}
