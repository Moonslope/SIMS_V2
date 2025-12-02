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
      Schema::table('payments', function (Blueprint $table) {
         $table->unsignedBigInteger('billing_item_id')->nullable()->after('billing_id');
         $table->foreign('billing_item_id')->references('id')->on('billing_items')->onDelete('set null');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::table('payments', function (Blueprint $table) {
         $table->dropForeign(['billing_item_id']);
         $table->dropColumn('billing_item_id');
      });
   }
};
