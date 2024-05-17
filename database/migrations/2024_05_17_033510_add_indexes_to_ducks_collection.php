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
        Schema::collection('ducks', function (Blueprint $table) {
            $table->index('health');
            $table->index('speed');
            $table->index('created_at');
            $table->index('equipment.type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::collection('ducks', function (Blueprint $table) {
            $table->dropIndex('health_1');
            $table->dropIndex('speed_1');
            $table->dropIndex('created_at_1');
            $table->dropIndex('equipment.type_1');
        });
    }
};
