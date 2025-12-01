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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('learner_reference_number');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('extension_name')->nullable();
            $table->string('nickname')->nullable();
            $table->date('birthdate');
            $table->string('birthplace');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('nationality');
            $table->string('spoken_dialect');
            $table->string('other_spoken_dialect')->nullable();
            $table->string('religion');
            $table->text('address');
            $table->enum('student_status', ['active', 'inactive', 'transferred', 'dropped'])->default('active');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
