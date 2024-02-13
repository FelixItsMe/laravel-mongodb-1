<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('raw_data', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->timestamp('device_time');
            $table->decimal('latitude', 20, 15);
            $table->decimal('longitude', 20, 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_data');
    }
};
