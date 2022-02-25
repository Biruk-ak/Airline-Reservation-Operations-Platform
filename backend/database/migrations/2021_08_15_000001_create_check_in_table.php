<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_in', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained('airlines')->cascadeOnDelete();
            $table->string('status', 64)->default('draft')->index();
            $table->string('code', 64)->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('external_ref', 128)->nullable()->unique();
            $table->timestamp('effective_from')->nullable()->index();
            $table->timestamp('effective_to')->nullable()->index();
            $table->boolean('is_active')->default(false)->index();
            $table->unsignedTinyInteger('priority')->default(5)->index();
            $table->text('notes')->nullable();
            $table->string('region', 64)->nullable()->index();
            $table->string('station_code', 8)->nullable()->index();
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['airline_id', 'code']);
            $table->index(['airline_id', 'status', 'is_active']);
            $table->index(['airline_id', 'station_code', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_in');
    }
};
