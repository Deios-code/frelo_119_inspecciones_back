<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('de_name', 255);
            $table->string('de_dane', 255)->unique();
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('ci_name', 255);
            $table->string('ci_dane', 255)->unique();
            $table->foreignId('ci_de_id')
                ->constrained('departments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('us_name', 255);
            $table->string('us_last_name', 255);
            $table->enum('us_type_document', ['CEDULA', 'PASAPORTE']);
            $table->bigInteger('us_document')->unique();
            $table->string('us_address', 255);
            $table->date('us_birthday')->nullable();
            $table->bigInteger('us_phone')->unique();
            $table->enum('us_habeas_data', ['SI', 'NO']);
            $table->enum('us_exoneration', ['SI', 'NO'])->nullable();
            $table->string('us_email', 255)->unique();
            $table->string('us_password', 255);
            $table->foreignId('us_ci_id')
                ->constrained('cities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('st_name', 255);
            $table->integer('st_nit')->unique()->nullable();
            $table->string('st_address', 255);
            $table->string('st_longitude', 255)->nullable()->unique();
            $table->string('st_latitude', 255)->nullable()->unique();
            $table->bigInteger('st_phone')->unique()->nullable();
            $table->foreignId('st_user_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreignId('st_ci_id')
                ->constrained('cities')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->string('es_name_establishment', 255);
            $table->integer('es_nit')->unique();
            $table->bigInteger('es_phone')->unique();
            $table->string('es_address', 255);
            $table->string('es_commune', 255);
            $table->string('es_neighborhood', 255);
            $table->string('es_email_establishment', 255)->unique();
            $table->foreignId('es_station_id')
                ->nullable()
                ->constrained('stations')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreignId('es_us_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreignId('es_ci_id')
                ->nullable()
                ->constrained('cities')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('inspectors', function (Blueprint $table) {
            $table->id();
            $table->enum('ins_range', ['CAPITAN','TENIENTE','SUBTENIENTE','SARGENTO','CABO','BOMBERO','INSPECTOR'])->default('INSPECTOR');
            $table->foreignId('ins_id_user')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('ins_id_station')
                ->constrained('stations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('pr_name',255);
            $table->timestamps();
        });

        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('fo_name',255);
            $table->enum('fo_type',['CUALITATIVO','CUANTITATIVO']);
            $table->integer('fo_score');
            $table->enum('fo_edit',['SI','NO'])->default('NO');
            $table->foreignId('fo_processes_id')
                ->nullable()
                ->constrained('processes')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('se_name',255);
            $table->integer('se_percentage');
            $table->foreignId('se_fo_id')
                ->constrained('forms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('qu_statement',255);
            $table->integer('qu_score')->nullable();
            $table->enum('qu_nature',['CUANTITATIVO','CUALITATIVA']);
            $table->enum('qu_type',['TEXTO','MULTIPLE','UNICA','ARCHIVO']);
            $table->enum('qu_required',['SI','NO'])->default('NO');
            $table->foreignId('qu_se_id')
                ->constrained('sections')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->enum('in_state',['APROBADA','RECHAZADA','PENDIENTE POR EVALUAR','APROBADA CON OBSERVACIONES']);
            $table->integer('in_score');
            $table->integer('in_consecutive');
            $table->foreignId('in_inspectors_id')
                ->constrained('inspectors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('in_establishment_id')
                ->constrained('establishments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usf_user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('usf_forms_id')
                ->constrained('forms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_sections', function (Blueprint $table) {
            $table->id();
            $table->string('uss_name',255);
            $table->foreignId('uss_usf_id')
                ->constrained('user_forms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_questions', function (Blueprint $table) {
            $table->id();
            $table->string('usq_statement',1000);
            $table->enum('usq_type',['TEXTO','MULTIPLE','UNICA','FILE']);
            $table->foreignId('usq_uss_id')
                ->nullable()
                ->constrained('user_sections')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreignId('usq_usf_id')
                ->nullable()
                ->constrained('user_forms')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreignId('usq_se_id')
                ->nullable()
                ->constrained('sections')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('options_answer', function (Blueprint $table) {
            $table->id();
            $table->string('op_text',255);
            $table->enum('op_correct',['SI','NO']);
            $table->string('op_file',255)->nullable();
            $table->foreignId('op_qu_id')
                ->nullable()
                ->constrained('questions')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreignId('op_usq_id')
                ->nullable()
                ->constrained('user_questions')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('user_options_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uso_us_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreignId('uso_op_id')
                ->nullable()
                ->constrained('options_answer')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_options_answers');
        Schema::dropIfExists('options_answer');
        Schema::dropIfExists('user_questions');
        Schema::dropIfExists('user_sections');
        Schema::dropIfExists('user_forms');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('forms');
        Schema::dropIfExists('processes');
        Schema::dropIfExists('inspectors');
        Schema::dropIfExists('establishments');
        Schema::dropIfExists('users');
        Schema::dropIfExists('stations');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('departments');
    }
};
