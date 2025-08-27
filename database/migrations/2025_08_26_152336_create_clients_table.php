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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('company')->nullable()->index();
            $table->string('website')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete(); // account owner
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table): void {
            if (Schema::hasColumn('clients', 'email')) {
                $table->dropIndex('clients_email_idx');
            }

            if (Schema::hasColumn('clients', 'company')) {
                $table->dropIndex('clients_company_idx');
            }
        });

        // Drop FK before dropping the table (extra explicit)
        Schema::table('clients', function (Blueprint $table): void {
            if (Schema::hasColumn('clients', 'owner_id')) {
                $table->dropForeign('clients_owner_id_foreign');
            }
        });

        Schema::dropIfExists('clients');
    }
};
