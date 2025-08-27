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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('status', ['todo','doing','done'])->default('todo')->index();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('due_date')->nullable()->index();
            $table->string('priority')->default('normal')->index(); // 'low','normal','high' if you want, keep free-text MVP
            $table->unsignedInteger('estimate_minutes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropIndex('tasks_status_idx');
            $table->dropIndex('tasks_due_date_idx');
            $table->dropIndex('tasks_priority_idx');

            $table->dropForeign('tasks_project_id_foreign');
            $table->dropForeign('tasks_assignee_id_foreign');
        });

        Schema::dropIfExists('tasks');
    }
};
