<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 64)->default('ops_agent')->index();
            $table->foreignId('airline_id')->nullable()->constrained('airlines')->nullOnDelete();
            $table->string('station_code', 8)->nullable()->index();
            $table->string('employee_number', 64)->nullable()->index();
            $table->string('department', 128)->nullable();
            $table->string('phone', 64)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('last_login_at')->nullable();
            $table->json('preferences')->nullable();
            $table->json('permissions_cache')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};
