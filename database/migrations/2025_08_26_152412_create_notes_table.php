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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->morphs('notable'); // notable_type, notable_id
            $table->text('body');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table): void {
            // remove the morph index and columns explicitly
            $table->dropIndex('notes_notable_type_notable_id_index');
            $table->dropColumn('notable_type');
            $table->dropColumn('notable_id');

            $table->dropForeign('notes_user_id_foreign');
        });

        Schema::dropIfExists('notes');
    }
};
