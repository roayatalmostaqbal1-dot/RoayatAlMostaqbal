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
        Schema::create('api_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique(); // e.g., 'api.users.index'
            $table->string('route_path'); // e.g., '/api/users'
            $table->string('http_method'); // GET, POST, PUT, DELETE, PATCH
            $table->foreignId('permission_id')->nullable()->constrained('permissions')->onDelete('set null');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('route_name');
            $table->index('permission_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_routes');
    }
};

