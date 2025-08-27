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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('status', ['planned','active','paused','done'])->default('planned')->index();
            $table->unsignedBigInteger('budget_cents')->default(0);
            $table->unsignedBigInteger('hourly_rate_cents')->default(0);
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable()->index();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropIndex('projects_status_idx');
            $table->dropIndex('projects_due_date_idx');

            $table->dropForeign('projects_client_id_foreign');
            $table->dropForeign('projects_owner_id_foreign');
        });

        Schema::dropIfExists('projects');
    }
};
