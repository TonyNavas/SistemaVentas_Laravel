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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['nuevo', 'en_proceso', 'listo', 'entregado', 'pagado'])->default('nuevo');
            $table->decimal('total',10,2);
            $table->decimal('pago',10,2)->nullable();
            $table->date('fecha');
            $table->text('notas');

            $table->foreignId('user_id')->constrained();
            $table->foreignId('table_id')->constrained();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
