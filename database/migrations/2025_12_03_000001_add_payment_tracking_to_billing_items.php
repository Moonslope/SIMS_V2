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
      Schema::table('billing_items', function (Blueprint $table) {
         $table->decimal('amount_paid', 10, 2)->default(0)->after('amount');
         $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('amount_paid');
         $table->date('payment_date')->nullable()->after('status');
         $table->text('remarks')->nullable()->after('payment_date');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::table('billing_items', function (Blueprint $table) {
         $table->dropColumn(['amount_paid', 'status', 'payment_date', 'remarks']);
      });
   }
};
