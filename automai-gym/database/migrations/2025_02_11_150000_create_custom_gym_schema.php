<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 0. SESSIONS (Required for Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // 1. ROLES
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id_rol');
            $table->string('nombre_rol', 30)->unique();
            $table->string('descripcion_rol', 120)->nullable();
        });

        // 2. USUARIOS
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->string('correo_usuario', 120)->unique();
            $table->string('hash_contrasena_usuario', 255);
            $table->string('nombre_mostrado_usuario', 80);
            $table->enum('estado_usuario', ['activo', 'bloqueado', 'pendiente'])->default('activo');
            $table->dateTime('fecha_creacion_usuario')->useCurrent();
            $table->dateTime('fecha_ultimo_acceso_usuario')->nullable();
            // Laravel Auth helper columns (optional but good for compatibility if needed later, but sticking to schema for now)
            $table->rememberToken();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
        });

        // 3. USUARIOS_ROLES
        Schema::create('usuarios_roles', function (Blueprint $table) {
            $table->increments('id_usuario_rol');
            $table->unsignedInteger('id_usuario');
            $table->unsignedInteger('id_rol');
            $table->dateTime('fecha_asignacion_rol')->useCurrent();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_rol')->references('id_rol')->on('roles')->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['id_usuario', 'id_rol'], 'uq_usuario_rol');
        });

        // 4. PERFIL
        Schema::create('perfiles_usuario', function (Blueprint $table) {
            $table->increments('id_perfil_usuario');
            $table->unsignedInteger('id_usuario')->unique();
            $table->string('nombre_real_usuario', 100)->nullable();
            $table->string('telefono_usuario', 30)->nullable();
            $table->string('ruta_foto_perfil_usuario', 180)->nullable();
            $table->enum('objetivo_principal_usuario', ['definir', 'volumen', 'rendimiento', 'salud'])->default('salud');
            $table->unsignedTinyInteger('dias_entrenamiento_semana_usuario')->default(3);
            $table->enum('nivel_usuario', ['principiante', 'intermedio', 'avanzado'])->default('principiante');
            $table->decimal('peso_kg_usuario', 5, 2)->nullable();
            $table->unsignedSmallInteger('altura_cm_usuario')->nullable();
            $table->date('fecha_inicio_usuario')->nullable();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });

        // 5. AJUSTES
        Schema::create('ajustes_usuario', function (Blueprint $table) {
            $table->increments('id_ajustes_usuario');
            $table->unsignedInteger('id_usuario')->unique();
            $table->boolean('notificaciones_entrenamiento_activas')->default(true);
            $table->boolean('notificaciones_clases_activas')->default(true);
            $table->enum('tono_ia_coach', ['directo', 'motivador'])->default('directo');
            $table->enum('idioma_preferido', ['es', 'en'])->default('es');
            $table->enum('semana_empieza_en', ['lunes', 'domingo'])->default('lunes');

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });

        // 6. SEGURIDAD
        Schema::create('seguridad_usuario', function (Blueprint $table) {
            $table->increments('id_seguridad_usuario');
            $table->unsignedInteger('id_usuario')->unique();
            $table->boolean('requiere_cambio_contrasena')->default(false);
            $table->dateTime('fecha_ultimo_cambio_contrasena')->nullable();
            $table->unsignedInteger('intentos_fallidos_login')->default(0);
            $table->dateTime('fecha_ultimo_intento_fallido')->nullable();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });

        // 7. TIPOS CLASE
        Schema::create('tipos_clase', function (Blueprint $table) {
            $table->increments('id_tipo_clase');
            $table->string('nombre_tipo_clase', 60)->unique();
            $table->string('descripcion_tipo_clase', 180)->nullable();
        });

        // 8. CLASES GIMNASIO
        Schema::create('clases_gimnasio', function (Blueprint $table) {
            $table->increments('id_clase_gimnasio');
            $table->unsignedInteger('id_tipo_clase');
            $table->string('titulo_clase', 100);
            $table->string('descripcion_clase', 220)->nullable();
            $table->string('instructor_clase', 80)->nullable();
            $table->dateTime('fecha_inicio_clase');
            $table->dateTime('fecha_fin_clase');
            $table->unsignedSmallInteger('cupo_maximo_clase')->default(20);
            $table->enum('estado_clase', ['borrador', 'publicada', 'cancelada'])->default('publicada');
            $table->dateTime('fecha_creacion_clase')->useCurrent();

            $table->foreign('id_tipo_clase')->references('id_tipo_clase')->on('tipos_clase')->onDelete('restrict')->onUpdate('cascade');
        });

        // 9. RESERVAS
        Schema::create('reservas_clase', function (Blueprint $table) {
            $table->increments('id_reserva_clase');
            $table->unsignedInteger('id_usuario');
            $table->unsignedInteger('id_clase_gimnasio');
            $table->enum('estado_reserva', ['reservada', 'cancelada', 'asistio', 'no_asistio'])->default('reservada');
            $table->dateTime('fecha_reserva')->useCurrent();
            $table->enum('origen_reserva', ['usuario', 'ia_coach'])->default('usuario');
            $table->string('notas_reserva', 180)->nullable();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_clase_gimnasio')->references('id_clase_gimnasio')->on('clases_gimnasio')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['id_usuario', 'id_clase_gimnasio'], 'uq_usuario_clase');
        });

        // 10. CHECK-IN
        Schema::create('checkin_reserva', function (Blueprint $table) {
            $table->increments('id_checkin_reserva');
            $table->unsignedInteger('id_reserva_clase')->unique();
            $table->boolean('realizado_checkin')->default(false);
            $table->dateTime('fecha_checkin')->nullable();
            $table->enum('metodo_checkin', ['manual', 'qr', 'recepcion'])->default('manual');

            $table->foreign('id_reserva_clase')->references('id_reserva_clase')->on('reservas_clase')->onDelete('cascade')->onUpdate('cascade');
        });

        // 11. EJERCICIOS
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->increments('id_ejercicio');
            $table->string('nombre_ejercicio', 120)->unique();
            $table->enum('grupo_muscular_principal', ['pecho', 'espalda', 'pierna', 'hombro', 'biceps', 'triceps', 'core', 'cardio', 'fullbody']);
            $table->string('descripcion_ejercicio', 220)->nullable();
        });

        // 12. RUTINAS USUARIO
        Schema::create('rutinas_usuario', function (Blueprint $table) {
            $table->increments('id_rutina_usuario');
            $table->unsignedInteger('id_usuario');
            $table->string('nombre_rutina_usuario', 140);
            $table->enum('objetivo_rutina_usuario', ['definir', 'volumen', 'rendimiento', 'salud'])->default('salud');
            $table->enum('nivel_rutina_usuario', ['principiante', 'intermedio', 'avanzado'])->default('principiante');
            $table->unsignedSmallInteger('duracion_estimada_minutos')->nullable();
            $table->enum('origen_rutina', ['usuario', 'ia_coach', 'plantilla'])->default('usuario');
            $table->text('instrucciones_rutina')->nullable();
            $table->dateTime('fecha_creacion_rutina')->useCurrent();
            $table->boolean('rutina_activa')->default(true);

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });

        // 13. RUTINAS_EJERCICIOS
        Schema::create('rutinas_ejercicios', function (Blueprint $table) {
            $table->increments('id_rutina_ejercicio');
            $table->unsignedInteger('id_rutina_usuario');
            $table->unsignedInteger('id_ejercicio');
            $table->unsignedSmallInteger('orden_en_rutina')->default(1);
            $table->unsignedSmallInteger('series_objetivo')->default(3);
            $table->string('repeticiones_objetivo', 30)->default('8-12');
            $table->decimal('peso_objetivo_kg', 6, 2)->nullable();
            $table->decimal('rpe_objetivo', 3, 1)->nullable();
            $table->unsignedSmallInteger('descanso_segundos')->nullable();
            $table->string('notas_ejercicio', 220)->nullable();

            $table->foreign('id_rutina_usuario')->references('id_rutina_usuario')->on('rutinas_usuario')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_ejercicio')->references('id_ejercicio')->on('ejercicios')->onDelete('restrict')->onUpdate('cascade');
        });

        // 14. REGISTROS PROGRESO
        Schema::create('registros_progreso', function (Blueprint $table) {
            $table->increments('id_registro_progreso');
            $table->unsignedInteger('id_usuario');
            $table->date('fecha_registro');
            $table->decimal('peso_kg_registro', 5, 2)->nullable();
            $table->decimal('cintura_cm_registro', 5, 2)->nullable();
            $table->decimal('pecho_cm_registro', 5, 2)->nullable();
            $table->decimal('cadera_cm_registro', 5, 2)->nullable();
            $table->string('notas_progreso', 220)->nullable();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['id_usuario', 'fecha_registro'], 'uq_usuario_fecha');
        });

        // 15. CONVERSACIONES IA
        Schema::create('conversaciones_ia', function (Blueprint $table) {
            $table->increments('id_conversacion_ia');
            $table->unsignedInteger('id_usuario');
            $table->string('titulo_conversacion', 120)->nullable();
            $table->dateTime('fecha_creacion_conversacion')->useCurrent();
            $table->boolean('conversacion_activa')->default(true);

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });

        // 16. MENSAJES IA
        Schema::create('mensajes_ia', function (Blueprint $table) {
            $table->increments('id_mensaje_ia');
            $table->unsignedInteger('id_conversacion_ia');
            $table->enum('rol_mensaje', ['usuario', 'asistente', 'sistema']);
            $table->text('contenido_mensaje');
            $table->json('acciones_mensaje')->nullable();
            $table->dateTime('fecha_mensaje')->useCurrent();

            $table->foreign('id_conversacion_ia')->references('id_conversacion_ia')->on('conversaciones_ia')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes_ia');
        Schema::dropIfExists('conversaciones_ia');
        Schema::dropIfExists('registros_progreso');
        Schema::dropIfExists('rutinas_ejercicios');
        Schema::dropIfExists('rutinas_usuario');
        Schema::dropIfExists('ejercicios');
        Schema::dropIfExists('checkin_reserva');
        Schema::dropIfExists('reservas_clase');
        Schema::dropIfExists('clases_gimnasio');
        Schema::dropIfExists('tipos_clase');
        Schema::dropIfExists('seguridad_usuario');
        Schema::dropIfExists('ajustes_usuario');
        Schema::dropIfExists('perfiles_usuario');
        Schema::dropIfExists('usuarios_roles');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sessions');
    }
};
