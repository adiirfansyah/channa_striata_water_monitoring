<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_sensor_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_logs', function (Blueprint $table) {
            $table->id();
            $table->float('suhu')->nullable();
            $table->float('ph')->nullable();
            $table->float('kekeruhan')->nullable();
            $table->timestamps(); // Ini akan membuat kolom created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};