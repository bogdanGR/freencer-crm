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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique(); // global unique
            $table->enum('status', ['draft','sent','paid','overdue'])->default('draft')->index();
            $table->char('currency', 3)->default('EUR');
            $table->unsignedBigInteger('subtotal_cents')->default(0);
            $table->unsignedBigInteger('tax_cents')->default(0);
            $table->unsignedBigInteger('total_cents')->default(0);
            $table->date('issued_on')->nullable()->index();
            $table->date('due_on')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table): void {
            $table->dropUnique('invoices_number_uk');
            $table->dropIndex('invoices_status_idx');
            $table->dropIndex('invoices_issued_on_idx');
            $table->dropIndex('invoices_due_on_idx');

            $table->dropForeign('invoices_client_id_foreign');
        });

        Schema::dropIfExists('invoices');
    }
};
